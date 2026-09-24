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
}
