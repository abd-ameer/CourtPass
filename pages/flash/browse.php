<?php
/**
 * CourtPass — Public Flash Deals Page
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle = 'Flash Deals';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-header__title">⚡ Available Now — Flash Deals</h1>
        <p style="opacity: 0.8; max-width: 600px;">
            Discounted upcoming slots released by venue owners. Grab them before they expire!
        </p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <div id="flashContainer">
        <div class="spinner" style="margin: 3rem auto;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('flashContainer');

    async function loadFlashDeals() {
        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/flash/browse');
            
            if (!data.flash_deals || data.flash_deals.length === 0) {
                container.innerHTML = `
                    <div class="card empty-state">
                        <div class="empty-state__icon">⚡</div>
                        <h3 class="empty-state__title">No active flash deals right now</h3>
                        <p class="empty-state__text">Check back soon or browse standard venue slots.</p>
                        <a href="<?= BASE_URL ?>/venues" class="btn btn--primary btn--lg">Browse Venues</a>
                    </div>
                `;
                return;
            }

            let html = '<div class="grid grid--4">';
            data.flash_deals.forEach(deal => {
                const discountPct = Math.round((1 - deal.discounted_price / deal.original_price) * 100);
                html += `
                    <div class="card">
                        <div class="flash-banner" style="margin: 1rem 1rem 0;">
                            ⚡ Flash Deal — ${discountPct}% OFF
                        </div>
                        <div class="card__body">
                            <h4 class="card__title">${escapeHtml(deal.court_name)}</h4>
                            <p class="card__subtitle">
                                ${escapeHtml(deal.venue_name)} (${escapeHtml(deal.city)})
                            </p>
                            <p class="text-sm">
                                📅 <strong>${deal.slot_date}</strong><br>
                                🕐 ${deal.slot_start} – ${deal.slot_end}
                            </p>
                        </div>
                        <div class="card__footer">
                            <div class="card__price">
                                Rs. ${parseFloat(deal.discounted_price).toFixed(2)}
                                <small><del>Rs. ${parseFloat(deal.original_price).toFixed(2)}</del></small>
                            </div>
                            <a href="<?= BASE_URL ?>/book?court=${deal.court_id}&date=${deal.slot_date}" class="btn btn--primary btn--sm">Book Now</a>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            container.innerHTML = html;
        } catch (err) {
            container.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
        }
    }

    loadFlashDeals();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
