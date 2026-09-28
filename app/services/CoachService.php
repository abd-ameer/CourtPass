<?php
class CoachService
{
    /**
     * Coach sign-up: users, coach_profiles and coach_sports rows in one transaction.
     * $data holds the account fields plus experience_level, bio and sports (sport type ids).
     */
    public function register(array $data): array
    {
        $auth = new AuthService();

        $id = Database::transaction(function () use ($auth, $data): int {
            $id = $auth->createUser('coach', $data);
            $bio = trim((string) ($data['bio'] ?? ''));
            (new CoachProfileModel())->create($id, (string) $data['experience_level'], $bio === '' ? null : $bio);
            (new CoachSportModel())->addAll($id, $data['sports']);
            return $id;
        });

        return AuthService::sessionUser((new UserModel())->findById($id));
    }

    /** A coach's profile with sport ids and names, rating and approved venues, or null when the user is not a coach. */
    public function profile(int $coachId): ?array
    {
        $p = (new CoachProfileModel())->find($coachId);
        if ($p === null) {
            return null;
        }
        return self::presentProfile($p) + [
            'sport_type_ids' => (new CoachSportModel())->sportIdsFor($coachId),
            'venues'         => (new CoachVenueApprovalModel())->approvedVenuesFor($coachId),
        ];
    }

    /** The coach's active reviews with their session, newest first. */
    public function reviews(int $coachId, ?int $limit = null): array
    {
        return array_map(function (array $r): array {
            $r['id'] = (int) $r['id'];
            $r['rating'] = (int) $r['rating'];
            $r['session_starts_at'] = $r['session_date'] . ' ' . $r['start_time'];
            return $r;
        }, (new ReviewModel())->forCoach($coachId, $limit));
    }

    /** Every coach for the admin's verification page, unverified first. */
    public function adminCoaches(): array
    {
        return array_map(fn (array $p) => self::presentProfile($p), (new CoachProfileModel())->forAdmin());
    }

    /** Coach requests at the owner's venues: ['pending' => [...], 'approved' => [...]]. */
    public function ownerRequests(int $ownerId): array
    {
        $groups = ['pending' => [], 'approved' => []];
        foreach ((new CoachVenueApprovalModel())->forVenueOwner($ownerId) as $r) {
            $r['id'] = (int) $r['id'];
            $r['is_verified'] = (int) $r['is_verified'] === 1;
            $r['upcoming_count'] = (int) $r['upcoming_count'];
            $groups[$r['status']][] = $r;
        }
        return $groups;
    }

    private static function presentProfile(array $p): array
    {
        $p['coach_id'] = (int) $p['coach_id'];
        $p['is_verified'] = (int) $p['is_verified'] === 1;
        $p['avg_rating'] = $p['avg_rating'] === null ? null : round((float) $p['avg_rating'], 1);
        $p['review_count'] = (int) $p['review_count'];
        $p['sport_names'] = (string) ($p['sport_names'] ?? '');
        return $p;
    }
}
