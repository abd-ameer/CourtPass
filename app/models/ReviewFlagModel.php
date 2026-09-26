<?php
class ReviewFlagModel extends Model
{
    public function create(int $reviewId, int $flaggedBy, string $reason): int
    {
        return $this->insert(
            'INSERT INTO review_flags (review_id, flagged_by, reason) VALUES (?, ?, ?)',
            'iis',
            [$reviewId, $flaggedBy, $reason]
        );
    }

    /** Closes the review's open flag as dismissed or upheld. Returns the number of flags closed (0 or 1). */
    public function resolveOpen(int $reviewId, string $status, int $adminId, string $resolvedAt): int
    {
        return $this->execute(
            "UPDATE review_flags SET status = ?, resolved_by = ?, resolved_at = ? WHERE review_id = ? AND status = 'open'",
            'sisi',
            [$status, $adminId, $resolvedAt, $reviewId]
        );
    }

    /** Flags of a review the customer is deleting; the audit entry keeps the count. */
    public function deleteForReview(int $reviewId): int
    {
        return $this->execute('DELETE FROM review_flags WHERE review_id = ?', 'i', [$reviewId]);
    }
}
