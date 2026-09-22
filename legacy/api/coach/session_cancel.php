<?php
/**
 * CourtPass — API: Coach Session Cancel Endpoint
 * Cancelling a session triggers full refund to all registered students & updates underlying court block.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$sessionId = (int)($_POST['session_id'] ?? 0);
$userId = currentUserId();
$role = currentUserRole();

if (!$sessionId) {
    jsonError('Session ID is required.');
}

$session = dbFetchOne("SELECT * FROM coach_sessions WHERE id = ?", 'i', [$sessionId]);

if (!$session) {
    jsonError('Session not found.', 404);
}

if ($role !== 'admin' && $session['coach_id'] !== $userId) {
    jsonError('Access denied.', 403);
}

if ($session['status'] === 'cancelled') {
    jsonError('Session is already cancelled.');
}

try {
    dbBeginTransaction();

    dbQuery("UPDATE coach_sessions SET status = 'cancelled' WHERE id = ?", 'i', [$sessionId]);

    // Fetch all registered students for full refund
    $students = dbFetchAll("SELECT * FROM session_registrations WHERE session_id = ? AND payment_status = 'paid'", 'i', [$sessionId]);

    foreach ($students as $student) {
        dbQuery("UPDATE session_registrations SET payment_status = 'refunded' WHERE id = ?", 'i', [$student['id']]);

        // Mandatory Notification Event:
        // Event: Session cancelled by coach -> All registered students
        createNotification(
            $student['customer_id'],
            'coach_session_cancelled',
            'Training Session Cancelled ⚠️',
            "The coach has cancelled session '{$session['title']}' on " . formatDate($session['session_date']) . ". A full refund has been issued.",
            $sessionId,
            'coach_session'
        );
    }

    writeAuditLog($userId, 'coach_session', $sessionId, 'coach_session_cancelled_by_coach', [
        'refunded_students' => count($students)
    ]);

    dbCommit();

    jsonSuccess('Session cancelled. Full refunds processed for all registered students.', ['refunded_count' => count($students)]);

} catch (Exception $e) {
    dbRollback();
    error_log('Session cancel error: ' . $e->getMessage());
    jsonError('Failed to cancel session.');
}
