<?php
class UserModel extends Model
{
    private const COLUMNS = 'id, role, name, email, phone, password_hash, status';

    public function findByEmail(string $email): ?array
    {
        return $this->selectOne('SELECT ' . self::COLUMNS . ' FROM users WHERE email = ?', 's', [$email]);
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
