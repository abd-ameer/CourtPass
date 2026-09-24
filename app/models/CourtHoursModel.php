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
}
