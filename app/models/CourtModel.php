<?php
class CourtModel extends Model
{
    /** Court with the venue fields needed to decide if it can be booked. */
    public function findWithVenue(int $id): ?array
    {
        return $this->selectOne(
            'SELECT c.id, c.venue_id, c.name, c.sport_type_id, c.hourly_rate, c.is_active,
                    v.name AS venue_name, v.slug AS venue_slug, v.owner_id,
                    v.status AS venue_status, v.is_active AS venue_is_active
             FROM courts c
             JOIN venues v ON v.id = c.venue_id
             WHERE c.id = ?',
            'i',
            [$id]
        );
    }

    /** Active courts of a venue, for the court tabs on the slot grid. */
    public function activeForVenue(int $venueId): array
    {
        return $this->select('SELECT id, name FROM courts WHERE venue_id = ? AND is_active = 1 ORDER BY id', 'i', [$venueId]);
    }

    /** Must run inside a transaction; the lock is held until commit or rollback. */
    public function lockForUpdate(int $id): bool
    {
        return $this->selectOne('SELECT id FROM courts WHERE id = ? FOR UPDATE', 'i', [$id]) !== null;
    }
}
