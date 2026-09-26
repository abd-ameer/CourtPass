<?php
/**
 * Coach sessions table. Read queries join courts, venues, sport types, users, coach profiles,
 * registrations and reviews for labels and counts only.
 */
class CoachSessionModel extends Model
{
    private const DETAIL_SELECT = "
        SELECT s.id, s.coach_id, s.court_id, s.block_id, s.session_date, s.start_time, s.title, s.description,
               s.capacity, s.fee, s.visibility, s.access_token, s.status, s.cancelled_by, s.cancel_reason,
               s.cancelled_at, s.created_at,
               c.name AS court_name, v.id AS venue_id, v.name AS venue_name, v.address AS venue_address,
               v.city AS venue_city, st.code AS sport_code, st.name AS sport_name,
               u.name AS coach_name, cp.is_verified AS coach_verified,
               (SELECT COUNT(*) FROM session_registrations r
                WHERE r.session_id = s.id AND r.status IN ('registered', 'pending_payment')) AS live_count,
               (SELECT COUNT(*) FROM session_registrations r
                WHERE r.session_id = s.id AND r.status <> 'cancelled') AS registration_count,
               (SELECT AVG(rv.rating) FROM reviews rv
                WHERE rv.coach_id = s.coach_id AND rv.status = 'active') AS coach_rating
        FROM coach_sessions s
        JOIN courts c ON c.id = s.court_id
        JOIN venues v ON v.id = c.venue_id
        JOIN sport_types st ON st.id = c.sport_type_id
        JOIN users u ON u.id = s.coach_id
        JOIN coach_profiles cp ON cp.coach_id = s.coach_id";

    public function create(int $coachId, int $courtId, int $blockId, string $date, string $time, string $title, string $description,
        int $capacity, float $fee, string $visibility, ?string $token): int
    {
        return $this->insert(
            'INSERT INTO coach_sessions (coach_id, court_id, block_id, session_date, start_time, title, description,
                 capacity, fee, visibility, access_token)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            'iiissssidss',
            [$coachId, $courtId, $blockId, $date, $time, $title, $description, $capacity, $fee, $visibility, $token]
        );
    }

    public function find(int $id): ?array
    {
        return $this->selectOne(self::DETAIL_SELECT . ' WHERE s.id = ?', 'i', [$id]);
    }

    /** Every session of the coach, latest slot first. */
    public function forCoach(int $coachId): array
    {
        return $this->select(
            self::DETAIL_SELECT . ' WHERE s.coach_id = ? ORDER BY s.session_date DESC, s.start_time DESC, s.id DESC',
            'i',
            [$coachId]
        );
    }

    /** Public open or full sessions starting after $now, soonest first, optionally filtered. */
    public function publicUpcoming(string $now, ?string $sportCode, ?int $venueId, ?string $date): array
    {
        $sql = self::DETAIL_SELECT . " WHERE s.visibility = 'public' AND s.status IN ('open', 'full')
                AND TIMESTAMP(s.session_date, s.start_time) > ?";
        $types = 's';
        $params = [$now];
        if ($sportCode !== null) {
            $sql .= ' AND st.code = ?';
            $types .= 's';
            $params[] = $sportCode;
        }
        if ($venueId !== null) {
            $sql .= ' AND v.id = ?';
            $types .= 'i';
            $params[] = $venueId;
        }
        if ($date !== null) {
            $sql .= ' AND s.session_date = ?';
            $types .= 's';
            $params[] = $date;
        }
        return $this->select($sql . ' ORDER BY s.session_date, s.start_time, s.id', $types, $params);
    }

    public function findByToken(string $token): ?array
    {
        return $this->selectOne(self::DETAIL_SELECT . " WHERE s.access_token = ? AND s.visibility = 'private'", 's', [$token]);
    }

    /** Must run inside a transaction. */
    public function lockForUpdate(int $id): ?array
    {
        return $this->selectOne(
            'SELECT id, coach_id, court_id, block_id, session_date, start_time, capacity, fee, status
             FROM coach_sessions WHERE id = ? FOR UPDATE',
            'i',
            [$id]
        );
    }

    public function updateDetails(int $id, string $title, string $description, int $capacity, float $fee): int
    {
        return $this->execute(
            "UPDATE coach_sessions SET title = ?, description = ?, capacity = ?, fee = ?
             WHERE id = ? AND status IN ('open', 'full')",
            'ssidi',
            [$title, $description, $capacity, $fee, $id]
        );
    }

    public function setOpenOrFull(int $id, string $status): int
    {
        return $this->execute(
            "UPDATE coach_sessions SET status = ? WHERE id = ? AND status IN ('open', 'full')",
            'si',
            [$status, $id]
        );
    }

    /** Cancels and unlinks the court block in one statement, as the table's CHECK constraints require. */
    public function cancel(int $id, int $cancelledBy, string $reason, string $now): int
    {
        return $this->execute(
            "UPDATE coach_sessions
             SET status = 'cancelled', cancel_reason = ?, cancelled_by = ?, cancelled_at = ?, block_id = NULL
             WHERE id = ? AND status IN ('open', 'full')",
            'sisi',
            [$reason, $cancelledBy, $now, $id]
        );
    }

    /** Open or full sessions whose start time has passed. */
    public function pastOpen(string $now): array
    {
        return $this->select(
            "SELECT id, status FROM coach_sessions
             WHERE status IN ('open', 'full') AND TIMESTAMP(session_date, start_time) <= ?
             ORDER BY id",
            's',
            [$now]
        );
    }

    public function complete(int $id, string $now): int
    {
        return $this->execute(
            "UPDATE coach_sessions SET status = 'completed'
             WHERE id = ? AND status IN ('open', 'full') AND TIMESTAMP(session_date, start_time) <= ?",
            'is',
            [$id, $now]
        );
    }
}
