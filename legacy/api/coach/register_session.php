<?php
/**
 * CourtPass — API: Register & Cancel Session Student Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);

$action = getVal('action', 'register');
$userId = currentUserId();

if ($action === 'cancel_my_registration') {
    requireCsrf(true);
    $regId = (int)($_POST['registration_id'] ?? 0);

    $reg = dbFetchOne("SELECT sr.*, cs.coach_id, cs.title FROM session_registrations sr JOIN coach_sessions cs ON sr.session_id = cs.id WHERE sr.id = ? AND sr.customer_id = ?", 'ii', [$regId, $userId]);

    if (!$reg) {
        jsonError('Registration not found.');
    }

    dbQuery("DELETE FROM session_registrations WHERE id = ?", 'i', [$regId]);
    dbQuery("UPDATE coach_sessions SET registered_count = registered_count - 1, status = 'open' WHERE id = ?", 'i', [$reg['session_id']]);

    // Mandatory Notification Event:
    // Event: Student cancels a registration -> Coach
    $customerName = currentUserName();
    createNotification(
        $reg['coach_id'],
        'student_cancelled_registration',
        'Student Cancelled Registration ℹ️',
        "Student {$customerName} cancelled their registration for session '{$reg['title']}'.",
        $reg['session_id'],
        'coach_session'
    );

    jsonSuccess('Registration cancelled.');
    exit;
}

// Default: Register for session
requireRole('customer', true);
requireCsrf(true);

$sessionId = (int)($_POST['session_id'] ?? 0);
$token = trim($_POST['private_token'] ?? '');

if (!$sessionId) {
    jsonError('Session ID is required.');
}

$session = dbFetchOne("SELECT * FROM coach_sessions WHERE id = ?", 'i', [$sessionId]);

if (!$session || $session['status'] === 'cancelled') {
    jsonError('Session not found or cancelled.');
}

// If private session, check private_token
if ($session['visibility'] === 'private' && $session['private_token'] !== $token) {
    jsonError('Invalid link/token for this private session.', 403);
}

if ($session['registered_count'] >= $session['capacity']) {
    jsonError('This session is full.');
}

// Check existing registration
$existing = dbFetchOne("SELECT id FROM session_registrations WHERE session_id = ? AND customer_id = ?", 'ii', [$sessionId, $userId]);
if ($existing) {
    jsonError('You are already registered for this training session.');
}

try {
    dbBeginTransaction();

    dbQuery(
        "INSERT INTO session_registrations (session_id, customer_id, payment_status, attendance) VALUES (?, ?, 'paid', 'registered')",
        'ii',
        [$sessionId, $userId]
    );

    $regId = dbLastInsertId();

    $newCount = $session['registered_count'] + 1;
    $newStatus = ($newCount >= $session['capacity']) ? 'full' : 'open';

    dbQuery("UPDATE coach_sessions SET registered_count = ?, status = ? WHERE id = ?", 'isi', [$newCount, $newStatus, $sessionId]);

    $customerName = currentUserName();

    // Mandatory Notification Events:
    // Event: New registration for a session -> Coach
    createNotification(
        $session['coach_id'],
        'new_session_registration',
        'New Student Registration! 🎓',
        "{$customerName} registered for your session '{$session['title']}'.",
        $sessionId,
        'coach_session'
    );

    // Event: Session reaches full capacity -> Coach
    if ($newStatus === 'full') {
        createNotification(
            $session['coach_id'],
            'session_full_capacity',
            'Session Full! 🔥',
            "Your session '{$session['title']}' has reached 100% capacity ({$session['capacity']} students).",
            $sessionId,
            'coach_session'
        );
    }

    writeAuditLog($userId, 'session_registration', $regId, 'student_registered_for_coach_session', [
        'session_id' => $sessionId,
        'amount' => $session['price_per_student']
    ]);

    dbCommit();

    jsonSuccess('Successfully registered for training session!', ['registration_id' => $regId]);

} catch (Exception $e) {
    dbRollback();
    error_log('Session registration error: ' . $e->getMessage());
    jsonError('Failed to register for session.');
}
