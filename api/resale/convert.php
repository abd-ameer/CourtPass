<?php
/**
 * CourtPass — API: Cash-on-Arrival Booking Conversion to Online Payment for Resale Endpoint
 * Explicit choice presented to customer to convert cash-on-arrival to online payment before listing for resale.
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
$userId = currentUserId();

$booking = dbFetchOne("SELECT * FROM bookings WHERE id = ? AND customer_id = ?", 'ii', [$bookingId, $userId]);

if (!$booking) {
    jsonError('Booking not found or access denied.', 404);
}

if ($booking['payment_method'] === 'online') {
    jsonSuccess('Booking is already paid online and eligible for resale listing.');
}

// Convert payment method to online (payment_converted = 1)
try {
    dbBeginTransaction();

    dbQuery("UPDATE bookings SET payment_method = 'online', payment_converted = 1 WHERE id = ?", 'i', [$bookingId]);

    writeAuditLog($userId, 'booking', $bookingId, 'converted_cash_to_online_for_resale');

    $orderId = generateOrderId('CP-CV', $bookingId);
    $amount = (float)$booking['amount'];

    dbQuery(
        "INSERT INTO payments (booking_id, payhere_order_id, amount, status) VALUES (?, ?, ?, 'pending')",
        'isd',
        [$bookingId, $orderId, $amount]
    );

    dbCommit();

    jsonSuccess('Booking converted to online payment. Please complete PayHere Sandbox payment to unlock resale listing.', [
        'booking_id' => $bookingId,
        'converted' => true
    ]);

} catch (Exception $e) {
    dbRollback();
    error_log('Conversion error: ' . $e->getMessage());
    jsonError('Failed to convert payment method.');
}
