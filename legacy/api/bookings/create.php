<?php
/**
 * CourtPass — API: Booking Creation Endpoint
 * Database-level conflict prevention via SELECT FOR UPDATE in transaction + UNIQUE constraint.
 * Verifies customer reliability tier for Cash-on-Arrival eligibility.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../reliability/score.php';

requireLogin(true);
requireRole('customer', true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Method not allowed', 405);
}

requireCsrf(true);

$customerId = currentUserId();
$courtId = (int)($_POST['court_id'] ?? 0);
$slotDate = trim($_POST['slot_date'] ?? '');
$slotStart = trim($_POST['slot_start'] ?? '');
$paymentMethod = $_POST['payment_method'] ?? 'online'; // 'online' or 'cash_on_arrival'

if (!$courtId || !$slotDate || !$slotStart) {
    jsonError('Court, slot date, and slot start time are required.');
}

// Validate date format Y-m-d
$dt = DateTime::createFromFormat('Y-m-d H:i:s', "{$slotDate} {$slotStart}", new DateTimeZone('Asia/Colombo'));
if (!$dt) {
    jsonError('Invalid date/time parameters.');
}

// Ensure slot is in the future
if ($dt < new DateTime('now', new DateTimeZone('Asia/Colombo'))) {
    jsonError('Cannot book slots in the past.');
}

// Slot end is fixed at 1 hour later
$slotEnd = (clone $dt)->modify('+1 hour')->format('H:i:s');

// Get court details
$court = dbFetchOne("SELECT c.*, v.status as venue_status FROM courts c JOIN venues v ON c.venue_id = v.id WHERE c.id = ?", 'i', [$courtId]);

if (!$court || $court['status'] !== 'active' || $court['venue_status'] !== 'approved') {
    jsonError('This court is unavailable for booking.');
}

// Check Cash-on-Arrival eligibility if requested
if ($paymentMethod === 'cash_on_arrival') {
    $reliability = getCustomerReliabilityInfo($customerId);
    if (!$reliability['cash_on_arrival_eligible']) {
        jsonError('Cash on Arrival is only available for Standard+ members (5+ completed bookings and 70%+ score). Please select Online Payment.');
    }
}

// Calculate booking amount (check if flash slot active)
$flash = dbFetchOne(
    "SELECT id, discounted_price FROM flash_slots WHERE court_id = ? AND slot_date = ? AND slot_start = ? AND status = 'active'",
    'iss',
    [$courtId, $slotDate, $slotStart]
);

$amount = $flash ? (float)$flash['discounted_price'] : (float)$court['hourly_rate'];

try {
    dbBeginTransaction();

    // Database-level concurrency lock on court slot
    // SELECT ... FOR UPDATE to lock existing bookings for this court/date
    $lockedBookings = dbFetchAll(
        "SELECT id FROM bookings WHERE court_id = ? AND slot_date = ? AND slot_start = ? AND status IN ('pending', 'confirmed') FOR UPDATE",
        'iss',
        [$courtId, $slotDate, $slotStart]
    );

    if (!empty($lockedBookings)) {
        dbRollback();
        jsonError('This slot was just booked by another user. Please choose a different slot.');
    }

    // Check conflict with coach sessions
    $lockedSessions = dbFetchAll(
        "SELECT id FROM coach_sessions WHERE court_id = ? AND session_date = ? AND session_start = ? AND status IN ('open', 'full') FOR UPDATE",
        'iss',
        [$courtId, $slotDate, $slotStart]
    );

    if (!empty($lockedSessions)) {
        dbRollback();
        jsonError('This slot is reserved for a coach training session.');
    }

    // Create booking
    // Status is 'pending' for online payment (awaiting PayHere confirmation or owner confirmation),
    // or 'confirmed' directly for cash_on_arrival (or 'pending' owner confirmation).
    $status = ($paymentMethod === 'cash_on_arrival') ? 'confirmed' : 'pending';

    dbQuery(
        "INSERT INTO bookings (court_id, customer_id, slot_date, slot_start, slot_end, amount, payment_method, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        'iisssdss',
        [$courtId, $customerId, $slotDate, $slotStart, $slotEnd, $amount, $paymentMethod, $status]
    );

    $bookingId = dbLastInsertId();

    // If flash slot, mark booked
    if ($flash) {
        dbQuery("UPDATE flash_slots SET status = 'booked' WHERE id = ?", 'i', [$flash['id']]);
    }

    // Audit log
    writeAuditLog($customerId, 'booking', $bookingId, 'booking_created', [
        'court_id' => $courtId,
        'slot_date' => $slotDate,
        'slot_start' => $slotStart,
        'amount' => $amount,
        'payment_method' => $paymentMethod,
        'status' => $status
    ]);

    // Create payment record
    $orderId = generateOrderId('CP-BK', $bookingId);
    dbQuery(
        "INSERT INTO payments (booking_id, payhere_order_id, amount, status) VALUES (?, ?, ?, 'pending')",
        'isd',
        [$bookingId, $orderId, $amount]
    );

    dbCommit();

    // Generate PayHere Checkout parameters for online payment
    $payhereData = null;
    if ($paymentMethod === 'online') {
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

        $payhereData = [
            'sandbox_url' => PAYHERE_SANDBOX_URL,
            'merchant_id' => $merchantId,
            'return_url' => BASE_URL . '/payment/return?booking_id=' . $bookingId,
            'cancel_url' => BASE_URL . '/payment/cancel?booking_id=' . $bookingId,
            'notify_url' => BASE_URL . '/api/payments/notify',
            'order_id' => $orderId,
            'items' => "CourtPass Booking #{$bookingId}",
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
        ];
    }

    jsonSuccess(
        $paymentMethod === 'cash_on_arrival' 
            ? 'Booking confirmed with Cash on Arrival!' 
            : 'Booking initiated. Redirecting to PayHere Sandbox...',
        [
            'booking_id' => $bookingId,
            'payment_method' => $paymentMethod,
            'payhere_data' => $payhereData
        ]
    );

} catch (Exception $e) {
    dbRollback();
    error_log('Booking creation failed: ' . $e->getMessage());
    jsonError('Booking failed due to double-booking constraint or server error.');
}
