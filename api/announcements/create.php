<?php
/**
 * CourtPass — API: Owner Announcements Creation Endpoint
 * Operational or Promotional announcements per venue.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

requireLogin(true);
requireRole('owner', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$venueId = (int)($_POST['venue_id'] ?? 0);
$type = $_POST['type'] ?? 'promotional'; // 'operational' or 'promotional'
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$userId = currentUserId();

if (!$venueId || empty($title) || empty($content) || !in_array($type, ['operational', 'promotional'], true)) {
    jsonError('Venue ID, title, type, and content are required.');
}

$venue = dbFetchOne("SELECT id FROM venues WHERE id = ? AND owner_id = ?", 'ii', [$venueId, $userId]);
if (!$venue) {
    jsonError('Venue not found or access denied.', 403);
}

try {
    dbQuery(
        "INSERT INTO announcements (venue_id, owner_id, type, title, content, status) VALUES (?, ?, ?, ?, ?, 'active')",
        'iisss',
        [$venueId, $userId, $type, $title, $content]
    );

    $annId = dbLastInsertId();

    writeAuditLog($userId, 'announcement', $annId, 'announcement_posted', [
        'venue_id' => $venueId,
        'type' => $type,
        'title' => $title
    ]);

    jsonSuccess('Announcement posted successfully!', ['announcement_id' => $annId]);

} catch (Exception $e) {
    error_log('Announcement error: ' . $e->getMessage());
    jsonError('Failed to post announcement.');
}
