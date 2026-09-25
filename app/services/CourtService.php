<?php
class CourtService
{
    /** Court and venue a customer can book now, or null (venue not approved, or venue or court switched off). */
    public function bookableCourt(int $courtId): ?array
    {
        $court = (new CourtModel())->findWithVenue($courtId);
        if ($court === null || $court['venue_status'] !== 'approved'
            || (int) $court['venue_is_active'] !== 1 || (int) $court['is_active'] !== 1) {
            return null;
        }
        return $court;
    }

    /** Active courts of a venue: [['id' => 1, 'name' => 'Futsal Court A'], ...]. */
    public function venueCourts(int $venueId): array
    {
        return array_map(
            fn (array $c) => ['id' => (int) $c['id'], 'name' => $c['name']],
            (new CourtModel())->activeForVenue($venueId)
        );
    }

    /** First lock in the agreed order (court, session, booking or registration, payment). Caller owns the transaction. */
    public function lockCourt(int $courtId): void
    {
        if (!(new CourtModel())->lockForUpdate($courtId)) {
            throw new RuntimeException("Court {$courtId} not found.");
        }
    }

    /** ['open' => 'HH:MM', 'close' => 'HH:MM'] for the date's weekday, or null when closed. */
    public function hoursOn(int $courtId, string $date): ?array
    {
        $day = (int) (new DateTimeImmutable($date))->format('N');
        $row = (new CourtHoursModel())->forDay($courtId, $day);
        if ($row === null) {
            return null;
        }
        return ['open' => substr($row['open_time'], 0, 5), 'close' => substr($row['close_time'], 0, 5)];
    }

    /** One-hour slot start times for the date, e.g. ['06:00', '07:00', ...]; the close time is not a start. */
    public function slotStarts(int $courtId, string $date): array
    {
        $hours = $this->hoursOn($courtId, $date);
        if ($hours === null) {
            return [];
        }
        $starts = [];
        for ($h = (int) substr($hours['open'], 0, 2); $h < (int) substr($hours['close'], 0, 2); $h++) {
            $starts[] = sprintf('%02d:00', $h);
        }
        return $starts;
    }

    /** Owner and coaching blocks on the date, keyed by start time: ['18:00' => ['id' => 3, 'type' => 'owner']]. */
    public function blockedStarts(int $courtId, string $date): array
    {
        $blocks = [];
        foreach ((new CourtBlockModel())->onDate($courtId, $date) as $block) {
            $blocks[substr($block['start_time'], 0, 5)] = ['id' => (int) $block['id'], 'type' => $block['block_type']];
        }
        return $blocks;
    }

    public function isBlocked(int $courtId, string $date, string $time): bool
    {
        return (new CourtBlockModel())->existsAt($courtId, $date, $time);
    }

    /** The owner's approved venues by name, the only ones courts can be added to: [['id' => 1, 'name' => '...'], ...]. */
    public function ownerCourtVenues(int $ownerId): array
    {
        $venues = array_filter((new VenueModel())->forOwner($ownerId), fn (array $v) => $v['status'] === 'approved');
        usort($venues, fn (array $a, array $b) => strcasecmp($a['name'], $b['name']));
        return array_map(fn (array $v) => ['id' => (int) $v['id'], 'name' => $v['name']], $venues);
    }

    /**
     * Adds a court with its weekly hours to one of the owner's approved venues and returns the court id.
     * $hours: [day_of_week => ['open' => 'HH:00', 'close' => 'HH:00']]; a missing day is closed.
     */
    public function addCourt(int $ownerId, int $venueId, string $name, int $sportTypeId, float $hourlyRate, array $hours): int
    {
        $hours = self::validHours($hours);

        try {
            return Database::transaction(function () use ($ownerId, $venueId, $name, $sportTypeId, $hourlyRate, $hours): int {
                $venue = (new VenueModel())->lockForUpdate($venueId);
                if ($venue === null || (int) $venue['owner_id'] !== $ownerId) {
                    throw new ValidationException(['venue_id' => 'Choose one of your venues.']);
                }
                if ($venue['status'] !== 'approved') {
                    throw new ValidationException(['venue_id' => 'Courts can be added once the venue is approved.']);
                }
                $venueSports = array_column((new VenueSportModel())->forVenues([$venueId])[$venueId], 'id');
                if (!in_array($sportTypeId, $venueSports, true)) {
                    throw new ValidationException(['sport_type_id' => 'Choose one of the sports this venue offers.']);
                }

                $id = (new CourtModel())->create($venueId, $name, $sportTypeId, $hourlyRate);
                (new CourtHoursModel())->replaceForCourt($id, $hours);
                (new AuditService())->log($ownerId, 'court.created', 'court', $id, null, null, json_encode([
                    'venue_id' => $venueId, 'sport_type_id' => $sportTypeId, 'hourly_rate' => $hourlyRate, 'hours' => $hours,
                ]));
                return $id;
            });
        } catch (mysqli_sql_exception $e) {
            // 1062 = the venue already has a court with this name
            if ($e->getCode() === 1062) {
                throw new ValidationException(['name' => 'This venue already has a court with this name.']);
            }
            throw $e;
        }
    }

    /** One of the owner's courts with its weekly hours, or null when it is not theirs. */
    public function ownerCourt(int $ownerId, int $courtId): ?array
    {
        $court = (new CourtModel())->findWithVenue($courtId);
        if ($court === null || (int) $court['owner_id'] !== $ownerId) {
            return null;
        }
        $court['hours'] = [];
        foreach ((new CourtHoursModel())->forCourt($courtId) as $row) {
            $court['hours'][(int) $row['day_of_week']] = [
                'open'  => substr($row['open_time'], 0, 5),
                'close' => substr($row['close_time'], 0, 5),
            ];
        }
        return $court;
    }

    /** Replaces the court's weekly hours. Existing bookings are kept even if they fall outside the new hours. */
    public function updateHours(int $ownerId, int $courtId, array $hours): void
    {
        $hours = self::validHours($hours);

        Database::transaction(function () use ($ownerId, $courtId, $hours): void {
            $court = (new CourtModel())->findWithVenue($courtId);
            if ($court === null || (int) $court['owner_id'] !== $ownerId) {
                throw new ValidationException(['court' => 'Court not found.']);
            }
            $this->lockCourt($courtId);
            (new CourtHoursModel())->replaceForCourt($courtId, $hours);
            (new AuditService())->log($ownerId, 'court.hours_updated', 'court', $courtId, null, null, json_encode(['hours' => $hours]));
        });
    }

    /** Whole hours, close after open, close at most 24:00, at least one open day. Returns the hours sorted by day. */
    private static function validHours(array $hours): array
    {
        $valid = [];
        foreach ($hours as $day => $h) {
            $open = is_array($h) ? (string) ($h['open'] ?? '') : '';
            $close = is_array($h) ? (string) ($h['close'] ?? '') : '';
            if (!is_int($day) || $day < 1 || $day > 7
                || !preg_match('/^([01]\d|2[0-3]):00$/', $open)
                || !preg_match('/^(([01]\d|2[0-3]):00|24:00)$/', $close)) {
                throw new ValidationException(['hours' => 'Use whole hours such as 06:00 and 22:00.']);
            }
            if ($close <= $open) {
                throw new ValidationException(['hours' => 'The closing time must be after the opening time on every open day.']);
            }
            $valid[$day] = ['open' => $open, 'close' => $close];
        }
        if ($valid === []) {
            throw new ValidationException(['hours' => 'Open the court on at least one day.']);
        }
        ksort($valid);
        return $valid;
    }
}
