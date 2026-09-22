<?php
/**
 * CourtPass — API: Helper for Atomic Ownership Transfer on Successful Resale Purchase
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/audit.php';
require_once __DIR__ . '/../../includes/notifications.php';

/**
 * Atomically transfer booking ownership to buyer upon payment completion.
 *
 * @param int $resaleId
 * @param string|null $payherePaymentId
 */
function completeResalePurchase(int $resaleId, ?string $payherePaymentId = null): void {
    $listing = dbFetchOne("SELECT * FROM resale_listings WHERE id = ?", 'i', [$resaleId]);
    if (!$listing || $listing['status'] !== 'active') {
        return;
    }

    $buyerId = $listing['buyer_id'];
    $sellerId = $listing['seller_id'];
    $bookingId = $listing['booking_id'];

    // Atomic Ownership Transfer
    dbQuery("UPDATE bookings SET customer_id = ? WHERE id = ?", 'ii', [$buyerId, $bookingId]);
    dbQuery("UPDATE resale_listings SET status = 'sold', sold_at = NOW() WHERE id = ?", 'i', [$resaleId]);

    // Audit log
    writeAuditLog($buyerId, 'resale', $resaleId, 'resale_purchased', [
        'booking_id' => $bookingId,
        'seller_id' => $sellerId,
        'buyer_id' => $buyerId,
        'price' => $listing['listing_price']
    ]);

    // Notifications
    createNotification(
        $sellerId,
        'resale_sold',
        'Resale Listing Sold! 💰',
        "Your court booking #{$bookingId} listing was purchased. Funds processed.",
        $resaleId,
        'resale'
    );

    createNotification(
        $buyerId,
        'resale_purchased',
        'Resale Booking Transfer Complete! 🎉',
        "You are now the owner of booking #{$bookingId}.",
        $bookingId,
        'booking'
    );
}
