<?php
class RefundService
{
    public const REASONS = [
        'customer_cancel', 'owner_cancel', 'owner_reject', 'resale', 'session_cancelled',
        'registration_cancel', 'venue_deactivated', 'coach_deactivated', 'late_payment',
    ];

    /**
     * Records a simulated refund of a booking's paid payment. Runs inside the caller's transaction.
     * Returns null when there is nothing to refund: 0%, no paid payment (cash), or already refunded.
     */
    public function refundBooking(int $bookingId, float $percentage, string $reason, ?int $actorId): ?array
    {
        return $this->refund(fn () => (new PaymentModel())->paidForBookingForUpdate($bookingId), $percentage, $reason, $actorId);
    }

    /**
     * Records a simulated refund of a session registration's paid payment. Runs inside the caller's transaction.
     * Returns null when there is nothing to refund: 0%, no paid payment, or already refunded.
     */
    public function refundRegistration(int $registrationId, float $percentage, string $reason, ?int $actorId): ?array
    {
        return $this->refund(fn () => (new PaymentModel())->paidForRegistrationForUpdate($registrationId), $percentage, $reason, $actorId);
    }

    private function refund(callable $findPayment, float $percentage, string $reason, ?int $actorId): ?array
    {
        if (!in_array($reason, self::REASONS, true)) {
            throw new InvalidArgumentException("Unknown refund reason {$reason}.");
        }
        if ($percentage <= 0) {
            return null;
        }
        if ($percentage > 100) {
            throw new InvalidArgumentException('Refund percentage cannot be above 100.');
        }

        $payment = $findPayment();
        $refunds = new RefundModel();
        if ($payment === null || $refunds->existsForPayment((int) $payment['id'])) {
            return null;
        }

        $amount = round((float) $payment['amount'] * $percentage / 100, 2);
        if ($amount <= 0) {
            return null;
        }
        $id = $refunds->create((int) $payment['id'], $reason, $percentage, $amount, 0.0, $actorId);

        return ['id' => $id, 'payment_id' => (int) $payment['id'], 'percentage' => $percentage, 'amount' => $amount];
    }
}
