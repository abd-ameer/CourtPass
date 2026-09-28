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

    /** Every account for the admin user list, optionally one role, with a short standing label. */
    public function allUsers(?string $role = null): array
    {
        $role = in_array($role, ['customer', 'owner', 'coach', 'admin'], true) ? $role : null;
        return array_map(function (array $u): array {
            $plural = fn (int $n, string $word) => $n . ' ' . $word . ($n === 1 ? '' : 's');
            $u['id'] = (int) $u['id'];
            $u['standing'] = match ($u['role']) {
                'admin'    => 'Platform administrator',
                'customer' => $u['reliability_tier'] === 'new_member' || $u['reliability_score'] === null
                    ? status_label((string) $u['reliability_tier'])
                    : rtrim(rtrim(number_format((float) $u['reliability_score'], 2), '0'), '.') . '% ' . status_label($u['reliability_tier']),
                'owner'    => (int) $u['approved_venues'] === 0 && (int) $u['pending_venues'] === 0
                    ? 'No venues yet'
                    : implode(', ', array_filter([
                        (int) $u['approved_venues'] > 0 ? $plural((int) $u['approved_venues'], 'approved venue') : null,
                        (int) $u['pending_venues'] > 0 ? $plural((int) $u['pending_venues'], 'pending venue') : null,
                    ])),
                'coach'    => (int) $u['is_verified'] === 1 ? 'Verified coach' : 'Not verified yet',
                default    => '',
            };
            return $u;
        }, $this->users->all($role));
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
