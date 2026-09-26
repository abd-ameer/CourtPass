<?php
class ReviewModel extends Model
{
    /** One review with its labels and its open flag, if any. Joins are read-only. */
    private const DETAIL_SELECT = "
        SELECT r.id, r.reviewer_id, r.venue_id, r.booking_id, r.coach_id, r.registration_id, r.rating, r.comment,
               r.response_text, r.responded_by, r.responded_at, r.status, r.removed_by, r.removed_reason, r.created_at,
               u.name AS reviewer_name, v.name AS venue_name, v.slug AS venue_slug, v.owner_id,
               c.name AS court_name, b.slot_date, b.start_time, ci.checked_in_at, cu.name AS coach_name,
               f.id AS flag_id, f.reason AS flag_reason, f.created_at AS flagged_at, f.flagged_by,
               fu.name AS flagged_by_name
        FROM reviews r
        JOIN users u ON u.id = r.reviewer_id
        LEFT JOIN venues v ON v.id = r.venue_id
        LEFT JOIN bookings b ON b.id = r.booking_id
        LEFT JOIN courts c ON c.id = b.court_id
        LEFT JOIN check_ins ci ON ci.booking_id = r.booking_id
        LEFT JOIN users cu ON cu.id = r.coach_id
        LEFT JOIN review_flags f ON f.review_id = r.id AND f.status = 'open'
        LEFT JOIN users fu ON fu.id = f.flagged_by";

    public function create(int $reviewerId, int $venueId, int $bookingId, int $rating, string $comment, string $createdAt): int
    {
        return $this->insert(
            "INSERT INTO reviews (reviewer_id, venue_id, booking_id, rating, comment, status, created_at)
             VALUES (?, ?, ?, ?, ?, 'active', ?)",
            'iiiiss',
            [$reviewerId, $venueId, $bookingId, $rating, $comment, $createdAt]
        );
    }

    public function find(int $id): ?array
    {
        return $this->selectOne(self::DETAIL_SELECT . ' WHERE r.id = ?', 'i', [$id]);
    }

    /** Must run inside a transaction; the row stays locked until commit or rollback. */
    public function lockForUpdate(int $id): ?array
    {
        return $this->selectOne(
            'SELECT id, reviewer_id, venue_id, booking_id, rating, comment, response_text, status
             FROM reviews WHERE id = ? FOR UPDATE',
            'i',
            [$id]
        );
    }

    public function existsForBooking(int $bookingId): bool
    {
        return $this->selectOne('SELECT 1 FROM reviews WHERE booking_id = ?', 'i', [$bookingId]) !== null;
    }

    /** The customer's venue and coach reviews, newest first. */
    public function forReviewer(int $reviewerId): array
    {
        return $this->select(self::DETAIL_SELECT . ' WHERE r.reviewer_id = ? ORDER BY r.created_at DESC, r.id DESC', 'i', [$reviewerId]);
    }

    public function activeForVenue(int $venueId): array
    {
        return $this->select(
            self::DETAIL_SELECT . " WHERE r.venue_id = ? AND r.status = 'active' ORDER BY r.created_at DESC, r.id DESC",
            'i',
            [$venueId]
        );
    }

    /** Venue reviews at every venue the owner runs, optionally one venue, newest first. */
    public function forOwner(int $ownerId, ?int $venueId): array
    {
        $sql = self::DETAIL_SELECT . ' WHERE v.owner_id = ?';
        $types = 'i';
        $params = [$ownerId];
        if ($venueId !== null) {
            $sql .= ' AND r.venue_id = ?';
            $types .= 'i';
            $params[] = $venueId;
        }
        return $this->select($sql . ' ORDER BY r.created_at DESC, r.id DESC', $types, $params);
    }

    /** Venue reviews for the admin: flagged (active with an open flag), active or removed. */
    public function forModeration(string $status): array
    {
        $where = match ($status) {
            'flagged' => "r.status = 'active' AND f.id IS NOT NULL",
            'removed' => "r.status = 'removed'",
            default   => "r.status = 'active'",
        };
        $order = $status === 'flagged' ? 'f.created_at, f.id' : 'r.created_at DESC, r.id DESC';
        return $this->select(self::DETAIL_SELECT . " WHERE r.venue_id IS NOT NULL AND {$where} ORDER BY {$order}");
    }

    /** Average rating and count of active reviews, keyed by venue id. */
    public function ratingsFor(array $venueIds): array
    {
        $venueIds = array_values(array_unique(array_map('intval', $venueIds)));
        if ($venueIds === []) {
            return [];
        }
        $rows = $this->select(
            "SELECT venue_id, AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM reviews
             WHERE status = 'active' AND venue_id IN (" . implode(',', array_fill(0, count($venueIds), '?')) . ')
             GROUP BY venue_id',
            str_repeat('i', count($venueIds)),
            $venueIds
        );
        $ratings = [];
        foreach ($rows as $row) {
            $ratings[(int) $row['venue_id']] = ['avg_rating' => round((float) $row['avg_rating'], 1), 'review_count' => (int) $row['review_count']];
        }
        return $ratings;
    }

    public function updateContent(int $id, int $rating, string $comment): int
    {
        return $this->execute(
            "UPDATE reviews SET rating = ?, comment = ? WHERE id = ? AND status = 'active'",
            'isi',
            [$rating, $comment, $id]
        );
    }

    public function delete(int $id): int
    {
        return $this->execute("DELETE FROM reviews WHERE id = ? AND status = 'active'", 'i', [$id]);
    }

    public function remove(int $id, int $adminId, string $reason): int
    {
        return $this->execute(
            "UPDATE reviews SET status = 'removed', removed_by = ?, removed_reason = ? WHERE id = ? AND status = 'active'",
            'isi',
            [$adminId, $reason, $id]
        );
    }

    public function respond(int $id, int $ownerId, string $response, string $respondedAt): int
    {
        return $this->execute(
            "UPDATE reviews SET response_text = ?, responded_by = ?, responded_at = ?
             WHERE id = ? AND status = 'active' AND response_text IS NULL",
            'sisi',
            [$response, $ownerId, $respondedAt, $id]
        );
    }
}
