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

    public function sportIdsFor(int $coachId): array
    {
        return array_map('intval', array_column(
            $this->select('SELECT sport_type_id FROM coach_sports WHERE coach_id = ?', 'i', [$coachId]),
            'sport_type_id'
        ));
    }
}
