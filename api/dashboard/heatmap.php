<?php
/**
 * CourtPass — API: Owner Dashboard Heatmap Data Endpoint
 * Generates court utilization heatmap data formatted for Chart.js (sanctioned JS library).
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin(true);
requireRole('owner', true);

$ownerId = currentUserId();

// Fetch hourly court utilization breakdown (by day of week and hour of day)
$utilization = dbFetchAll(
    "SELECT DAYNAME(b.slot_date) as day_name, HOUR(b.slot_start) as slot_hour, COUNT(*) as booking_count
     FROM bookings b
     JOIN courts c ON b.court_id = c.id
     JOIN venues v ON c.venue_id = v.id
     WHERE v.owner_id = ? AND b.status IN ('confirmed', 'completed')
     GROUP BY day_name, slot_hour
     ORDER BY FIELD(day_name, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), slot_hour",
    'i',
    [$ownerId]
);

jsonResponse(['success' => true, 'heatmap' => $utilization]);
