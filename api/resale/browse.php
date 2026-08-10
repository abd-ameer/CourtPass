<?php
/**
 * CourtPass — API: Browse & Revoke Resale Listings
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/audit.php';

// Auto-expire unclaimed resale listings where slot start time has passed (read-time check)
dbQuery(
    "UPDATE resale_listings rl
     JOIN bookings b ON rl.booking_id = b.id
     SET rl.status = 'expired'
     WHERE rl.status = 'active' AND CONCAT(b.slot_date, ' ', b.slot_start) <= NOW()"
);

$action = getVal('action', 'browse');

if ($action === 'revoke') {
    requireLogin(true);
    requireCsrf(true);

    $resaleId = (int)($_POST['resale_id'] ?? 0);
    $userId = currentUserId();

    $listing = dbFetchOne("SELECT * FROM resale_listings WHERE id = ? AND seller_id = ?", 'ii', [$resaleId, $userId]);
    if (!$listing || $listing['status'] !== 'active') {
        jsonError('Listing not found or cannot be revoked.');
    }

    dbQuery("UPDATE resale_listings SET status = 'revoked' WHERE id = ?", 'i', [$resaleId]);
    writeAuditLog($userId, 'resale', $resaleId, 'resale_revoked');

    jsonSuccess('Resale listing revoked. Your booking remains confirmed.');
    exit;
}

// Default action: Browse active listings
$listings = dbFetchAll(
    "SELECT rl.*, b.slot_date, b.slot_start, b.slot_end, b.amount as original_price,
            c.name as court_name, c.sport_type, v.name as venue_name, v.city,
            u.name as seller_name
     FROM resale_listings rl
     JOIN bookings b ON rl.booking_id = b.id
     JOIN courts c ON b.court_id = c.id
     JOIN venues v ON c.venue_id = v.id
     JOIN users u ON rl.seller_id = u.id
     WHERE rl.status = 'active' 
       AND CONCAT(b.slot_date, ' ', b.slot_start) > NOW()
     ORDER BY b.slot_date ASC, b.slot_start ASC"
);

jsonResponse(['success' => true, 'listings' => $listings]);
