<?php
class AccountService
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    /** Name, email, phone and role of an account for the settings page. */
    public function details(int $userId): array
    {
        $user = $this->users->findById($userId);
        if ($user === null) {
            throw new RuntimeException("User {$userId} not found.");
        }
        return [
            'name'  => $user['name'],
            'email' => $user['email'],
            'phone' => (string) $user['phone'],
            'role'  => $user['role'],
        ];
    }

    /** Email is the login and never changes here. Returns the refreshed session user. */
    public function updateDetails(int $userId, string $name, string $phone): array
    {
        $phone = trim($phone);
        $this->users->updateDetails($userId, trim($name), $phone === '' ? null : $phone);
        return AuthService::sessionUser($this->users->findById($userId));
    }

    public function changePassword(int $userId, string $current, string $new): void
    {
        $user = $this->users->findById($userId);
        if ($user === null || !password_verify($current, $user['password_hash'])) {
            throw new ValidationException(['current_password' => 'Current password is incorrect.']);
        }
        if (password_verify($new, $user['password_hash'])) {
            throw new ValidationException(['new_password' => 'Choose a password different from your current one.']);
        }
        $this->users->updatePasswordHash($userId, AuthService::hashPassword($new));
    }
}
