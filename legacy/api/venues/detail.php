<?php
/**
 * CourtPass — API: Venue Detail Endpoint
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$venueId = (int)getVal('id');

if (!$venueId) {
    jsonError('Venue ID is required.');
}

$venue = dbFetchOne(
    "SELECT v.*, u.name as owner_name, u.phone as owner_phone,
            (SELECT ROUND(AVG(r.rating), 1) FROM reviews r WHERE r.venue_id = v.id AND r.status = 'active') as avg_rating,
            (SELECT COUNT(*) FROM reviews r WHERE r.venue_id = v.id AND r.status = 'active') as review_count
     FROM venues v
     JOIN users u ON v.owner_id = u.id
     WHERE v.id = ?",
    'i',
    [$venueId]
);

if (!$venue) {
    jsonError('Venue not found.', 404);
}

// Fetch active courts with operating hours
$courts = dbFetchAll("SELECT * FROM courts WHERE venue_id = ? AND status = 'active'", 'i', [$venueId]);

foreach ($courts as &$court) {
    $court['operating_hours'] = dbFetchAll(
        "SELECT day_of_week, open_time, close_time FROM operating_hours WHERE court_id = ? ORDER BY day_of_week ASC",
        'i',
        [$court['id']]
    );
}
unset($court);

// Fetch active announcements
$announcements = dbFetchAll(
    "SELECT * FROM announcements WHERE venue_id = ? AND status = 'active' ORDER BY created_at DESC",
    'i',
    [$venueId]
);

// Fetch active reviews
$reviews = dbFetchAll(
    "SELECT r.*, u.name as customer_name 
     FROM reviews r 
     JOIN users u ON r.customer_id = u.id 
     WHERE r.venue_id = ? AND r.status = 'active' 
     ORDER BY r.created_at DESC LIMIT 20",
    'i',
    [$venueId]
);

// Fetch approved coaches at this venue
$coaches = dbFetchAll(
    "SELECT cp.*, u.name as coach_name, u.email as coach_email, u.phone as coach_phone
     FROM coach_venue_approvals cva
     JOIN coach_profiles cp ON cva.coach_id = cp.user_id
     JOIN users u ON cp.user_id = u.id
     WHERE cva.venue_id = ? AND cva.status = 'approved'",
    'i',
    [$venueId]
);

jsonResponse([
    'success' => true,
    'venue' => $venue,
    'courts' => $courts,
    'announcements' => $announcements,
    'reviews' => $reviews,
    'coaches' => $coaches
]);
