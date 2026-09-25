<?php
class VenueSportModel extends Model
{
    /** Sports of each venue: [venue_id => [['id' => 2, 'name' => 'Badminton'], ...]]. */
    public function forVenues(array $venueIds): array
    {
        $venueIds = array_values(array_unique(array_map('intval', $venueIds)));
        if ($venueIds === []) {
            return [];
        }
        $rows = $this->select(
            'SELECT vs.venue_id, s.id, s.name FROM venue_sports vs
             JOIN sport_types s ON s.id = vs.sport_type_id
             WHERE vs.venue_id IN (' . implode(',', array_fill(0, count($venueIds), '?')) . ')
             ORDER BY s.id',
            str_repeat('i', count($venueIds)),
            $venueIds
        );
        $sports = array_fill_keys($venueIds, []);
        foreach ($rows as $row) {
            $sports[(int) $row['venue_id']][] = ['id' => (int) $row['id'], 'name' => $row['name']];
        }
        return $sports;
    }

    public function addAll(int $venueId, array $sportTypeIds): void
    {
        foreach ($sportTypeIds as $sportTypeId) {
            $this->insert('INSERT INTO venue_sports (venue_id, sport_type_id) VALUES (?, ?)', 'ii', [$venueId, (int) $sportTypeId]);
        }
    }

    public function removeAll(int $venueId, array $sportTypeIds): void
    {
        foreach ($sportTypeIds as $sportTypeId) {
            $this->execute('DELETE FROM venue_sports WHERE venue_id = ? AND sport_type_id = ?', 'ii', [$venueId, (int) $sportTypeId]);
        }
    }
}
