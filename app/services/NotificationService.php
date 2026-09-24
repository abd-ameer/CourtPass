<?php
class NotificationService
{
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
}
