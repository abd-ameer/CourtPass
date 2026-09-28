<?php
class NotificationService
{
    public const PAGE_LIMIT = 50;

    /** In-app message for one user. $link is an app path such as /customer/bookings/12. */
    public function notify(int $userId, string $type, string $title, string $message, ?string $link = null): void
    {
        (new NotificationModel())->create(
            $userId,
            $type,
            mb_substr($title, 0, 150),
            mb_substr($message, 0, 500),
            $link
        );
    }

    /** The user's latest notifications, newest first. */
    public function recent(int $userId, int $limit = self::PAGE_LIMIT): array
    {
        return array_map(fn (array $n): array => [
            'id'         => (int) $n['id'],
            'type'       => $n['type'],
            'title'      => $n['title'],
            'message'    => $n['message'],
            'link'       => $n['link_url'],
            'is_read'    => (bool) $n['is_read'],
            'created_at' => $n['created_at'],
        ], (new NotificationModel())->forUser($userId, $limit));
    }

    public function unreadCount(int $userId): int
    {
        return (new NotificationModel())->unreadCount($userId);
    }

    /** Returns how many notifications changed to read. */
    public function markAllRead(int $userId): int
    {
        return (new NotificationModel())->markAllRead($userId, now());
    }
}
