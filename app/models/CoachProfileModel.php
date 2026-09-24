<?php
class CoachProfileModel extends Model
{
    /** New coaches start unverified; certifications are added later from the profile page. */
    public function create(int $coachId, string $experienceLevel, ?string $bio): void
    {
        $this->insert(
            'INSERT INTO coach_profiles (coach_id, experience_level, bio) VALUES (?, ?, ?)',
            'iss',
            [$coachId, $experienceLevel, $bio]
        );
    }
}
