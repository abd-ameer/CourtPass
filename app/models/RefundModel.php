<?php
class RefundModel extends Model
{
    public function existsForPayment(int $paymentId): bool
    {
        return $this->selectOne('SELECT 1 FROM refunds WHERE payment_id = ?', 'i', [$paymentId]) !== null;
    }

    public function create(int $paymentId, string $reason, float $percentage, float $amount, float $feeAmount, ?int $createdBy): int
    {
        return $this->insert(
            'INSERT INTO refunds (payment_id, reason, percentage, amount, fee_amount, created_by) VALUES (?, ?, ?, ?, ?, ?)',
            'isdddi',
            [$paymentId, $reason, $percentage, $amount, $feeAmount, $createdBy]
        );
    }
}
