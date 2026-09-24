<?php
class CoachSportModel extends Model
{
    public function addAll(int $coachId, array $sportTypeIds): void
    {
        foreach ($sportTypeIds as $sportTypeId) {
            $this->insert(
                'INSERT INTO coach_sports (coach_id, sport_type_id) VALUES (?, ?)',
                'ii',
                [$coachId, (int) $sportTypeId]
            );
        }
    }
}
