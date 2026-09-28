<?php
class NotificationModel extends Model
{
    public function create(int $userId, string $type, string $title, string $message, ?string $linkUrl): int
    {
        return $this->insert(
            'INSERT INTO notifications (user_id, type, title, message, link_url) VALUES (?, ?, ?, ?, ?)',
            'issss',
            [$userId, $type, $title, $message, $linkUrl]
        );
    }

    /** The user's notifications, newest first. */
    public function forUser(int $userId, int $limit): array
    {
        return $this->select(
            'SELECT id, type, title, message, link_url, is_read, created_at
             FROM notifications WHERE user_id = ?
             ORDER BY created_at DESC, id DESC LIMIT ?',
            'ii',
            [$userId, $limit]
        );
    }

    public function unreadCount(int $userId): int
    {
        $row = $this->selectOne('SELECT COUNT(*) AS n FROM notifications WHERE user_id = ? AND is_read = 0', 'i', [$userId]);
        return (int) ($row['n'] ?? 0);
    }

    public function markAllRead(int $userId, string $now): int
    {
        return $this->execute(
            'UPDATE notifications SET is_read = 1, read_at = ? WHERE user_id = ? AND is_read = 0',
            'si',
            [$now, $userId]
        );
    }
}
