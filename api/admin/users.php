<?php
/**
 * CourtPass — API: Admin User Management Endpoint
 * Deactivates/activates user accounts (customer, owner, coach).
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

requireLogin(true);
requireRole('admin', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$targetUserId = (int)($_POST['user_id'] ?? 0);
$action = $_POST['action'] ?? ''; // 'deactivate' or 'activate'
$adminId = currentUserId();

if (!$targetUserId || !in_array($action, ['deactivate', 'activate'], true)) {
    jsonError('Invalid user action parameters.');
}

if ($targetUserId === $adminId) {
    jsonError('You cannot deactivate your own admin account.');
}

$status = ($action === 'deactivate') ? 'deactivated' : 'active';

dbQuery("UPDATE users SET status = ? WHERE id = ?", 'si', [$status, $targetUserId]);
writeAuditLog($adminId, 'user', $targetUserId, "admin_user_{$action}d");

jsonSuccess("User account {$action}d successfully.");
