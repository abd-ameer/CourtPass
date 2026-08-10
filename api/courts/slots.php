<?php
/**
 * CourtPass — API: On-the-Fly Slot Generation Endpoint
 * Generates 1-hour slots from operating hours for a specific court and date,
 * checking against existing bookings, coach sessions, and flash deals.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

$courtId = (int)getVal('court_id');
$dateStr = getVal('date'); // Y-m-d

if (!$courtId || !$dateStr) {
    jsonError('court_id and date (Y-m-d) are required.');
}

// Validate date format
$dt = DateTime::createFromFormat('Y-m-d', $dateStr, new DateTimeZone('Asia/Colombo'));
if (!$dt || $dt->format('Y-m-d') !== $dateStr) {
    jsonError('Invalid date format. Use Y-m-d.');
}

// Get court info
$court = dbFetchOne("SELECT * FROM courts WHERE id = ? AND status = 'active'", 'i', [$courtId]);
if (!$court) {
    jsonError('Court not found or inactive.', 404);
}

// Get day of week (0=Sunday ... 6=Saturday)
$dayOfWeek = (int)$dt->format('w');

// Fetch operating hours for this court on this day
$opHours = dbFetchOne(
    "SELECT open_time, close_time FROM operating_hours WHERE court_id = ? AND day_of_week = ?",
    'ii',
    [$courtId, $dayOfWeek]
);

if (!$opHours) {
    jsonResponse(['success' => true, 'court' => $court, 'date' => $dateStr, 'slots' => []]);
}

$open = new DateTime("{$dateStr} {$opHours['open_time']}", new DateTimeZone('Asia/Colombo'));
$close = new DateTime("{$dateStr} {$opHours['close_time']}", new DateTimeZone('Asia/Colombo'));

// Fetch existing active bookings for this court and date
$existingBookings = dbFetchAll(
    "SELECT id, slot_start, slot_end, status 
     FROM bookings 
     WHERE court_id = ? AND slot_date = ? AND status IN ('pending', 'confirmed')",
    'is',
    [$courtId, $dateStr]
);

// Fetch existing coach sessions for this court and date
$existingSessions = dbFetchAll(
    "SELECT id, session_start, session_end, title, status 
     FROM coach_sessions 
     WHERE court_id = ? AND session_date = ? AND status IN ('open', 'full')",
    'is',
    [$courtId, $dateStr]
);

// Fetch flash slots for this court and date
$flashSlots = dbFetchAll(
    "SELECT id, slot_start, discounted_price, status 
     FROM flash_slots 
     WHERE court_id = ? AND slot_date = ? AND status = 'active'",
    'is',
    [$courtId, $dateStr]
);

$flashMap = [];
foreach ($flashSlots as $fs) {
    $flashMap[$fs['slot_start']] = $fs;
}

$slots = [];
$now = new DateTime('now', new DateTimeZone('Asia/Colombo'));

$currentSlotStart = clone $open;
while ($currentSlotStart < $close) {
    $currentSlotEnd = (clone $currentSlotStart)->modify('+1 hour');
    if ($currentSlotEnd > $close) {
        break; // Fixed 1-hour slots only
    }

    $startTimeStr = $currentSlotStart->format('H:i:s');
    $endTimeStr = $currentSlotEnd->format('H:i:s');

    $isPast = ($currentSlotStart < $now);
    $isBooked = false;
    $bookingId = null;
    $sessionTitle = null;

    // Check conflict with bookings
    foreach ($existingBookings as $b) {
        if ($b['slot_start'] === $startTimeStr) {
            $isBooked = true;
            $bookingId = $b['id'];
            break;
        }
    }

    // Check conflict with coach sessions
    if (!$isBooked) {
        foreach ($existingSessions as $s) {
            if ($s['session_start'] === $startTimeStr) {
                $isBooked = true;
                $sessionTitle = $s['title'];
                break;
            }
        }
    }

    $isFlash = isset($flashMap[$startTimeStr]);
    $price = $isFlash ? (float)$flashMap[$startTimeStr]['discounted_price'] : (float)$court['hourly_rate'];

    $slots[] = [
        'slot_start' => $startTimeStr,
        'slot_end' => $endTimeStr,
        'display_start' => $currentSlotStart->format('g:i A'),
        'display_end' => $currentSlotEnd->format('g:i A'),
        'price' => $price,
        'original_price' => (float)$court['hourly_rate'],
        'is_available' => (!$isPast && !$isBooked),
        'is_past' => $isPast,
        'is_booked' => $isBooked,
        'is_flash' => $isFlash,
        'flash_id' => $isFlash ? $flashMap[$startTimeStr]['id'] : null,
        'booking_id' => $bookingId,
        'session_title' => $sessionTitle
    ];

    $currentSlotStart->modify('+1 hour');
}

jsonResponse([
    'success' => true,
    'court' => $court,
    'date' => $dateStr,
    'slots' => $slots
]);
