<?php
/**
 * CourtPass — API: Booking Confirm & Reject Endpoints (Owner actions)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

requireLogin(true);

$action = getVal('action'); // 'confirm' or 'reject' or 'complete'
$bookingId = (int)($_POST['booking_id'] ?? $_GET['booking_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');
$userId = currentUserId();
$userRole = currentUserRole();

if (!$bookingId) {
    jsonError('Booking ID is required.');
}

$booking = dbFetchOne("SELECT b.*, c.venue_id FROM bookings b JOIN courts c ON b.court_id = c.id WHERE b.id = ?", 'i', [$bookingId]);

if (!$booking) {
    jsonError('Booking not found.', 404);
}

// Check venue ownership
if ($userRole !== 'admin') {
    $venue = dbFetchOne("SELECT owner_id FROM venues WHERE id = ? AND owner_id = ?", 'ii', [$booking['venue_id'], $userId]);
    if (!$venue) {
        jsonError('Access denied.', 403);
    }
}

if ($action === 'confirm') {
    dbQuery("UPDATE bookings SET status = 'confirmed' WHERE id = ?", 'i', [$bookingId]);
    writeAuditLog($userId, 'booking', $bookingId, 'booking_confirmed_by_owner');
    
    createNotification(
        $booking['customer_id'],
        'booking_confirmed',
        'Booking Confirmed! 🎉',
        "Your booking #{$bookingId} for " . formatDate($booking['slot_date']) . " at " . formatTime($booking['slot_start']) . " has been confirmed.",
        $bookingId,
        'booking'
    );
    
    jsonSuccess('Booking confirmed.');
} elseif ($action === 'reject') {
    if (empty($reason)) {
        jsonError('Mandatory rejection reason required.');
    }
    dbQuery("UPDATE bookings SET status = 'cancelled', cancel_reason = ? WHERE id = ?", 'si', ["Rejected by owner: {$reason}", $bookingId]);
    writeAuditLog($userId, 'booking', $bookingId, 'booking_rejected_by_owner', ['reason' => $reason]);
    
    createNotification(
        $booking['customer_id'],
        'booking_rejected',
        'Booking Request Declined',
        "Your booking request #{$bookingId} was declined by the venue owner. Reason: {$reason}",
        $bookingId,
        'booking'
    );
    
    jsonSuccess('Booking rejected.');
} elseif ($action === 'complete') {
    dbQuery("UPDATE bookings SET status = 'completed' WHERE id = ?", 'i', [$bookingId]);
    writeAuditLog($userId, 'booking', $bookingId, 'booking_completed');
    
    // Recalculate customer reliability
    require_once __DIR__ . '/../reliability/score.php';
    recalculateReliabilityScore($booking['customer_id'], 'booking_completed', $bookingId);
    
    jsonSuccess('Booking marked as completed.');
} else {
    jsonError('Invalid action specified.');
}
