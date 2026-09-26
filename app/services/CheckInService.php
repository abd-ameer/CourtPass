<?php
class CheckInService
{
    private const SEARCH_MAX = 100;

    /**
     * Marks the customer of a confirmed booking at one of the owner's venues as arrived.
     * The booking engine completes the booking first (court and booking locks), then the check-in row is added.
     * Returns the completed booking with checked_in_at.
     */
    public function checkIn(int $ownerId, int $bookingId): array
    {
        try {
            return Database::transaction(function () use ($ownerId, $bookingId): array {
                $booking = (new BookingService())->completeByCheckIn($ownerId, $bookingId);
                $at = now();
                (new CheckInModel())->create($booking['id'], $ownerId, $at);
                (new AuditService())->log($ownerId, 'check_in.recorded', 'check_in', $booking['id'], null, null,
                    json_encode(['checked_in_at' => $at, 'venue_id' => $booking['venue_id']]));
                $booking['checked_in_at'] = $at;
                return $booking;
            });
        } catch (mysqli_sql_exception $e) {
            // 1062 = the check-in row already exists
            if ($e->getCode() === 1062) {
                throw new ValidationException(['booking' => "Booking #{$bookingId} has already been checked in."]);
            }
            throw $e;
        }
    }

    /**
     * Today's front desk for the owner: confirmed bookings waiting, bookings already checked in,
     * and confirmed bookings whose slot ended without a check-in. $search is a booking id (with or
     * without #), which finds that booking on any date, or part of a customer name for today's list.
     */
    public function desk(int $ownerId, string $search = ''): array
    {
        $search = mb_substr(trim($search), 0, self::SEARCH_MAX);
        $today = date('Y-m-d');
        $bookings = new BookingService();

        $rows = array_values(array_filter(
            $bookings->ownerBookings($ownerId),
            fn (array $b) => $b['slot_date'] === $today && in_array($b['status'], ['confirmed', 'completed'], true)
        ));
        $rows = $this->withDeskState($rows);
        $rows = array_values(array_filter($rows, fn (array $b) => $b['desk_state'] !== null));
        usort($rows, fn (array $a, array $b) => [$a['start_time'], $a['id']] <=> [$b['start_time'], $b['id']]);

        $counts = ['waiting' => 0, 'checked_in' => 0, 'missed' => 0];
        foreach ($rows as $row) {
            $counts[$row['desk_state']]++;
        }

        $found = null;
        $message = null;
        if (preg_match('/^#?(\d{1,10})$/', $search, $m)) {
            $booking = $bookings->ownerBooking($ownerId, (int) $m[1]);
            if ($booking === null) {
                $message = "No booking #{$m[1]} at your venues.";
                $rows = [];
            } else {
                $rows = $this->withDeskState([$booking]);
                $found = $rows[0];
            }
        } elseif ($search !== '') {
            $needle = mb_strtolower($search);
            $rows = array_values(array_filter($rows, fn (array $b) => str_contains(mb_strtolower($b['customer_name']), $needle)));
            if ($rows === []) {
                $message = "No booking today for a customer matching \"{$search}\".";
            }
        }

        return [
            'date'     => $today,
            'search'   => $search,
            'bookings' => $rows,
            'counts'   => $counts,
            'found'    => $found,
            'message'  => $message,
        ];
    }

    /**
     * Adds checked_in_at and desk_state: waiting (confirmed, slot not ended), checked_in, missed
     * (confirmed, slot ended) or null for any other status.
     */
    private function withDeskState(array $rows): array
    {
        $checkIns = (new CheckInModel())->forBookings(array_column($rows, 'id'));
        $now = now();
        foreach ($rows as $i => $b) {
            $ended = date('Y-m-d H:i:s', strtotime($b['starts_at'] . ' +1 hour')) <= $now;
            $rows[$i]['checked_in_at'] = $checkIns[$b['id']] ?? null;
            $rows[$i]['desk_state'] = match (true) {
                $rows[$i]['checked_in_at'] !== null => 'checked_in',
                $b['status'] === 'confirmed' && !$ended => 'waiting',
                $b['status'] === 'confirmed' => 'missed',
                default => null,
            };
        }
        return $rows;
    }
}
