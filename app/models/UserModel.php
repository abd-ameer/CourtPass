<?php
class UserModel extends Model
{
    private const COLUMNS = 'id, role, name, email, phone, password_hash, status';

    public function findByEmail(string $email): ?array
    {
        return $this->selectOne('SELECT ' . self::COLUMNS . ' FROM users WHERE email = ?', 's', [$email]);
    }

    /** Every account, optionally one role, with the fields the admin user list shows as standing. */
    public function all(?string $role): array
    {
        $sql = "SELECT u.id, u.role, u.name, u.email, u.phone, u.status, u.created_at,
                       cp.reliability_score, cp.reliability_tier, co.is_verified,
                       (SELECT COUNT(*) FROM venues v WHERE v.owner_id = u.id AND v.status = 'approved') AS approved_venues,
                       (SELECT COUNT(*) FROM venues v WHERE v.owner_id = u.id AND v.status = 'pending') AS pending_venues
                FROM users u
                LEFT JOIN customer_profiles cp ON cp.customer_id = u.id
                LEFT JOIN coach_profiles co ON co.coach_id = u.id";
        if ($role !== null) {
            return $this->select($sql . ' WHERE u.role = ? ORDER BY u.id', 's', [$role]);
        }
        return $this->select($sql . ' ORDER BY u.id');
    }

    public function findById(int $id): ?array
    {
        return $this->selectOne('SELECT ' . self::COLUMNS . ' FROM users WHERE id = ?', 'i', [$id]);
    }

    public function emailExists(string $email): bool
    {
        return $this->selectOne('SELECT 1 FROM users WHERE email = ?', 's', [$email]) !== null;
    }

    public function create(string $role, string $name, string $email, ?string $phone, string $passwordHash): int
    {
        return $this->insert(
            'INSERT INTO users (role, name, email, phone, password_hash) VALUES (?, ?, ?, ?, ?)',
            'sssss',
            [$role, $name, $email, $phone, $passwordHash]
        );
    }

    public function updateDetails(int $id, string $name, ?string $phone): int
    {
        return $this->execute('UPDATE users SET name = ?, phone = ? WHERE id = ?', 'ssi', [$name, $phone, $id]);
    }

    public function updatePasswordHash(int $id, string $passwordHash): int
    {
        return $this->execute('UPDATE users SET password_hash = ? WHERE id = ?', 'si', [$passwordHash, $id]);
    }
}
