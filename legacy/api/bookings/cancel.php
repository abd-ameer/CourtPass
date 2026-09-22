<?php
/**
 * CourtPass — API: Booking Cancellation Endpoint
 * Precision cancellation policy implementation with time-based refund tiers and reliability penalties.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../reliability/score.php';

requireLogin(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$bookingId = (int)($_POST['booking_id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');
$userId = currentUserId();
$userRole = currentUserRole();

if (!$bookingId) {
    jsonError('Booking ID is required.');
}

$booking = dbFetchOne("SELECT * FROM bookings WHERE id = ?", 'i', [$bookingId]);

if (!$booking) {
    jsonError('Booking not found.', 404);
}

if ($booking['status'] === 'cancelled' || $booking['status'] === 'completed') {
    jsonError('This booking cannot be cancelled in its current state.');
}

// Ownership / permission check
if ($userRole === 'customer' && $booking['customer_id'] !== $userId) {
    jsonError('You do not have permission to cancel this booking.', 403);
}

if ($userRole === 'owner') {
    // Check if owner owns the venue of this court
    $court = dbFetchOne("SELECT venue_id FROM courts WHERE id = ?", 'i', [$booking['court_id']]);
    $venue = dbFetchOne("SELECT owner_id FROM venues WHERE id = ?", 'ii', [$court['venue_id'], $userId]);
    if (!$venue) {
        jsonError('You do not own the venue for this booking.', 403);
    }
    if (empty($reason)) {
        jsonError('Venue owners must provide a mandatory reason when cancelling a booking.');
    }
}

// Calculate time before slot in hours
$hours = hoursUntil($booking['slot_date'], $booking['slot_start']);

if ($hours < 0) {
    jsonError('Cannot cancel a slot that has already started or passed.');
}

// Determine Policy Outcome
$refundPercent = 0;
$cancellationClassification = 'responsible';
$policySummary = '';

if ($booking['payment_method'] === 'online') {
    if ($hours > 48) {
        $refundPercent = 100;
        $cancellationClassification = 'responsible';
        $policySummary = 'Full refund (100%) simulated via PayHere Sandbox.';
    } elseif ($hours >= 12 && $hours <= 48) {
        $refundPercent = 50;
        $cancellationClassification = 'moderate';
        $policySummary = '50% refund simulated via PayHere Sandbox (or list for resale).';
    } else {
        $refundPercent = 0;
        $cancellationClassification = 'irresponsible';
        $policySummary = 'Less than 12 hours before slot: No refund.';
    }
} else {
    // Cash-on-arrival booking cancellation policy
    if ($hours > 48) {
        $cancellationClassification = 'responsible';
        $policySummary = 'Cancelled > 48h before slot: Responsible cancellation (no reliability penalty).';
    } elseif ($hours >= 12 && $hours <= 48) {
        $cancellationClassification = 'moderate';
        $policySummary = 'Cancelled 12–48h before slot: Moderate cancellation (minor reliability penalty).';
    } else {
        $cancellationClassification = 'irresponsible';
        $policySummary = 'Cancelled < 12h before slot: Irresponsible cancellation (larger reliability penalty).';
    }
}

$fullReason = $reason ? "[{$cancellationClassification}] {$reason}" : "[{$cancellationClassification}] Cancelled by customer";

try {
    dbBeginTransaction();

    dbQuery(
        "UPDATE bookings SET status = 'cancelled', cancel_reason = ?, cancelled_by = ? WHERE id = ?",
        'sii',
        [$fullReason, $userId, $bookingId]
    );

    // Record refund in payments table if applicable
    if ($refundPercent > 0) {
        $refundAmount = ($booking['amount'] * $refundPercent) / 100.0;
        dbQuery(
            "UPDATE payments SET status = ?, refund_amount = ? WHERE booking_id = ?",
            'sdi',
            [($refundPercent === 100 ? 'refunded' : 'partially_refunded'), $refundAmount, $bookingId]
        );
    }

    // Write immutable audit log
    writeAuditLog($userId, 'booking', $bookingId, 'booking_cancelled', [
        'cancelled_by_role' => $userRole,
        'hours_before_slot' => $hours,
        'refund_percent' => $refundPercent,
        'cancellation_classification' => $cancellationClassification,
        'reason' => $reason
    ]);

    dbCommit();

    // Trigger reliability recalculation for customer
    recalculateReliabilityScore($booking['customer_id'], 'booking_cancelled', $bookingId);

    jsonSuccess("Booking cancelled. {$policySummary}", [
        'refund_percent' => $refundPercent,
        'cancellation_classification' => $cancellationClassification
    ]);

} catch (Exception $e) {
    dbRollback();
    error_log('Booking cancellation error: ' . $e->getMessage());
    jsonError('Failed to cancel booking.');
}
