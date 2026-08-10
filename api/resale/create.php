<?php
/**
 * CourtPass — API: Create Resale Listing Endpoint
 * Server-side enforcement: listing price is capped at 90% of original booking price.
 * Never trust client-submitted prices alone.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

requireLogin(true);
requireRole('customer', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$bookingId = (int)($_POST['booking_id'] ?? 0);
$submittedPrice = (float)($_POST['listing_price'] ?? 0);
$userId = currentUserId();

if (!$bookingId || $submittedPrice <= 0) {
    jsonError('Valid booking ID and listing price are required.');
}

$booking = dbFetchOne("SELECT * FROM bookings WHERE id = ? AND customer_id = ?", 'ii', [$bookingId, $userId]);

if (!$booking) {
    jsonError('Booking not found or access denied.', 404);
}

// Eligibility Rule: booking must be confirmed, online-paid, and not already completed/cancelled
if ($booking['status'] !== 'confirmed') {
    jsonError('Only confirmed bookings can be listed for resale.');
}

if ($booking['payment_method'] !== 'online') {
    jsonError('Cash-on-arrival bookings must be converted to online payment before listing for resale.');
}

// Check if slot has already passed
if (isSlotPast($booking['slot_date'], $booking['slot_start'])) {
    jsonError('Cannot list past slots for resale.');
}

// Check existing active listing
$existing = dbFetchOne("SELECT id FROM resale_listings WHERE booking_id = ? AND status = 'active'", 'i', [$bookingId]);
if ($existing) {
    jsonError('This booking is already listed on the resale marketplace.');
}

// CRITICAL SERVER-SIDE ENFORCEMENT: Max 90% of original booking price
$originalPrice = (float)$booking['amount'];
$maxAllowedPrice = round($originalPrice * 0.90, 2);

if ($submittedPrice > $maxAllowedPrice) {
    jsonError("Listing price exceeds the server-enforced cap of 90% (Max allowed: Rs. {$maxAllowedPrice}).");
}

try {
    dbBeginTransaction();

    dbQuery(
        "INSERT INTO resale_listings (booking_id, seller_id, listing_price, status) VALUES (?, ?, ?, 'active')",
        'iid',
        [$bookingId, $userId, $submittedPrice]
    );

    $resaleId = dbLastInsertId();

    writeAuditLog($userId, 'resale', $resaleId, 'resale_listed', [
        'booking_id' => $bookingId,
        'original_price' => $originalPrice,
        'listing_price' => $submittedPrice,
        'cap_applied_90_percent' => $maxAllowedPrice
    ]);

    dbCommit();

    jsonSuccess("Booking listed for resale at Rs. {$submittedPrice} (90% cap enforced).", ['resale_id' => $resaleId]);

} catch (Exception $e) {
    dbRollback();
    error_log('Resale creation error: ' . $e->getMessage());
    jsonError('Failed to list booking for resale.');
}
