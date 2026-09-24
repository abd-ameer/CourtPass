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
}
