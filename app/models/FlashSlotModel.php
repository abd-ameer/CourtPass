<?php
/**
 * Flash slots table. Read queries join courts, venues and sport types for labels only.
 */
class FlashSlotModel extends Model
{
    private const DETAIL_SELECT = "
        SELECT f.id, f.court_id, f.slot_date, f.start_time, f.original_price, f.discounted_price, f.status,
               c.name AS court_name, v.id AS venue_id, v.name AS venue_name, v.slug AS venue_slug, v.city AS venue_city,
               st.code AS sport_code, st.name AS sport_name
        FROM flash_slots f
        JOIN courts c ON c.id = f.court_id
        JOIN venues v ON v.id = c.venue_id
        JOIN sport_types st ON st.id = c.sport_type_id";

    /** Active deals on listed venues and active courts whose slot starts after $now, soonest first. */
    public function activeUpcoming(string $now): array
    {
        return $this->select(
            self::DETAIL_SELECT . " WHERE f.status = 'active' AND v.status = 'approved' AND v.is_active = 1 AND c.is_active = 1
                AND TIMESTAMP(f.slot_date, f.start_time) > ?
             ORDER BY f.slot_date, f.start_time, f.id",
            's',
            [$now]
        );
    }

    /** Active deals at the owner's venues whose slot starts after $now, soonest first. */
    public function activeForOwner(int $ownerId, string $now): array
    {
        return $this->select(
            self::DETAIL_SELECT . " WHERE f.status = 'active' AND v.owner_id = ? AND TIMESTAMP(f.slot_date, f.start_time) > ?
             ORDER BY f.slot_date, f.start_time, f.id",
            'is',
            [$ownerId, $now]
        );
    }
}
