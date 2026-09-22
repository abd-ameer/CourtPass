<?php
/**
 * CourtPass — API: Create Flash Slot Endpoint (Owner only)
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

$courtId = (int)($_POST['court_id'] ?? 0);
$slotDate = trim($_POST['slot_date'] ?? '');
$slotStart = trim($_POST['slot_start'] ?? '');
$discountedPrice = (float)($_POST['discounted_price'] ?? 0);
$userId = currentUserId();

if (!$courtId || !$slotDate || !$slotStart || $discountedPrice <= 0) {
    jsonError('Court ID, slot date, slot start, and valid discounted price are required.');
}

// Verify court ownership
$court = dbFetchOne(
    "SELECT c.*, v.owner_id 
     FROM courts c 
     JOIN venues v ON c.venue_id = v.id 
     WHERE c.id = ? AND v.owner_id = ?",
    'ii',
    [$courtId, $userId]
);

if (!$court) {
    jsonError('Court not found or access denied.', 403);
}

$originalPrice = (float)$court['hourly_rate'];
if ($discountedPrice >= $originalPrice) {
    jsonError("Discounted price (Rs. {$discountedPrice}) must be lower than the standard hourly rate (Rs. {$originalPrice}).");
}

// Ensure slot is unbooked and in the future
$slotEnd = (new DateTime("{$slotDate} {$slotStart}"))->modify('+1 hour')->format('H:i:s');

if (isSlotPast($slotDate, $slotStart)) {
    jsonError('Cannot create a Flash Deal for a past slot.');
}

$isBooked = dbFetchOne(
    "SELECT id FROM bookings WHERE court_id = ? AND slot_date = ? AND slot_start = ? AND status IN ('pending', 'confirmed')",
    'iss',
    [$courtId, $slotDate, $slotStart]
);

if ($isBooked) {
    jsonError('Cannot create a Flash Deal for an already booked slot.');
}

try {
    dbQuery(
        "INSERT INTO flash_slots (court_id, venue_id, slot_date, slot_start, slot_end, original_price, discounted_price, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, 'active')",
        'iisssdd',
        [$courtId, $court['venue_id'], $slotDate, $slotStart, $slotEnd, $originalPrice, $discountedPrice]
    );

    $flashId = dbLastInsertId();

    writeAuditLog($userId, 'flash_slot', $flashId, 'flash_deal_created', [
        'court_id' => $courtId,
        'slot_date' => $slotDate,
        'original_price' => $originalPrice,
        'discounted_price' => $discountedPrice
    ]);

    jsonSuccess('Flash Deal created successfully!', ['flash_id' => $flashId]);

} catch (Exception $e) {
    error_log('Flash deal creation error: ' . $e->getMessage());
    jsonError('Failed to create Flash Deal.');
}
