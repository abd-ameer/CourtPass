<?php
/**
 * CourtPass — API: Sports Diary Aggregation Endpoint
 * All data derived/aggregated from booking + check-in data, not manually entered.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin(true);
requireRole('customer', true);

$userId = currentUserId();

// Member since date
$user = getCurrentUser();

// Total sessions played (completed bookings + attended coach sessions)
$courtSessionsCount = dbFetchOne("SELECT COUNT(*) as cnt FROM bookings WHERE customer_id = ? AND status = 'completed'", 'i', [$userId]);
$coachSessionsCount = dbFetchOne("SELECT COUNT(*) as cnt FROM session_registrations WHERE customer_id = ? AND attendance = 'attended'", 'i', [$userId]);

$totalSessions = (int)($courtSessionsCount['cnt'] ?? 0) + (int)($coachSessionsCount['cnt'] ?? 0);

// Unique venues visited
$venuesVisited = dbFetchOne(
    "SELECT COUNT(DISTINCT c.venue_id) as cnt 
     FROM bookings b 
     JOIN courts c ON b.court_id = c.id 
     WHERE b.customer_id = ? AND b.status = 'completed'",
    'i',
    [$userId]
);

// Sport-type breakdown
$sportBreakdown = dbFetchAll(
    "SELECT c.sport_type, COUNT(*) as session_count 
     FROM bookings b 
     JOIN courts c ON b.court_id = c.id 
     WHERE b.customer_id = ? AND b.status = 'completed' 
     GROUP BY c.sport_type 
     ORDER BY session_count DESC",
    'i',
    [$userId]
);

// Most active month
$mostActiveMonth = dbFetchOne(
    "SELECT DATE_FORMAT(slot_date, '%M %Y') as month_label, COUNT(*) as cnt 
     FROM bookings 
     WHERE customer_id = ? AND status = 'completed' 
     GROUP BY month_label 
     ORDER BY cnt DESC 
     LIMIT 1",
    'i',
    [$userId]
);

jsonResponse([
    'success' => true,
    'diary' => [
        'member_since' => formatDate($user['created_at']),
        'total_sessions_played' => $totalSessions,
        'venues_visited' => (int)($venuesVisited['cnt'] ?? 0),
        'most_active_month' => $mostActiveMonth ? $mostActiveMonth['month_label'] : 'N/A',
        'sport_breakdown' => $sportBreakdown
    ]
]);
