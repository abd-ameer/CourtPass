<?php
class CoachProfileModel extends Model
{
    private const DETAIL_SELECT = "
        SELECT cp.coach_id, cp.bio, cp.experience_level, cp.certifications, cp.is_verified, cp.verified_at,
               u.name, u.email, u.phone, u.created_at,
               (SELECT AVG(r.rating) FROM reviews r WHERE r.coach_id = cp.coach_id AND r.status = 'active') AS avg_rating,
               (SELECT COUNT(*) FROM reviews r WHERE r.coach_id = cp.coach_id AND r.status = 'active') AS review_count,
               (SELECT GROUP_CONCAT(st.name ORDER BY st.id SEPARATOR ', ')
                FROM coach_sports cs JOIN sport_types st ON st.id = cs.sport_type_id WHERE cs.coach_id = cp.coach_id) AS sport_names
        FROM coach_profiles cp
        JOIN users u ON u.id = cp.coach_id";

    /** New coaches start unverified; certifications are added later from the profile page. */
    public function create(int $coachId, string $experienceLevel, ?string $bio): void
    {
        $this->insert(
            'INSERT INTO coach_profiles (coach_id, experience_level, bio) VALUES (?, ?, ?)',
            'iss',
            [$coachId, $experienceLevel, $bio]
        );
    }

    /** One coach's profile with name, contact, sports and rating, or null. */
    public function find(int $coachId): ?array
    {
        return $this->selectOne(self::DETAIL_SELECT . ' WHERE cp.coach_id = ?', 'i', [$coachId]);
    }

    /** Every coach for the admin's verification page, unverified first, then by name. */
    public function forAdmin(): array
    {
        return $this->select(self::DETAIL_SELECT . ' ORDER BY cp.is_verified, u.name');
    }
}
