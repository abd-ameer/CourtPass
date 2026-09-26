<?php
class VenueService
{
    private VenueModel $venues;
    private VenueSportModel $venueSports;

    public function __construct()
    {
        $this->venues = new VenueModel();
        $this->venueSports = new VenueSportModel();
    }

    /** Sport types for venue and coach forms. Other modules read them through here. */
    public function sportTypes(): array
    {
        return array_map(
            fn (array $s) => ['id' => (int) $s['id'], 'code' => $s['code'], 'name' => $s['name']],
            (new SportTypeModel())->all()
        );
    }

    /**
     * Registers a venue in the pending state. $data: name, city, address, contact_phone, description, sport_type_ids.
     * Returns the new venue id.
     */
    public function create(int $ownerId, array $data): int
    {
        $sportIds = $this->validSportIds($data['sport_type_ids'] ?? []);

        try {
            return Database::transaction(function () use ($ownerId, $data, $sportIds): int {
                $id = $this->venues->create(
                    $ownerId, $data['name'], $this->freeSlug($data['name']), $data['description'] ?? null,
                    $data['address'], $data['city'], $data['contact_phone']
                );
                $this->venueSports->addAll($id, $sportIds);
                (new AuditService())->log($ownerId, 'venue.created', 'venue', $id, null, 'pending',
                    json_encode(['name' => $data['name'], 'sport_type_ids' => $sportIds]));
                return $id;
            });
        } catch (mysqli_sql_exception $e) {
            // 1062 = another venue took the same slug at the same moment
            if ($e->getCode() === 1062) {
                throw new ValidationException(['name' => 'Could not save the venue. Please submit it again.']);
            }
            throw $e;
        }
    }

    /** The owner's venues, newest first. */
    public function ownerVenues(int $ownerId): array
    {
        return $this->withSports(array_map(fn (array $v) => $this->present($v), $this->venues->forOwner($ownerId)));
    }

    /** One of the owner's venues with its sports and courts, or null when it is not theirs. */
    public function ownerVenue(int $ownerId, int $venueId): ?array
    {
        $venue = $this->venues->find($venueId);
        if ($venue === null || (int) $venue['owner_id'] !== $ownerId) {
            return null;
        }
        return $this->detailed($venue);
    }

    /**
     * Updates details and sports. A rejected venue goes back to pending (resubmitted); other statuses are kept.
     * Returns true when the venue was resubmitted. The audit entry lists the removed and added sports.
     */
    public function update(int $ownerId, int $venueId, array $data): bool
    {
        $sportIds = $this->validSportIds($data['sport_type_ids'] ?? []);

        return Database::transaction(function () use ($ownerId, $venueId, $data, $sportIds): bool {
            $venue = $this->lockOwned($ownerId, $venueId);
            if ($venue['status'] === 'deactivated') {
                throw new ValidationException(['venue' => 'This venue was deactivated by the platform and cannot be edited.']);
            }

            $current = array_column($this->venueSports->forVenues([$venueId])[$venueId], 'id');
            $removed = array_values(array_diff($current, $sportIds));
            $inUse = array_values(array_intersect($removed, (new CourtModel())->sportIdsForVenue($venueId)));
            if ($inUse !== []) {
                $names = array_column(array_filter($this->sportTypes(), fn (array $s) => in_array($s['id'], $inUse, true)), 'name');
                throw new ValidationException(['sport_type_ids' => implode(', ', $names) . ' cannot be removed because a court uses it.']);
            }

            $this->venues->updateDetails($venueId, $data['name'], $data['description'] ?? null, $data['address'], $data['city'], $data['contact_phone']);
            $added = array_values(array_diff($sportIds, $current));
            $this->venueSports->removeAll($venueId, $removed);
            $this->venueSports->addAll($venueId, $added);

            $resubmitted = $venue['status'] === 'rejected' && $this->venues->resubmit($venueId) === 1;
            (new AuditService())->log($ownerId, 'venue.updated', 'venue', $venueId, $venue['status'],
                $resubmitted ? 'pending' : $venue['status'],
                json_encode([
                    'resubmitted'            => $resubmitted,
                    'sport_type_ids'         => $sportIds,
                    'removed_sport_type_ids' => array_map('intval', $removed),
                    'added_sport_type_ids'   => array_map('intval', $added),
                ]));
            return $resubmitted;
        });
    }

    /** Owner switches an approved venue off. Existing bookings and sessions are kept; new ones are refused. */
    public function deactivate(int $ownerId, int $venueId): void
    {
        $this->switchActive($ownerId, $venueId, false);
    }

    public function activate(int $ownerId, int $venueId): void
    {
        $this->switchActive($ownerId, $venueId, true);
    }

    /** Venues waiting for admin approval, oldest first. */
    public function pendingVenues(): array
    {
        return $this->withSports(array_map(fn (array $v) => $this->present($v), $this->venues->pending()));
    }

    /** Any venue with owner details, sports and courts, for the admin. */
    public function adminVenue(int $venueId): ?array
    {
        $venue = $this->venues->find($venueId);
        return $venue === null ? null : $this->detailed($venue);
    }

    public function approve(int $adminId, int $venueId): void
    {
        $this->decide($adminId, $venueId, 'approved', null);
    }

    public function reject(int $adminId, int $venueId, string $reason): void
    {
        $this->decide($adminId, $venueId, 'rejected', $reason);
    }

    /** Approved, switched-on venues for discovery, each with its sports, active court count and lowest rate. */
    public function publicVenues(?int $sportTypeId = null, ?string $city = null, ?string $search = null): array
    {
        $rows = $this->venues->publicList($sportTypeId, self::blankToNull($city), self::blankToNull($search));
        $venues = array_map(function (array $v): array {
            $v['id'] = (int) $v['id'];
            $v['court_count'] = (int) $v['court_count'];
            $v['min_rate'] = $v['min_rate'] === null ? null : (float) $v['min_rate'];
            return $v;
        }, $rows);
        return $this->withSports($venues);
    }

    /** An approved, switched-on venue by slug with its sports and active courts, or null. */
    public function publicVenueBySlug(string $slug): ?array
    {
        $venue = $this->venues->findPublicBySlug($slug);
        if ($venue === null) {
            return null;
        }
        $venue['id'] = (int) $venue['id'];
        $venue['sports'] = $this->venueSports->forVenues([$venue['id']])[$venue['id']];
        $venue['courts'] = array_values(array_filter($this->courts($venue['id']), fn (array $c) => $c['is_active']));
        return $venue;
    }

    private function decide(int $adminId, int $venueId, string $status, ?string $reason): void
    {
        $venue = Database::transaction(function () use ($adminId, $venueId, $status, $reason): array {
            if ($this->venues->lockForUpdate($venueId) === null) {
                throw new ValidationException(['venue' => 'Venue not found.']);
            }
            $now = now();
            $changed = $status === 'approved'
                ? $this->venues->approve($venueId, $adminId, $now)
                : $this->venues->reject($venueId, $adminId, (string) $reason, $now);
            if ($changed !== 1) {
                throw new ValidationException(['venue' => 'Only a pending venue can be approved or rejected.']);
            }
            (new AuditService())->log($adminId, 'venue.' . $status, 'venue', $venueId, 'pending', $status,
                $reason === null ? null : json_encode(['reason' => $reason]));
            return $this->venues->find($venueId);
        });

        $id = (int) $venue['id'];
        $message = $status === 'approved'
            ? "{$venue['name']} is approved. Add its courts and operating hours to start taking bookings."
            : "{$venue['name']} was not approved. Reason: {$reason} Edit the venue to submit it again.";
        (new NotificationService())->notify((int) $venue['owner_id'], 'venue_' . $status,
            $status === 'approved' ? 'Venue approved' : 'Venue not approved', $message, "/owner/venues/{$id}");
    }

    private function switchActive(int $ownerId, int $venueId, bool $active): void
    {
        Database::transaction(function () use ($ownerId, $venueId, $active): void {
            $venue = $this->lockOwned($ownerId, $venueId);
            if ($venue['status'] !== 'approved') {
                throw new ValidationException(['venue' => 'Only an approved venue can be switched on or off.']);
            }
            if ($this->venues->setActive($venueId, $active) !== 1) {
                throw new ValidationException(['venue' => $active ? 'This venue is already active.' : 'This venue is already deactivated.']);
            }
            (new AuditService())->log($ownerId, $active ? 'venue.activated' : 'venue.deactivated', 'venue', $venueId,
                null, null, json_encode(['is_active' => $active ? 1 : 0]));
        });
    }

    /** Locks the venue row and checks it belongs to the owner. Runs inside a transaction. */
    private function lockOwned(int $ownerId, int $venueId): array
    {
        $venue = $this->venues->lockForUpdate($venueId);
        if ($venue === null || (int) $venue['owner_id'] !== $ownerId) {
            throw new ValidationException(['venue' => 'Venue not found.']);
        }
        return $venue;
    }

    /** At least one sport, every id a known sport type. Returns the ids as sorted unique ints. */
    private function validSportIds(mixed $ids): array
    {
        $ids = is_array($ids) ? array_values(array_unique(array_map('intval', $ids))) : [];
        $known = array_column($this->sportTypes(), 'id');
        if ($ids === [] || array_diff($ids, $known) !== []) {
            throw new ValidationException(['sport_type_ids' => 'Choose at least one sport type.']);
        }
        sort($ids);
        return $ids;
    }

    /** URL slug from the venue name, with -2, -3 ... when taken. Fixed once the venue is created. */
    private function freeSlug(string $name): string
    {
        $base = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($name)), '-');
        $base = rtrim(substr($base, 0, 150), '-');
        if ($base === '') {
            $base = 'venue';
        }
        $taken = $this->venues->slugsLike($base);
        $slug = $base;
        for ($n = 2; in_array($slug, $taken, true); $n++) {
            $slug = "{$base}-{$n}";
        }
        return $slug;
    }

    private function detailed(array $venue): array
    {
        $venue = $this->present($venue);
        $venue['sports'] = $this->venueSports->forVenues([$venue['id']])[$venue['id']];
        $venue['courts'] = $this->courts($venue['id']);
        return $venue;
    }

    private function courts(int $venueId): array
    {
        return array_map(fn (array $c) => [
            'id'            => (int) $c['id'],
            'name'          => $c['name'],
            'sport_type_id' => (int) $c['sport_type_id'],
            'sport_name'    => $c['sport_name'],
            'hourly_rate'   => (float) $c['hourly_rate'],
            'is_active'     => (int) $c['is_active'] === 1,
        ], (new CourtModel())->forVenue($venueId));
    }

    private function withSports(array $venues): array
    {
        $sports = $this->venueSports->forVenues(array_column($venues, 'id'));
        foreach ($venues as $i => $venue) {
            $venues[$i]['sports'] = $sports[$venue['id']] ?? [];
        }
        return $venues;
    }

    /** Casts ids and adds the flags the views use. */
    private function present(array $v): array
    {
        foreach (['id', 'owner_id', 'court_count'] as $key) {
            $v[$key] = (int) $v[$key];
        }
        $approved = $v['status'] === 'approved';
        $v['is_active'] = (int) $v['is_active'] === 1;
        $v['listed'] = $approved && $v['is_active'];
        $v['public_path'] = $approved ? '/venue/' . $v['slug'] : null;
        $v['can_edit'] = $v['status'] !== 'deactivated';
        $v['can_add_court'] = $approved;
        $v['can_deactivate'] = $approved && $v['is_active'];
        $v['can_activate'] = $approved && !$v['is_active'];
        $v['can_decide'] = $v['status'] === 'pending';
        return $v;
    }

    private static function blankToNull(?string $value): ?string
    {
        $value = $value === null ? null : trim($value);
        return $value === '' ? null : $value;
    }
}
