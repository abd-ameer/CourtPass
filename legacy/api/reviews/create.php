<?php
/**
 * CourtPass — API: Venue Reviews Endpoint
 * Verification gate: customer can review ONLY after a verified completed booking within 7 days.
 * One public owner response per review (second attempt rejected server-side).
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

$action = getVal('action', 'create');
$userId = currentUserId();

if ($action === 'create') {
    requireLogin(true);
    requireRole('customer', true);
    requireCsrf(true);

    $bookingId = (int)($_POST['booking_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if (!$bookingId || $rating < 1 || $rating > 5) {
        jsonError('Valid booking ID and rating (1-5) are required.');
    }

    $booking = dbFetchOne("SELECT b.*, c.venue_id FROM bookings b JOIN courts c ON b.court_id = c.id WHERE b.id = ? AND b.customer_id = ?", 'ii', [$bookingId, $userId]);

    if (!$booking) {
        jsonError('Booking not found or access denied.', 404);
    }

    // VERIFICATION GATE: Booking status must be 'completed'
    if ($booking['status'] !== 'completed') {
        jsonError('You can only review a venue after completing your booked session.');
    }

    // VERIFICATION GATE: Must be within 7 days of completion date
    $daysSince = (time() - strtotime($booking['slot_date'])) / (60 * 60 * 24);
    if ($daysSince > 7) {
        jsonError('Reviews must be submitted within 7 days of your session.');
    }

    // Check duplicate
    $existing = dbFetchOne("SELECT id FROM reviews WHERE booking_id = ?", 'i', [$bookingId]);
    if ($existing) {
        jsonError('You have already submitted a review for this booking.');
    }

    try {
        dbQuery(
            "INSERT INTO reviews (venue_id, customer_id, booking_id, rating, comment, status) VALUES (?, ?, ?, ?, ?, 'active')",
            'iiiis',
            [$booking['venue_id'], $userId, $bookingId, $rating, $comment]
        );

        $reviewId = dbLastInsertId();

        writeAuditLog($userId, 'review', $reviewId, 'venue_review_created', [
            'venue_id' => $booking['venue_id'],
            'rating' => $rating
        ]);

        jsonSuccess('Venue review submitted successfully!', ['review_id' => $reviewId]);

    } catch (Exception $e) {
        error_log('Venue review creation error: ' . $e->getMessage());
        jsonError('Failed to submit review.');
    }
    exit;
}

if ($action === 'respond') {
    requireLogin(true);
    requireRole('owner', true);
    requireCsrf(true);

    $reviewId = (int)($_POST['review_id'] ?? 0);
    $response = trim($_POST['response'] ?? '');

    if (!$reviewId || empty($response)) {
        jsonError('Review ID and response are required.');
    }

    $review = dbFetchOne("SELECT r.*, v.owner_id FROM reviews r JOIN venues v ON r.venue_id = v.id WHERE r.id = ?", 'i', [$reviewId]);

    if (!$review || $review['owner_id'] !== $userId) {
        jsonError('Review not found or access denied.', 403);
    }

    // SERVER-SIDE RULE: Second response attempt rejected
    if (!empty($review['owner_response'])) {
        jsonError('You have already responded to this review. Second response attempts are rejected.');
    }

    dbQuery("UPDATE reviews SET owner_response = ?, owner_response_at = NOW() WHERE id = ?", 'si', [$response, $reviewId]);

    writeAuditLog($userId, 'review', $reviewId, 'owner_responded_to_venue_review');

    jsonSuccess('Response posted successfully.');
    exit;
}
