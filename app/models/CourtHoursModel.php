<?php
class CourtHoursModel extends Model
{
    /** day_of_week: 1 = Monday ... 7 = Sunday. */
    public function forDay(int $courtId, int $dayOfWeek): ?array
    {
        return $this->selectOne(
            'SELECT open_time, close_time FROM court_operating_hours WHERE court_id = ? AND day_of_week = ?',
            'ii',
            [$courtId, $dayOfWeek]
        );
    }

    public function forCourt(int $courtId): array
    {
        return $this->select(
            'SELECT day_of_week, open_time, close_time FROM court_operating_hours WHERE court_id = ? ORDER BY day_of_week',
            'i',
            [$courtId]
        );
    }

    /** Replaces the court's week; a day missing from $hours is closed. Runs in the caller's transaction. */
    public function replaceForCourt(int $courtId, array $hours): void
    {
        $this->execute('DELETE FROM court_operating_hours WHERE court_id = ?', 'i', [$courtId]);
        foreach ($hours as $day => $h) {
            $this->insert(
                'INSERT INTO court_operating_hours (court_id, day_of_week, open_time, close_time) VALUES (?, ?, ?, ?)',
                'iiss',
                [$courtId, (int) $day, $h['open'] . ':00', $h['close'] . ':00']
            );
        }
    }
}
