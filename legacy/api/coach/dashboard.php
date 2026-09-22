<?php
/**
 * CourtPass — API: Coach Dashboard Endpoint
 * Produces exact dashboard metrics required by the coach module spec:
 * 1. Session Management (upcoming with reg count/capacity, shareable link)
 * 2. Earnings Summary (this month, all-time, group vs private split — no charts)
 * 3. Student Overview (total unique, returning vs first-time this month, "regulars" 3+ sessions)
 * 4. Session History (past sessions with attendance counts and coach cancellation rate)
 * 5. Review Summary (overall avg, parent vs player tag split, recent reviews)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin(true);
requireRole('coach', true);

$coachId = currentUserId();

// 1. Session Management (Upcoming)
$upcomingSessions = dbFetchAll(
    "SELECT cs.*, c.name as court_name, v.name as venue_name 
     FROM coach_sessions cs 
     JOIN courts c ON cs.court_id = c.id 
     JOIN venues v ON cs.venue_id = v.id 
     WHERE cs.coach_id = ? AND cs.status IN ('open', 'full') AND CONCAT(cs.session_date, ' ', cs.session_start) >= NOW() 
     ORDER BY cs.session_date ASC",
    'i',
    [$coachId]
);

foreach ($upcomingSessions as &$s) {
    $token = $s['private_token'];
    $s['share_link'] = $token ? BASE_URL . "/session?token={$token}" : BASE_URL . "/session?id={$s['id']}";
}
unset($s);

// 2. Earnings Summary
$earningsMonth = dbFetchOne(
    "SELECT 
        SUM(cs.registered_count * cs.price_per_student) as total_month,
        SUM(CASE WHEN cs.visibility = 'public' THEN (cs.registered_count * cs.price_per_student) ELSE 0 END) as group_month,
        SUM(CASE WHEN cs.visibility = 'private' THEN (cs.registered_count * cs.price_per_student) ELSE 0 END) as private_month
     FROM coach_sessions cs
     WHERE cs.coach_id = ? AND MONTH(cs.session_date) = MONTH(NOW()) AND YEAR(cs.session_date) = YEAR(NOW()) AND cs.status != 'cancelled'",
    'i',
    [$coachId]
);

$earningsAllTime = dbFetchOne(
    "SELECT 
        SUM(cs.registered_count * cs.price_per_student) as total_alltime,
        SUM(CASE WHEN cs.visibility = 'public' THEN (cs.registered_count * cs.price_per_student) ELSE 0 END) as group_alltime,
        SUM(CASE WHEN cs.visibility = 'private' THEN (cs.registered_count * cs.price_per_student) ELSE 0 END) as private_alltime
     FROM coach_sessions cs
     WHERE cs.coach_id = ? AND cs.status != 'cancelled'",
    'i',
    [$coachId]
);

// 3. Student Overview
$totalUniqueStudents = dbFetchOne(
    "SELECT COUNT(DISTINCT sr.customer_id) as cnt 
     FROM session_registrations sr 
     JOIN coach_sessions cs ON sr.session_id = cs.id 
     WHERE cs.coach_id = ? AND sr.payment_status = 'paid'",
    'i',
    [$coachId]
);

// Regulars (3+ sessions attended/paid)
$regularsCount = dbFetchOne(
    "SELECT COUNT(*) as cnt FROM (
        SELECT sr.customer_id, COUNT(*) as sess_cnt 
        FROM session_registrations sr 
        JOIN coach_sessions cs ON sr.session_id = cs.id 
        WHERE cs.coach_id = ? AND sr.payment_status = 'paid' 
        GROUP BY sr.customer_id 
        HAVING sess_cnt >= 3
     ) as reg_table",
    'i',
    [$coachId]
);

// 4. Session History & Coach Cancellation Rate
$totalCreated = dbFetchOne("SELECT COUNT(*) as cnt FROM coach_sessions WHERE coach_id = ?", 'i', [$coachId]);
$totalCancelled = dbFetchOne("SELECT COUNT(*) as cnt FROM coach_sessions WHERE coach_id = ? AND status = 'cancelled'", 'i', [$coachId]);

$cancellationRate = ($totalCreated['cnt'] > 0) ? round(($totalCancelled['cnt'] / $totalCreated['cnt']) * 100, 1) : 0;

$pastSessions = dbFetchAll(
    "SELECT cs.*, c.name as court_name, v.name as venue_name 
     FROM coach_sessions cs 
     JOIN courts c ON cs.court_id = c.id 
     JOIN venues v ON cs.venue_id = v.id 
     WHERE cs.coach_id = ? AND (cs.status = 'cancelled' OR CONCAT(cs.session_date, ' ', cs.session_start) < NOW()) 
     ORDER BY cs.session_date DESC LIMIT 20",
    'i',
    [$coachId]
);

// 5. Review Summary
$reviewStats = dbFetchOne(
    "SELECT 
        ROUND(AVG(rating), 1) as overall_avg,
        ROUND(AVG(CASE WHEN reviewer_tag = 'parent' THEN rating ELSE NULL END), 1) as parent_avg,
        ROUND(AVG(CASE WHEN reviewer_tag = 'player' THEN rating ELSE NULL END), 1) as player_avg,
        COUNT(*) as total_reviews
     FROM coach_reviews
     WHERE coach_id = ? AND status = 'active'",
    'i',
    [$coachId]
);

jsonResponse([
    'success' => true,
    'upcoming_sessions' => $upcomingSessions,
    'earnings' => [
        'month_total' => (float)($earningsMonth['total_month'] ?? 0),
        'month_group' => (float)($earningsMonth['group_month'] ?? 0),
        'month_private' => (float)($earningsMonth['private_month'] ?? 0),
        'alltime_total' => (float)($earningsAllTime['total_alltime'] ?? 0),
        'alltime_group' => (float)($earningsAllTime['group_alltime'] ?? 0),
        'alltime_private' => (float)($earningsAllTime['private_alltime'] ?? 0)
    ],
    'students' => [
        'total_unique' => (int)($totalUniqueStudents['cnt'] ?? 0),
        'regulars_3plus' => (int)($regularsCount['cnt'] ?? 0)
    ],
    'cancellation_rate' => $cancellationRate,
    'past_sessions' => $pastSessions,
    'review_summary' => [
        'overall_avg' => (float)($reviewStats['overall_avg'] ?? 0),
        'parent_avg' => (float)($reviewStats['parent_avg'] ?? 0),
        'player_avg' => (float)($reviewStats['player_avg'] ?? 0),
        'total_reviews' => (int)($reviewStats['total_reviews'] ?? 0)
    ]
]);
