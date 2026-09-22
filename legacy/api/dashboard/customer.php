<?php
/**
 * CourtPass — API: Customer Intelligence Profile Endpoint (Owner-facing)
 * Displays customer reliability score, venue-specific history, no-show count, and last visit date
 * when an owner reviews a booking request.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../reliability/score.php';

requireLogin(true);
requireRole('owner', true);

$customerId = (int)getVal('customer_id');
$venueId = (int)getVal('venue_id');

if (!$customerId) {
    jsonError('Customer ID is required.');
}

$reliability = getCustomerReliabilityInfo($customerId);
$customer = dbFetchOne("SELECT name, email, phone, created_at FROM users WHERE id = ?", 'i', [$customerId]);

// Venue-specific booking history for this customer at this owner's venue
$venueStats = dbFetchOne(
    "SELECT 
        COUNT(*) as total_bookings,
        SUM(CASE WHEN b.status = 'completed' THEN 1 ELSE 0 END) as completed_count,
        SUM(CASE WHEN b.status = 'no_show' THEN 1 ELSE 0 END) as noshow_count,
        MAX(b.slot_date) as last_visit_date
     FROM bookings b
     JOIN courts c ON b.court_id = c.id
     WHERE b.customer_id = ? AND c.venue_id = ?",
    'ii',
    [$customerId, $venueId]
);

jsonResponse([
    'success' => true,
    'customer' => $customer,
    'reliability' => $reliability,
    'venue_history' => [
        'total_bookings' => (int)($venueStats['total_bookings'] ?? 0),
        'completed_count' => (int)($venueStats['completed_count'] ?? 0),
        'noshow_count' => (int)($venueStats['noshow_count'] ?? 0),
        'last_visit' => $venueStats['last_visit_date'] ? formatDate($venueStats['last_visit_date']) : 'Never'
    ]
]);
