<?php
class AuthService
{
    /** Roles a guest can sign up as. Admin accounts are seeded only. */
    public const SIGNUP_ROLES = ['customer', 'owner', 'coach'];

    public const EMAIL_TAKEN = 'An account with this email already exists.';
    public const LOGIN_FAILED = 'Email or password is incorrect.';

    private const HASH_OPTIONS = ['cost' => 12];

    // Checked when the email is unknown so a missing account takes as long as a wrong password.
    private const DUMMY_HASH = '$2y$12$yQAVPNimbvf0P7vMCJj/qezkCRBzF1Nku8OlM.uqgxwcpW7Wb.jVK';

    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    /** Returns the session user for Auth::login(), or throws with a message for the login form. */
    public function authenticate(string $email, string $password): array
    {
        $user = $this->users->findByEmail(self::normaliseEmail($email));
        $valid = password_verify($password, $user['password_hash'] ?? self::DUMMY_HASH);

        if ($user === null || !$valid) {
            throw new ValidationException(['login' => self::LOGIN_FAILED]);
        }
        if ($user['status'] !== 'active') {
            throw new ValidationException(['login' => Auth::DEACTIVATED_MESSAGE]);
        }
        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT, self::HASH_OPTIONS)) {
            $this->users->updatePasswordHash((int) $user['id'], self::hashPassword($password));
        }
        return self::sessionUser($user);
    }

    public function registerCustomer(array $data): array
    {
        $id = Database::transaction(function () use ($data): int {
            $id = $this->createUser('customer', $data);
            (new CustomerProfileModel())->create($id);
            return $id;
        });
        return self::sessionUser($this->users->findById($id));
    }

    /**
     * Inserts the users row for a sign-up and returns its id.
     * Does not open a transaction: the caller wraps it together with the profile rows,
     * because a nested begin_transaction() would commit the outer one early.
     */
    public function createUser(string $role, array $data): int
    {
        if (!in_array($role, self::SIGNUP_ROLES, true)) {
            throw new InvalidArgumentException("Cannot sign up with role {$role}.");
        }

        $email = self::normaliseEmail((string) $data['email']);
        if ($this->users->emailExists($email)) {
            throw new ValidationException(['email' => self::EMAIL_TAKEN]);
        }

        $phone = trim((string) ($data['phone'] ?? ''));
        try {
            return $this->users->create(
                $role,
                trim((string) $data['name']),
                $email,
                $phone === '' ? null : $phone,
                self::hashPassword((string) $data['password'])
            );
        } catch (mysqli_sql_exception $e) {
            // 1062 = duplicate key: another sign-up took the email after the check above
            if ($e->getCode() === 1062) {
                throw new ValidationException(['email' => self::EMAIL_TAKEN]);
            }
            throw $e;
        }
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT, self::HASH_OPTIONS);
    }

    public static function normaliseEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /** The fields Auth::login() keeps in the session. */
    public static function sessionUser(array $user): array
    {
        return ['id' => (int) $user['id'], 'role' => $user['role'], 'name' => $user['name']];
    }
}
