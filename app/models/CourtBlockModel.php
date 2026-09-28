<?php
class CourtBlockModel extends Model
{
    public function onDate(int $courtId, string $date): array
    {
        return $this->select(
            'SELECT id, start_time, block_type FROM court_blocks WHERE court_id = ? AND block_date = ? ORDER BY start_time',
            'is',
            [$courtId, $date]
        );
    }

    /** Blocks on one date with who or what holds them: the coach and session for a coaching block, the reason for an owner block. */
    public function labelsOn(int $courtId, string $date): array
    {
        return $this->select(
            "SELECT b.start_time, b.block_type, b.reason, s.title AS session_title, u.name AS coach_name
             FROM court_blocks b
             LEFT JOIN coach_sessions s ON s.block_id = b.id
             LEFT JOIN users u ON u.id = s.coach_id
             WHERE b.court_id = ? AND b.block_date = ?",
            'is',
            [$courtId, $date]
        );
    }

    public function existsAt(int $courtId, string $date, string $time): bool
    {
        return $this->selectOne(
            'SELECT 1 FROM court_blocks WHERE court_id = ? AND block_date = ? AND start_time = ?',
            'iss',
            [$courtId, $date, $time]
        ) !== null;
    }

    public function create(int $courtId, string $date, string $time, string $type, ?string $reason, int $createdBy): int
    {
        return $this->insert(
            'INSERT INTO court_blocks (court_id, block_date, start_time, block_type, reason, created_by) VALUES (?, ?, ?, ?, ?, ?)',
            'issssi',
            [$courtId, $date, $time, $type, $reason, $createdBy]
        );
    }

    public function delete(int $id): int
    {
        return $this->execute('DELETE FROM court_blocks WHERE id = ?', 'i', [$id]);
    }
}
