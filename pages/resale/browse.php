<?php
/**
 * CourtPass — Public Resale Marketplace Page
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle = 'Resale Marketplace';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-header__title">🔄 Resale Marketplace</h1>
        <p style="opacity: 0.8; max-width: 600px;">
            Buy confirmed court bookings listed by other players at discounted prices. 
            All listing prices are server-capped at a maximum of 90% of original booking cost!
        </p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <div id="listingsContainer">
        <div class="spinner" style="margin: 3rem auto;"></div>
    </div>
</div>

<form id="payhereResaleForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="merchant_id" id="rs_merchant_id">
    <input type="hidden" name="return_url" id="rs_return_url">
    <input type="hidden" name="cancel_url" id="rs_cancel_url">
    <input type="hidden" name="notify_url" id="rs_notify_url">
    <input type="hidden" name="order_id" id="rs_order_id">
    <input type="hidden" name="items" id="rs_items">
    <input type="hidden" name="currency" id="rs_currency">
    <input type="hidden" name="amount" id="rs_amount">
    <input type="hidden" name="first_name" id="rs_first_name">
    <input type="hidden" name="last_name" id="rs_last_name">
    <input type="hidden" name="email" id="rs_email">
    <input type="hidden" name="phone" id="rs_phone">
    <input type="hidden" name="address" id="rs_address">
    <input type="hidden" name="city" id="rs_city">
    <input type="hidden" name="country" id="rs_country">
    <input type="hidden" name="hash" id="rs_hash">
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('listingsContainer');

    async function loadResaleListings() {
        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/resale/browse');
            
            if (!data.listings || data.listings.length === 0) {
                container.innerHTML = `
                    <div class="card empty-state">
                        <div class="empty-state__icon">🔄</div>
                        <h3 class="empty-state__title">No active resale listings</h3>
                        <p class="empty-state__text">Check back later or browse standard venue court slots.</p>
                        <a href="<?= BASE_URL ?>/venues" class="btn btn--primary btn--lg">Browse Venues</a>
                    </div>
                `;
                return;
            }

            let html = '<div class="grid grid--3">';
            data.listings.forEach(item => {
                const discountPct = Math.round((1 - item.listing_price / item.original_price) * 100);
                html += `
                    <div class="card">
                        <div class="card__body">
                            <div class="badge badge--warning mb-2">Resale · ${discountPct}% OFF</div>
                            <h3 class="card__title">${escapeHtml(item.court_name)}</h3>
                            <p class="card__subtitle">
                                📍 ${escapeHtml(item.venue_name)} (${escapeHtml(item.city)})
                            </p>
                            <p class="text-sm">
                                📅 <strong>${item.slot_date}</strong><br>
                                🕐 ${item.slot_start} – ${item.slot_end}
                            </p>
                            <p class="text-xs text-muted mt-2">Seller: ${escapeHtml(item.seller_name)}</p>
                        </div>
                        <div class="card__footer">
                            <div>
                                <div class="card__price">Rs. ${parseFloat(item.listing_price).toFixed(2)}</div>
                                <div class="text-xs text-muted"><del>Rs. ${parseFloat(item.original_price).toFixed(2)}</del></div>
                            </div>
                            ${<?= isLoggedIn() ? 'true' : 'false' ?> ? 
                                `<button class="btn btn--primary btn--sm buy-resale-btn" data-id="${item.id}">Buy Now</button>` :
                                `<a href="<?= BASE_URL ?>/login" class="btn btn--primary btn--sm">Log in to Buy</a>`
                            }
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            container.innerHTML = html;

            document.querySelectorAll('.buy-resale-btn').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    btn.disabled = true;
                    btn.textContent = 'Processing...';

                    try {
                        const formData = new FormData();
                        formData.append('resale_id', id);
                        formData.append('csrf_token', getCsrfToken());

                        const res = await fetchApi('<?= BASE_URL ?>/api/resale/buy', { method: 'POST', body: formData });
                        
                        if (res.payhere_data) {
                            const p = res.payhere_data;
                            const form = document.getElementById('payhereResaleForm');
                            form.action = p.sandbox_url;
                            document.getElementById('rs_merchant_id').value = p.merchant_id;
                            document.getElementById('rs_return_url').value = p.return_url;
                            document.getElementById('rs_cancel_url').value = p.cancel_url;
                            document.getElementById('rs_notify_url').value = p.notify_url;
                            document.getElementById('rs_order_id').value = p.order_id;
                            document.getElementById('rs_items').value = p.items;
                            document.getElementById('rs_currency').value = p.currency;
                            document.getElementById('rs_amount').value = p.amount;
                            document.getElementById('rs_first_name').value = p.first_name;
                            document.getElementById('rs_last_name').value = p.last_name;
                            document.getElementById('rs_email').value = p.email;
                            document.getElementById('rs_phone').value = p.phone;
                            document.getElementById('rs_address').value = p.address;
                            document.getElementById('rs_city').value = p.city;
                            document.getElementById('rs_country').value = p.country;
                            document.getElementById('rs_hash').value = p.hash;

                            showToast('Redirecting to PayHere Sandbox...', 'info');
                            setTimeout(() => form.submit(), 600);
                        }
                    } catch (err) {
                        showToast(err.message, 'error');
                        btn.disabled = false;
                        btn.textContent = 'Buy Now';
                    }
                });
            });

        } catch (err) {
            container.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
        }
    }

    loadResaleListings();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
