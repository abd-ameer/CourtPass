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
