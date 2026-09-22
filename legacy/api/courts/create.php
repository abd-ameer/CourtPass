<?php
/**
 * CourtPass — API: Court Creation Endpoint (Owner only)
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
$name = trim($_POST['name'] ?? '');
$sportType = $_POST['sport_type'] ?? '';
$hourlyRate = (float)($_POST['hourly_rate'] ?? 0);

$validSports = ['futsal', 'badminton', 'pickleball', 'squash', 'billiards', 'carrom', 'table_tennis'];

if (!$venueId || empty($name) || !in_array($sportType, $validSports, true) || $hourlyRate <= 0) {
    jsonError('Please fill out all court details correctly.');
}

// Verify ownership
$venue = dbFetchOne("SELECT id FROM venues WHERE id = ? AND owner_id = ?", 'ii', [$venueId, currentUserId()]);
if (!$venue) {
    jsonError('Venue not found or access denied.', 403);
}

try {
    dbBeginTransaction();

    dbQuery(
        "INSERT INTO courts (venue_id, name, sport_type, hourly_rate, status) VALUES (?, ?, ?, ?, 'active')",
        'issd',
        [$venueId, $name, $sportType, $hourlyRate]
    );

    $courtId = dbLastInsertId();

    // Default operating hours: Sunday (0) through Saturday (6), 08:00 to 22:00
    for ($day = 0; $day <= 6; $day++) {
        dbQuery(
            "INSERT INTO operating_hours (court_id, day_of_week, open_time, close_time) VALUES (?, ?, '08:00:00', '22:00:00')",
            'iis',
            [$courtId, $day]
        );
    }

    writeAuditLog(currentUserId(), 'court', $courtId, 'court_created', [
        'venue_id' => $venueId,
        'name' => $name,
        'sport_type' => $sportType,
        'hourly_rate' => $hourlyRate
    ]);

    dbCommit();

    jsonSuccess('Court added successfully with standard operating hours (8 AM - 10 PM).', ['court_id' => $courtId]);
} catch (Exception $e) {
    dbRollback();
    error_log('Court creation error: ' . $e->getMessage());
    jsonError('Failed to add court. Please try again.');
}
