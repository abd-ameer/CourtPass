<?php
/**
 * CourtPass — API: Most Active This Month Leaderboard Endpoint
 * Per-venue top 5 customers by verified attendance.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

$venueId = (int)getVal('venue_id');

$whereVenue = $venueId ? "AND c.venue_id = {$venueId}" : "";

$leaderboard = dbFetchAll(
    "SELECT u.name as customer_name, v.name as venue_name, COUNT(*) as completed_count
     FROM bookings b
     JOIN courts c ON b.court_id = c.id
     JOIN venues v ON c.venue_id = v.id
     JOIN users u ON b.customer_id = u.id
     WHERE b.status = 'completed' 
       AND MONTH(b.slot_date) = MONTH(NOW()) 
       AND YEAR(b.slot_date) = YEAR(NOW())
       {$whereVenue}
     GROUP BY b.customer_id, c.venue_id
     ORDER BY completed_count DESC
     LIMIT 5"
);

jsonResponse(['success' => true, 'leaderboard' => $leaderboard]);
