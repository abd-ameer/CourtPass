<?php
/**
 * CourtPass — Customer My Bookings Page
 * Manage, cancel, list for resale, or convert cash-on-arrival to online payment.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../api/reliability/score.php';

requireLogin();
requireRole('customer');

$userId = currentUserId();

// Fetch customer bookings with court and venue info
$bookings = dbFetchAll(
    "SELECT b.*, c.name as court_name, c.sport_type, v.name as venue_name, v.city,
            rl.id as resale_id, rl.status as resale_status, rl.listing_price
     FROM bookings b
     JOIN courts c ON b.court_id = c.id
     JOIN venues v ON c.venue_id = v.id
     LEFT JOIN resale_listings rl ON b.id = rl.booking_id AND rl.status = 'active'
     WHERE b.customer_id = ?
     ORDER BY b.slot_date DESC, b.slot_start DESC",
    'i',
    [$userId]
);

$reliability = getCustomerReliabilityInfo($userId);

$pageTitle = 'My Bookings';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>My Bookings</h1>
            <p class="text-secondary">View and manage your court reservations</p>
        </div>
        <div class="reliability <?= $reliability['tier'] === 'standard_plus' ? 'reliability--standard' : ($reliability['tier'] === 'restricted' ? 'reliability--restricted' : 'reliability--new') ?>">
            👤 Tier: <?= $reliability['display_tier'] ?> (Score: <?= $reliability['display_score'] ?>)
        </div>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="card empty-state">
            <div class="empty-state__icon">📅</div>
            <h3 class="empty-state__title">No bookings found</h3>
            <p class="empty-state__text">You haven't made any court reservations yet.</p>
            <a href="<?= BASE_URL ?>/venues" class="btn btn--primary btn--lg">Browse Venues & Book Now</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Venue & Court</th>
                        <th>Date & Time</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): 
                        $hoursLeft = hoursUntil($b['slot_date'], $b['slot_start']);
                        $isUpcoming = ($hoursLeft > 0 && in_array($b['status'], ['pending', 'confirmed']));
                    ?>
                        <tr>
                            <td><strong>#<?= $b['id'] ?></strong></td>
                            <td>
                                <div><strong><?= sanitize($b['venue_name']) ?></strong></div>
                                <div class="text-xs text-muted"><?= sportIcon($b['sport_type']) ?> <?= sanitize($b['court_name']) ?> (<?= sanitize($b['city']) ?>)</div>
                            </td>
                            <td>
                                <div><?= formatDate($b['slot_date']) ?></div>
                                <div class="text-xs text-muted"><?= formatTime($b['slot_start']) ?> – <?= formatTime($b['slot_end']) ?></div>
                            </td>
                            <td><strong><?= formatCurrency($b['amount']) ?></strong></td>
                            <td>
                                <span class="badge <?= $b['payment_method'] === 'online' ? 'badge--info' : 'badge--warning' ?>">
                                    <?= $b['payment_method'] === 'online' ? 'Online' : 'Cash on Arrival' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= statusBadgeClass($b['status']) ?>">
                                    <?= statusLabel($b['status']) ?>
                                </span>
                                <?php if ($b['resale_id']): ?>
                                    <br><span class="badge badge--warning mt-1">Listed on Resale</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($isUpcoming): ?>
                                    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                                        <!-- Cancel Button -->
                                        <button class="btn btn--danger btn--sm cancel-btn" data-id="<?= $b['id'] ?>" data-hours="<?= $hoursLeft ?>" data-method="<?= $b['payment_method'] ?>">
                                            Cancel
                                        </button>

                                        <!-- Resale Marketplace Button -->
                                        <?php if ($b['status'] === 'confirmed' && !$b['resale_id']): ?>
                                            <?php if ($b['payment_method'] === 'online'): ?>
                                                <button class="btn btn--accent btn--sm resale-btn" data-id="<?= $b['id'] ?>" data-price="<?= $b['amount'] ?>">
                                                    List for Resale
                                                </button>
                                            <?php else: ?>
                                                <!-- Cash-on-arrival conversion flow -->
                                                <button class="btn btn--outline btn--sm convert-resale-btn" data-id="<?= $b['id'] ?>" data-price="<?= $b['amount'] ?>">
                                                    Convert to Online & Resell
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($b['resale_id']): ?>
                                            <button class="btn btn--ghost btn--sm revoke-resale-btn" data-id="<?= $b['resale_id'] ?>">
                                                Revoke Resale
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ($b['status'] === 'completed'): ?>
                                    <span class="text-xs text-muted">Completed</span>
                                <?php else: ?>
                                    <span class="text-xs text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Cancel Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Cancel Booking</h3>
            <button class="modal__close" onclick="closeModal('cancelModal')">&times;</button>
        </div>
        <div class="modal__body">
            <div id="cancellationPolicyNotice" class="alert alert--info mb-4"></div>
            <form id="cancelForm">
                <?= csrfField() ?>
                <input type="hidden" name="booking_id" id="cancelBookingId">
                <div class="form-group">
                    <label class="form-label">Reason for Cancellation</label>
                    <textarea name="reason" class="form-textarea" placeholder="Optional reason..."></textarea>
                </div>
                <button type="submit" class="btn btn--danger btn--full">Confirm Cancellation</button>
            </form>
        </div>
    </div>
</div>

<!-- Resale Modal -->
<div class="modal-overlay" id="resaleModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">List Booking for Resale</h3>
            <button class="modal__close" onclick="closeModal('resaleModal')">&times;</button>
        </div>
        <div class="modal__body">
            <div class="alert alert--warning mb-4">
                ⚠️ Server Rule: Listing price is capped at <strong>90% of original price</strong> (Rs. <span id="maxPriceSpan">0</span>).
            </div>
            <form id="resaleForm">
                <?= csrfField() ?>
                <input type="hidden" name="booking_id" id="resaleBookingId">
                <div class="form-group">
                    <label class="form-label">Resale Price (LKR)</label>
                    <input type="number" step="0.01" name="listing_price" id="resalePriceInput" class="form-input" required>
                </div>
                <button type="submit" class="btn btn--accent btn--full">List on Resale Marketplace</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Cancel action
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const hours = parseFloat(btn.dataset.hours);
            const method = btn.dataset.method;

            document.getElementById('cancelBookingId').value = id;

            let notice = '';
            if (method === 'online') {
                if (hours > 48) notice = '✅ Full refund (100%) will be issued.';
                else if (hours >= 12) notice = '⚠️ 50% refund will be issued (12-48 hours before slot).';
                else notice = '❌ Less than 12 hours before slot: No refund.';
            } else {
                if (hours > 48) notice = '✅ Responsible cancellation (no penalty).';
                else if (hours >= 12) notice = '⚠️ Moderate cancellation (minor penalty).';
                else notice = '❌ Irresponsible cancellation (larger penalty).';
            }

            document.getElementById('cancellationPolicyNotice').innerHTML = notice;
            openModal('cancelModal');
        });
    });

    document.getElementById('cancelForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetchApi('<?= BASE_URL ?>/api/bookings/cancel', { method: 'POST', body: formData });
            showToast(res.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } catch (err) {
            showToast(err.message, 'error');
        }
    });

    // Resale action
    document.querySelectorAll('.resale-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const origPrice = parseFloat(btn.dataset.price);
            const maxPrice = (origPrice * 0.9).toFixed(2);

            document.getElementById('resaleBookingId').value = id;
            document.getElementById('maxPriceSpan').textContent = maxPrice;
            document.getElementById('resalePriceInput').value = maxPrice;
            document.getElementById('resalePriceInput').max = maxPrice;
            openModal('resaleModal');
        });
    });

    document.getElementById('resaleForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetchApi('<?= BASE_URL ?>/api/resale/create', { method: 'POST', body: formData });
            showToast(res.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } catch (err) {
            showToast(err.message, 'error');
        }
    });

    // Cash conversion to resale
    document.querySelectorAll('.convert-resale-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Cash-on-arrival bookings must be converted to online payment before listing for resale. Proceed to pay online via PayHere?')) {
                const id = btn.dataset.id;
                try {
                    const formData = new FormData();
                    formData.append('booking_id', id);
                    formData.append('csrf_token', getCsrfToken());
                    const res = await fetchApi('<?= BASE_URL ?>/api/resale/convert', { method: 'POST', body: formData });
                    showToast(res.message, 'info');
                    if (res.payhere_data) {
                        // Submit to payhere sandbox
                        location.href = `<?= BASE_URL ?>/book?court=1`; // or redirect
                    }
                } catch (err) {
                    showToast(err.message, 'error');
                }
            }
        });
    });

    // Revoke resale
    document.querySelectorAll('.revoke-resale-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (confirm('Revoke this resale listing? The booking will revert to confirmed.')) {
                const id = btn.dataset.id;
                try {
                    const formData = new FormData();
                    formData.append('resale_id', id);
                    formData.append('csrf_token', getCsrfToken());
                    const res = await fetchApi('<?= BASE_URL ?>/api/resale/revoke', { method: 'POST', body: formData });
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } catch (err) {
                    showToast(err.message, 'error');
                }
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
