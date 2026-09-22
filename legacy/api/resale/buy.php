<?php
/**
 * CourtPass — API: Buy Resale Listing Endpoint (PayHere Sandbox)
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

$resaleId = (int)($_POST['resale_id'] ?? 0);
$buyerId = currentUserId();

if (!$resaleId) {
    jsonError('Resale listing ID is required.');
}

$listing = dbFetchOne("SELECT * FROM resale_listings WHERE id = ? AND status = 'active'", 'i', [$resaleId]);

if (!$listing) {
    jsonError('Listing not found or no longer active.', 404);
}

if ($listing['seller_id'] === $buyerId) {
    jsonError('You cannot purchase your own resale listing.');
}

$amount = (float)$listing['listing_price'];

try {
    dbBeginTransaction();

    // Lock listing for buyer
    dbQuery("UPDATE resale_listings SET buyer_id = ? WHERE id = ? AND status = 'active'", 'ii', [$buyerId, $resaleId]);

    $orderId = generateOrderId('CP-RS', $resaleId);

    dbQuery(
        "INSERT INTO payments (resale_listing_id, payhere_order_id, amount, status) VALUES (?, ?, ?, 'pending')",
        'isd',
        [$resaleId, $orderId, $amount]
    );

    dbCommit();

    // Generate PayHere Checkout data
    $merchantSecret = PAYHERE_MERCHANT_SECRET;
    $merchantId = PAYHERE_MERCHANT_ID;
    $currency = 'LKR';
    $formattedAmount = number_format($amount, 2, '.', '');
    
    $hash = strtoupper(
        md5(
            $merchantId . 
            $orderId . 
            $formattedAmount . 
            $currency . 
            strtoupper(md5($merchantSecret))
        )
    );

    $customer = getCurrentUser();

    jsonSuccess('Initiating PayHere checkout for resale purchase...', [
        'payhere_data' => [
            'sandbox_url' => PAYHERE_SANDBOX_URL,
            'merchant_id' => $merchantId,
            'return_url' => BASE_URL . '/payment/return?resale_id=' . $resaleId,
            'cancel_url' => BASE_URL . '/payment/cancel',
            'notify_url' => BASE_URL . '/api/payments/notify',
            'order_id' => $orderId,
            'items' => "Resale Purchase #{$resaleId}",
            'currency' => $currency,
            'amount' => $formattedAmount,
            'first_name' => $customer['name'],
            'last_name' => '',
            'email' => $customer['email'],
            'phone' => $customer['phone'] ?? '',
            'address' => 'Colombo, Sri Lanka',
            'city' => 'Colombo',
            'country' => 'Sri Lanka',
            'hash' => $hash
        ]
    ]);

} catch (Exception $e) {
    dbRollback();
    error_log('Resale purchase init error: ' . $e->getMessage());
    jsonError('Failed to initiate resale purchase.');
}
