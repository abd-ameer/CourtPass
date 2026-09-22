<?php
/**
 * CourtPass — API: PayHere Sandbox Payment Notification Callback (notify_url)
 * Called asynchronously by PayHere Sandbox server on transaction completion.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

$merchantId = $_POST['merchant_id'] ?? '';
$orderId = $_POST['order_id'] ?? '';
$payhereAmount = $_POST['payhere_amount'] ?? '';
$payhereCurrency = $_POST['payhere_currency'] ?? '';
$statusCode = $_POST['status_code'] ?? '';
$md5sig = $_POST['md5sig'] ?? '';
$paymentId = $_POST['payment_id'] ?? '';

$merchantSecret = PAYHERE_MERCHANT_SECRET;

// Calculate expected signature
$localMd5sig = strtoupper(
    md5(
        $merchantId . 
        $orderId . 
        $payhereAmount . 
        $payhereCurrency . 
        $statusCode . 
        strtoupper(md5($merchantSecret))
    )
);

// Verify signature (or accept in sandbox mode if merchant secret is template placeholder)
if ($md5sig !== $localMd5sig && PAYHERE_MERCHANT_SECRET !== 'SANDBOX_MERCHANT_SECRET') {
    error_log("PayHere signature mismatch! Received: {$md5sig}, Expected: {$localMd5sig}");
    http_response_code(400);
    die('Signature verification failed');
}

// Find payment record by order_id
$payment = dbFetchOne("SELECT * FROM payments WHERE payhere_order_id = ?", 's', [$orderId]);

if (!$payment) {
    error_log("PayHere order not found: {$orderId}");
    http_response_code(404);
    die('Order not found');
}

// Handle payment status code from PayHere
// 2 = Success, 0 = Pending, -1 = Canceled, -2 = Failed
if ($statusCode == 2) {
    // Payment Successful
    try {
        dbBeginTransaction();

        // Update payment table
        dbQuery(
            "UPDATE payments SET status = 'completed', payhere_payment_id = ?, payhere_response = ? WHERE id = ?",
            'ssi',
            [$paymentId, json_encode($_POST), $payment['id']]
        );

        // Update related entity
        if ($payment['booking_id']) {
            dbQuery("UPDATE bookings SET status = 'confirmed' WHERE id = ?", 'i', [$payment['booking_id']]);
            writeAuditLog(null, 'booking', $payment['booking_id'], 'payment_completed_payhere', ['order_id' => $orderId, 'amount' => $payhereAmount]);

            // Notify customer
            $b = dbFetchOne("SELECT customer_id, slot_date, slot_start FROM bookings WHERE id = ?", 'i', [$payment['booking_id']]);
            if ($b) {
                createNotification(
                    $b['customer_id'],
                    'payment_success',
                    'Payment Successful! ✅',
                    "Your payment of Rs. {$payhereAmount} for Booking #{$payment['booking_id']} was confirmed.",
                    $payment['booking_id'],
                    'booking'
                );
            }
        } elseif ($payment['resale_listing_id']) {
            // Resale purchase payment confirmed
            require_once __DIR__ . '/../resale/buy_process.php';
            completeResalePurchase($payment['resale_listing_id'], $paymentId);
        } elseif ($payment['coach_session_reg_id']) {
            // Coach session registration payment confirmed
            dbQuery("UPDATE session_registrations SET payment_status = 'paid' WHERE id = ?", 'i', [$payment['coach_session_reg_id']]);
        }

        dbCommit();
    } catch (Exception $e) {
        dbRollback();
        error_log('PayHere callback processing error: ' . $e->getMessage());
    }
} else {
    // Payment Failed or Canceled
    dbQuery(
        "UPDATE payments SET status = 'failed', payhere_response = ? WHERE id = ?",
        'si',
        [json_encode($_POST), $payment['id']]
    );
}

http_response_code(200);
echo 'OK';
