<?php
class PaymentModel extends Model
{
    /** The paid payment behind a booking (booking or cash-to-online conversion), locked for the caller's transaction. */
    public function paidForBookingForUpdate(int $bookingId): ?array
    {
        return $this->selectOne(
            "SELECT id, purpose, amount FROM payments
             WHERE booking_id = ? AND status = 'paid'
             ORDER BY id DESC LIMIT 1 FOR UPDATE",
            'i',
            [$bookingId]
        );
    }
}
