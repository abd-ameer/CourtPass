<?php
/**
 * CourtPass — API: Admin Coach Identity Verification Endpoint
 * Verifies coach identity after inspecting uploaded NIC document photo artifact.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);
requireRole('admin', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$coachUserId = (int)($_POST['coach_user_id'] ?? 0);
$status = $_POST['status'] ?? ''; // 'verified' or 'rejected'
$adminId = currentUserId();

if (!$coachUserId || !in_array($status, ['verified', 'rejected'], true)) {
    jsonError('Invalid coach verification parameters.');
}

$profile = dbFetchOne("SELECT * FROM coach_profiles WHERE user_id = ?", 'i', [$coachUserId]);
if (!$profile) {
    jsonError('Coach profile not found.', 404);
}

dbQuery("UPDATE coach_profiles SET verification_status = ? WHERE user_id = ?", 'si', [$status, $coachUserId]);

writeAuditLog($adminId, 'coach_profile', $coachUserId, "admin_coach_verification_{$status}");

// Mandatory Notification Event:
// Event: Coach identity verification completed -> Coach
createNotification(
    $coachUserId,
    'coach_verification_completed',
    $status === 'verified' ? 'Identity Verified! 🎉' : 'Verification Update',
    $status === 'verified'
        ? 'Your NIC document photo has been verified by platform admin. You can now schedule sessions.'
        : 'Your coach identity verification was declined. Please re-upload a clear photo of your NIC document.',
    $coachUserId,
    'coach_profile'
);

jsonSuccess("Coach identity status updated to {$status}.");
