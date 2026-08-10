<?php
/**
 * CourtPass — API: Coach Session Creation Endpoint
 * Shared conflict check: verifies court availability against court bookings AND coach sessions.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

requireLogin(true);
requireRole('coach', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$coachId = currentUserId();

// Check if coach is verified by admin
$profile = dbFetchOne("SELECT verification_status FROM coach_profiles WHERE user_id = ?", 'i', [$coachId]);
if (!$profile || $profile['verification_status'] !== 'verified') {
    jsonError('Your coach account must be verified by admin before creating training sessions.');
}

$courtId = (int)($_POST['court_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$sessionDate = trim($_POST['session_date'] ?? '');
$sessionStart = trim($_POST['session_start'] ?? '');
$capacity = (int)($_POST['capacity'] ?? 0);
$pricePerStudent = (float)($_POST['price_per_student'] ?? 0);
$visibility = $_POST['visibility'] ?? 'public'; // 'public' or 'private'

if (!$courtId || empty($title) || !$sessionDate || !$sessionStart || $capacity <= 0 || $pricePerStudent < 0) {
    jsonError('All session fields are required and capacity must be at least 1.');
}

// Check if coach has approved access to this court's venue
$court = dbFetchOne("SELECT c.*, v.id as venue_id FROM courts c JOIN venues v ON c.venue_id = v.id WHERE c.id = ?", 'i', [$courtId]);

if (!$court) {
    jsonError('Court not found.', 404);
}

$approval = dbFetchOne(
    "SELECT status FROM coach_venue_approvals WHERE coach_id = ? AND venue_id = ? AND status = 'approved'",
    'ii',
    [$coachId, $court['venue_id']]
);

if (!$approval) {
    jsonError('You do not have active venue owner approval for this venue.');
}

// Session end is fixed at 1 hour later
$dt = new DateTime("{$sessionDate} {$sessionStart}", new DateTimeZone('Asia/Colombo'));
if ($dt < new DateTime('now', new DateTimeZone('Asia/Colombo'))) {
    jsonError('Cannot schedule sessions in the past.');
}

$sessionEnd = (clone $dt)->modify('+1 hour')->format('H:i:s');

// SHARED CONFLICT CHECK POINT: Verify court is free from bookings & sessions
try {
    dbBeginTransaction();

    // Check court bookings
    $bookingConflict = dbFetchOne(
        "SELECT id FROM bookings WHERE court_id = ? AND slot_date = ? AND slot_start = ? AND status IN ('pending', 'confirmed') FOR UPDATE",
        'iss',
        [$courtId, $sessionDate, $sessionStart]
    );

    if ($bookingConflict) {
        dbRollback();
        jsonError('This court slot is already booked by a player reservation.');
    }

    // Check other coach sessions
    $sessionConflict = dbFetchOne(
        "SELECT id FROM coach_sessions WHERE court_id = ? AND session_date = ? AND session_start = ? AND status IN ('open', 'full') FOR UPDATE",
        'iss',
        [$courtId, $sessionDate, $sessionStart]
    );

    if ($sessionConflict) {
        dbRollback();
        jsonError('Another coach session is already scheduled on this court at the selected time.');
    }

    $privateToken = ($visibility === 'private') ? generateToken(16) : null;

    dbQuery(
        "INSERT INTO coach_sessions (coach_id, court_id, venue_id, title, description, session_date, session_start, session_end, capacity, price_per_student, visibility, private_token, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'open')",
        'iiisssssidss',
        [$coachId, $courtId, $court['venue_id'], $title, $description, $sessionDate, $sessionStart, $sessionEnd, $capacity, $pricePerStudent, $visibility, $privateToken]
    );

    $sessionId = dbLastInsertId();

    writeAuditLog($coachId, 'coach_session', $sessionId, 'coach_session_created', [
        'title' => $title,
        'visibility' => $visibility,
        'capacity' => $capacity
    ]);

    dbCommit();

    jsonSuccess('Coach session created successfully!', [
        'session_id' => $sessionId,
        'private_token' => $privateToken,
        'share_link' => $privateToken ? BASE_URL . "/session?token={$privateToken}" : BASE_URL . "/session?id={$sessionId}"
    ]);

} catch (Exception $e) {
    dbRollback();
    error_log('Coach session creation error: ' . $e->getMessage());
    jsonError('Failed to create coach session due to scheduling conflict.');
}
