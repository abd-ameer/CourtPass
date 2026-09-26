<?php
class AnnouncementModel extends Model
{
    public function activeForVenue(int $venueId): array
    {
        return $this->select(
            "SELECT id, type, title, body, created_at FROM announcements
             WHERE venue_id = ? AND status = 'active' ORDER BY created_at DESC, id DESC",
            'i',
            [$venueId]
        );
    }
}
