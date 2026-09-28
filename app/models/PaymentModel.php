<?php
class PaymentModel extends Model
{
    /** A payment already verified as paid. $gatewayRef fills payhere_payment_id (a SIM- reference for simulated payments). */
    public function createPaid(string $purpose, ?int $bookingId, ?int $registrationId, string $orderId, float $amount, string $gatewayRef, string $paidAt): int
    {
        return $this->insert(
            "INSERT INTO payments (purpose, booking_id, registration_id, order_id, amount, status, payhere_payment_id, payhere_method, paid_at)
             VALUES (?, ?, ?, ?, ?, 'paid', ?, 'SIMULATED', ?)",
            'siisdss',
            [$purpose, $bookingId, $registrationId, $orderId, $amount, $gatewayRef, $paidAt]
        );
    }

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

    /** The paid payment behind a session registration, locked for the caller's transaction. */
    public function paidForRegistrationForUpdate(int $registrationId): ?array
    {
        return $this->selectOne(
            "SELECT id, purpose, amount FROM payments
             WHERE registration_id = ? AND status = 'paid'
             ORDER BY id DESC LIMIT 1 FOR UPDATE",
            'i',
            [$registrationId]
        );
    }
}
