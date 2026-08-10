<?php
/**
 * CourtPass — API: Coach Reviews Endpoint
 * Enforces attendance verification gate, Parent/Player tag, and max 1 public coach response.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

$action = getVal('action', 'list');
$userId = currentUserId();

// ─── Create Coach Review ─────────────────────────────────────
if ($action === 'create') {
    requireLogin(true);
    requireRole('customer', true);
    requireCsrf(true);

    $coachId = (int)($_POST['coach_id'] ?? 0);
    $sessionId = (int)($_POST['session_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $reviewerTag = $_POST['reviewer_tag'] ?? ''; // 'parent' or 'player'

    if (!$coachId || !$sessionId || $rating < 1 || $rating > 5 || !in_array($reviewerTag, ['parent', 'player'], true)) {
        jsonError('Please provide a valid rating (1-5), session, and select Parent or Player tag.');
    }

    // ATTENDANCE VERIFICATION GATE: Customer must have a confirmed registration for this session
    $registration = dbFetchOne(
        "SELECT id, attendance FROM session_registrations WHERE session_id = ? AND customer_id = ? AND payment_status = 'paid'",
        'ii',
        [$sessionId, $userId]
    );

    if (!$registration) {
        jsonError('Attendance verification failed. You can only review coaches for sessions you have attended.');
    }

    // Check duplicate review for same session
    $existing = dbFetchOne("SELECT id FROM coach_reviews WHERE session_id = ? AND customer_id = ?", 'ii', [$sessionId, $userId]);
    if ($existing) {
        jsonError('You have already submitted a review for this training session.');
    }

    try {
        dbQuery(
            "INSERT INTO coach_reviews (coach_id, customer_id, session_id, rating, comment, reviewer_tag, status)
             VALUES (?, ?, ?, ?, ?, ?, 'active')",
            'iiiiss',
            [$coachId, $userId, $sessionId, $rating, $comment, $reviewerTag]
        );

        $reviewId = dbLastInsertId();

        // Mandatory Notification Event:
        // Event: Review posted on coach's profile -> Coach
        $customerName = currentUserName();
        createNotification(
            $coachId,
            'coach_review_posted',
            'New Review Received! ⭐',
            "{$customerName} ({$reviewerTag}) left a {$rating}-star review on your profile.",
            $reviewId,
            'coach_review'
        );

        writeAuditLog($userId, 'coach_review', $reviewId, 'coach_review_posted', [
            'rating' => $rating,
            'tag' => $reviewerTag
        ]);

        jsonSuccess('Review submitted successfully!', ['review_id' => $reviewId]);

    } catch (Exception $e) {
        error_log('Coach review creation error: ' . $e->getMessage());
        jsonError('Failed to submit coach review.');
    }
    exit;
}

// ─── Coach Response (Single response limit) ──────────────────
if ($action === 'respond') {
    requireLogin(true);
    requireRole('coach', true);
    requireCsrf(true);

    $reviewId = (int)($_POST['review_id'] ?? 0);
    $response = trim($_POST['response'] ?? '');

    if (!$reviewId || empty($response)) {
        jsonError('Review ID and response message are required.');
    }

    $review = dbFetchOne("SELECT * FROM coach_reviews WHERE id = ? AND coach_id = ?", 'ii', [$reviewId, $userId]);

    if (!$review) {
        jsonError('Review not found or access denied.', 403);
    }

    // SERVER-SIDE RULE: Second response attempt rejected
    if (!empty($review['coach_response'])) {
        jsonError('You have already submitted a response to this review. Multiple responses are not permitted.');
    }

    dbQuery(
        "UPDATE coach_reviews SET coach_response = ?, coach_response_at = NOW() WHERE id = ?",
        'si',
        [$response, $reviewId]
    );

    writeAuditLog($userId, 'coach_review', $reviewId, 'coach_responded_to_review');

    jsonSuccess('Response submitted.');
    exit;
}

// ─── List Coach Reviews with Tag Aggregation ──────────────────
$coachId = (int)getVal('coach_id', $userId);

$reviews = dbFetchAll(
    "SELECT cr.*, u.name as customer_name 
     FROM coach_reviews cr
     JOIN users u ON cr.customer_id = u.id
     WHERE cr.coach_id = ? AND cr.status = 'active'
     ORDER BY cr.created_at DESC",
    'i',
    [$coachId]
);

$stats = dbFetchOne(
    "SELECT 
        ROUND(AVG(rating), 1) as overall_avg,
        COUNT(*) as total_count,
        ROUND(AVG(CASE WHEN reviewer_tag = 'parent' THEN rating ELSE NULL END), 1) as parent_avg,
        COUNT(CASE WHEN reviewer_tag = 'parent' THEN 1 ELSE NULL END) as parent_count,
        ROUND(AVG(CASE WHEN reviewer_tag = 'player' THEN rating ELSE NULL END), 1) as player_avg,
        COUNT(CASE WHEN reviewer_tag = 'player' THEN 1 ELSE NULL END) as player_count
     FROM coach_reviews
     WHERE coach_id = ? AND status = 'active'",
    'i',
    [$coachId]
);

jsonResponse([
    'success' => true,
    'stats' => [
        'overall_avg' => (float)($stats['overall_avg'] ?? 0),
        'total_count' => (int)($stats['total_count'] ?? 0),
        'parent_avg' => (float)($stats['parent_avg'] ?? 0),
        'parent_count' => (int)($stats['parent_count'] ?? 0),
        'player_avg' => (float)($stats['player_avg'] ?? 0),
        'player_count' => (int)($stats['player_count'] ?? 0)
    ],
    'reviews' => $reviews
]);
