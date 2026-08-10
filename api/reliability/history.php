<?php
/**
 * CourtPass — API: Reliability Score & History Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/score.php';

requireLogin(true);

$userId = currentUserId();
$role = currentUserRole();

$targetId = $userId;

// Admin or Owner checking a customer
if (isset($_GET['customer_id']) && ($role === 'admin' || $role === 'owner')) {
    $targetId = (int)$_GET['customer_id'];
}

$info = getCustomerReliabilityInfo($targetId);

$history = dbFetchAll(
    "SELECT rh.*, b.slot_date, b.slot_start 
     FROM reliability_history rh 
     LEFT JOIN bookings b ON rh.related_booking_id = b.id 
     WHERE rh.customer_id = ? 
     ORDER BY rh.created_at DESC 
     LIMIT 20",
    'i',
    [$targetId]
);

jsonResponse([
    'success' => true,
    'reliability' => $info,
    'history' => $history
]);
