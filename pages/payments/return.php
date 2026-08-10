<?php
/**
 * CourtPass — Payment Return Page (User redirected here after PayHere payment)
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$bookingId = (int)getVal('booking_id');

if ($bookingId) {
    // Confirm booking status
    dbQuery("UPDATE bookings SET status = 'confirmed' WHERE id = ? AND status = 'pending'", 'i', [$bookingId]);
    dbQuery("UPDATE payments SET status = 'completed' WHERE booking_id = ? AND status = 'pending'", 'i', [$bookingId]);
}

$pageTitle = 'Payment Confirmation';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container container--narrow" style="padding: 4rem 0; text-align: center;">
    <div class="card" style="padding: 3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
        <h1 style="color: var(--primary-600); margin-bottom: 0.5rem;">Payment Successful!</h1>
        <p class="text-secondary" style="margin-bottom: 2rem;">
            Thank you for your payment. Your booking has been confirmed.
        </p>

        <?php if ($bookingId): ?>
            <div style="background: var(--neutral-50); padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; text-align: left;">
                <p><strong>Booking Reference:</strong> #<?= $bookingId ?></p>
                <p><strong>Status:</strong> <span class="badge badge--success">Confirmed</span></p>
            </div>
        <?php endif; ?>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="<?= BASE_URL ?>/my-bookings" class="btn btn--primary btn--lg">View My Bookings</a>
            <a href="<?= BASE_URL ?>/venues" class="btn btn--outline btn--lg">Browse More Venues</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
