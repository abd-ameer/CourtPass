<?php
/**
 * CourtPass — Notification Helper
 * Writes notification rows inline at the moment events occur.
 * No batch jobs — inserted directly inside the relevant service method.
 */

require_once __DIR__ . '/db.php';

/**
 * Create a notification for a user.
 *
 * @param int         $userId            Recipient user ID
 * @param string      $type              Notification type identifier
 * @param string      $title             Short title
 * @param string      $message           Full message body
 * @param int|null    $relatedEntityId   Optional related entity ID
 * @param string|null $relatedEntityType Optional entity type (e.g., 'booking', 'coach_session')
 */
function createNotification(
    int $userId,
    string $type,
    string $title,
    string $message,
    ?int $relatedEntityId = null,
    ?string $relatedEntityType = null
): void {
    try {
        dbQuery(
            "INSERT INTO notifications (user_id, type, title, message, related_entity_id, related_entity_type) 
             VALUES (?, ?, ?, ?, ?, ?)",
            'isssss',
            [$userId, $type, $title, $message, $relatedEntityId, $relatedEntityType]
        );
    } catch (Exception $e) {
        // Notification failures should not break the main flow
        error_log("CourtPass notification creation failed: {$e->getMessage()}");
    }
}

/**
 * Get unread notifications for a user.
 *
 * @param int $userId
 * @param int $limit
 * @return array
 */
function getUnreadNotifications(int $userId, int $limit = 20): array {
    return dbFetchAll(
        "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT ?",
        'ii',
        [$userId, $limit]
    );
}

/**
 * Get all notifications for a user (paginated).
 *
 * @param int $userId
 * @param int $limit
 * @param int $offset
 * @return array
 */
function getAllNotifications(int $userId, int $limit = 20, int $offset = 0): array {
    return dbFetchAll(
        "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
        'iii',
        [$userId, $limit, $offset]
    );
}

/**
 * Mark a notification as read.
 *
 * @param int $notificationId
 * @param int $userId
 */
function markNotificationRead(int $notificationId, int $userId): void {
    dbQuery(
        "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
        'ii',
        [$notificationId, $userId]
    );
}

/**
 * Mark all notifications as read for a user.
 *
 * @param int $userId
 */
function markAllNotificationsRead(int $userId): void {
    dbQuery(
        "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0",
        'i',
        [$userId]
    );
}

/**
 * Get unread notification count.
 *
 * @param int $userId
 * @return int
 */
function getUnreadCount(int $userId): int {
    $row = dbFetchOne(
        "SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0",
        'i',
        [$userId]
    );
    return (int)($row['cnt'] ?? 0);
}
