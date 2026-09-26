<?php
class CheckInModel extends Model
{
    public function create(int $bookingId, int $ownerId, string $checkedInAt): void
    {
        $this->execute(
            'INSERT INTO check_ins (booking_id, checked_in_by, checked_in_at) VALUES (?, ?, ?)',
            'iis',
            [$bookingId, $ownerId, $checkedInAt]
        );
    }

    /** Check-in times keyed by booking id, for the bookings that have one. */
    public function forBookings(array $bookingIds): array
    {
        $bookingIds = array_values(array_unique(array_map('intval', $bookingIds)));
        if ($bookingIds === []) {
            return [];
        }
        $rows = $this->select(
            'SELECT booking_id, checked_in_at FROM check_ins
             WHERE booking_id IN (' . implode(',', array_fill(0, count($bookingIds), '?')) . ')',
            str_repeat('i', count($bookingIds)),
            $bookingIds
        );
        return array_column($rows, 'checked_in_at', 'booking_id');
    }
}
