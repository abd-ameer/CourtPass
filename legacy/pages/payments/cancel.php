<?php
/**
 * CourtPass — Payment Cancelled Page
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle = 'Payment Cancelled';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container container--narrow" style="padding: 4rem 0; text-align: center;">
    <div class="card" style="padding: 3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">⚠️</div>
        <h1 style="color: var(--warning-600); margin-bottom: 0.5rem;">Payment Cancelled</h1>
        <p class="text-secondary" style="margin-bottom: 2rem;">
            Your payment session was cancelled or not completed. No charges were made.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="<?= BASE_URL ?>/venues" class="btn btn--primary btn--lg">Try Booking Again</a>
            <a href="<?= BASE_URL ?>" class="btn btn--outline btn--lg">Go Home</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
