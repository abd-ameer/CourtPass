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
}
