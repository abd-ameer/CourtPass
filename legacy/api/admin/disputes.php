<?php
/**
 * CourtPass — API: Admin Dispute Review & No-Show Clearing Endpoint
 * Clearing a no-show triggers an automatic reliability recalculation.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../reliability/score.php';

requireLogin(true);
requireRole('admin', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$bookingId = (int)($_POST['booking_id'] ?? 0);
$action = $_POST['action'] ?? ''; // 'clear_noshow'
$adminId = currentUserId();

if (!$bookingId || $action !== 'clear_noshow') {
    jsonError('Invalid dispute action parameters.');
}

$booking = dbFetchOne("SELECT * FROM bookings WHERE id = ?", 'i', [$bookingId]);

if (!$booking || $booking['status'] !== 'no_show') {
    jsonError('Booking not found or not in no-show status.', 404);
}

try {
    dbBeginTransaction();

    // Revert status to completed
    dbQuery("UPDATE bookings SET status = 'completed' WHERE id = ?", 'i', [$bookingId]);

    writeAuditLog($adminId, 'booking', $bookingId, 'admin_cleared_no_show_dispute', [
        'customer_id' => $booking['customer_id']
    ]);

    dbCommit();

    // Automatic reliability recalculation after clearing dispute
    $recalc = recalculateReliabilityScore($booking['customer_id'], 'admin_dispute_cleared', $bookingId);

    jsonSuccess("No-show dispute cleared. Reliability recalculated automatically (New Score: {$recalc['score']}%).", [
        'new_score' => $recalc['score'],
        'new_tier' => $recalc['tier']
    ]);

} catch (Exception $e) {
    dbRollback();
    error_log('Dispute clearing error: ' . $e->getMessage());
    jsonError('Failed to clear no-show dispute.');
}
