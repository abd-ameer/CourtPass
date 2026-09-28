<?php
/**
 * Coach venue approvals table. Read queries join venues, courts and coach sports for labels and filters only.
 */
class CoachVenueApprovalModel extends Model
{
    public function isApproved(int $coachId, int $venueId): bool
    {
        return $this->selectOne(
            "SELECT id FROM coach_venue_approvals WHERE coach_id = ? AND venue_id = ? AND status = 'approved'",
            'ii',
            [$coachId, $venueId]
        ) !== null;
    }

    /** Listed venues that approved the coach, by name. */
    public function approvedVenuesFor(int $coachId): array
    {
        return $this->select(
            "SELECT v.id, v.name, v.slug, v.city
             FROM coach_venue_approvals a
             JOIN venues v ON v.id = a.venue_id AND v.status = 'approved' AND v.is_active = 1
             WHERE a.coach_id = ? AND a.status = 'approved'
             ORDER BY v.name",
            'i',
            [$coachId]
        );
    }

    /**
     * Pending and approved coach requests at the owner's venues, with the coach's profile,
     * sports and upcoming session count at that venue.
     */
    public function forVenueOwner(int $ownerId): array
    {
        return $this->select(
            "SELECT a.id, a.coach_id, a.venue_id, a.status, a.requested_at, a.decided_at,
                    v.name AS venue_name, u.name AS coach_name, u.email AS coach_email, u.phone AS coach_phone,
                    cp.experience_level, cp.certifications, cp.is_verified,
                    (SELECT GROUP_CONCAT(st.name ORDER BY st.id SEPARATOR ', ')
                     FROM coach_sports cs JOIN sport_types st ON st.id = cs.sport_type_id WHERE cs.coach_id = a.coach_id) AS sport_names,
                    (SELECT COUNT(*) FROM coach_sessions s JOIN courts c ON c.id = s.court_id
                     WHERE s.coach_id = a.coach_id AND c.venue_id = a.venue_id AND s.status IN ('open', 'full')
                       AND TIMESTAMP(s.session_date, s.start_time) > NOW()) AS upcoming_count,
                    (SELECT MIN(TIMESTAMP(s.session_date, s.start_time)) FROM coach_sessions s JOIN courts c ON c.id = s.court_id
                     WHERE s.coach_id = a.coach_id AND c.venue_id = a.venue_id AND s.status IN ('open', 'full')
                       AND TIMESTAMP(s.session_date, s.start_time) > NOW()) AS next_session_at
             FROM coach_venue_approvals a
             JOIN venues v ON v.id = a.venue_id
             JOIN users u ON u.id = a.coach_id
             JOIN coach_profiles cp ON cp.coach_id = a.coach_id
             WHERE v.owner_id = ? AND a.status IN ('pending', 'approved')
             ORDER BY a.status = 'approved', a.requested_at, a.id",
            'i',
            [$ownerId]
        );
    }

    /** Active courts at the coach's approved, listed venues whose sport the coach teaches. */
    public function approvedCourtsFor(int $coachId): array
    {
        return $this->select(
            "SELECT v.id AS venue_id, v.name AS venue_name, c.id AS court_id, c.name AS court_name, st.name AS sport_name
             FROM coach_venue_approvals a
             JOIN venues v ON v.id = a.venue_id AND v.status = 'approved' AND v.is_active = 1
             JOIN courts c ON c.venue_id = v.id AND c.is_active = 1
             JOIN coach_sports cs ON cs.coach_id = a.coach_id AND cs.sport_type_id = c.sport_type_id
             JOIN sport_types st ON st.id = c.sport_type_id
             WHERE a.coach_id = ? AND a.status = 'approved'
             ORDER BY v.name, c.name",
            'i',
            [$coachId]
        );
    }
}
