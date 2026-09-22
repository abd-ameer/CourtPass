<?php
/**
 * CourtPass — Interactive Court Booking Page
 * Features dynamic on-the-fly slot picker, sport badge, and reliability tier check.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../api/reliability/score.php';

requireLogin();
requireRole('customer');

$courtId = (int)getVal('court');
$selectedDate = getVal('date', today());

if (!$courtId) {
    header('Location: ' . BASE_URL . '/venues');
    exit;
}

$court = dbFetchOne(
    "SELECT c.*, v.name as venue_name, v.address, v.city, v.id as venue_id 
     FROM courts c 
     JOIN venues v ON c.venue_id = v.id 
     WHERE c.id = ? AND c.status = 'active' AND v.status = 'approved'",
    'i',
    [$courtId]
);

if (!$court) {
    die('Court not found or unavailable.');
}

$reliability = getCustomerReliabilityInfo(currentUserId());

$pageTitle = 'Book Court — ' . sanitize($court['name']);
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Breadcrumb & Court Title -->
    <div style="margin-bottom: 2rem;">
        <div class="text-sm text-muted" style="margin-bottom: 0.5rem;">
            <a href="<?= BASE_URL ?>/venues">Venues</a> / 
            <a href="<?= BASE_URL ?>/venue?id=<?= $court['venue_id'] ?>"><?= sanitize($court['venue_name']) ?></a> / 
            <?= sanitize($court['name']) ?>
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div>
                <h1><?= sanitize($court['name']) ?></h1>
                <p class="text-secondary">
                    <?= sportIcon($court['sport_type']) ?> <?= sportName($court['sport_type']) ?> · 
                    📍 <?= sanitize($court['city']) ?> · 
                    <strong><?= formatCurrency($court['hourly_rate']) ?>/hr</strong>
                </p>
            </div>
            <!-- Reliability Tier Badge -->
            <div class="reliability <?= $reliability['tier'] === 'standard_plus' ? 'reliability--standard' : ($reliability['tier'] === 'restricted' ? 'reliability--restricted' : 'reliability--new') ?>">
                👤 Member Tier: <?= $reliability['display_score'] ?>
            </div>
        </div>
    </div>

    <div class="grid grid--3">
        <!-- Date & Slot Picker -->
        <div style="grid-column: span 2;">
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.5rem;">Select Date & Time Slot</h3>

                <div class="form-group" style="max-width: 250px; margin-bottom: 2rem;">
                    <label class="form-label" for="datePicker">Booking Date</label>
                    <input type="date" id="datePicker" class="form-input" 
                           value="<?= sanitize($selectedDate) ?>" 
                           min="<?= today() ?>" 
                           max="<?= (new DateTime('+30 days'))->format('Y-m-d') ?>">
                </div>

                <div id="slotsContainer">
                    <div class="spinner" style="margin: 2rem auto;"></div>
                </div>
            </div>
        </div>

        <!-- Booking Summary & Checkout Card -->
        <div>
            <div class="card" style="padding: 2rem; position: sticky; top: calc(var(--header-height) + 1rem);">
                <h3 style="margin-bottom: 1.5rem;">Booking Summary</h3>

                <div style="border-bottom: 1px solid var(--neutral-100); padding-bottom: 1rem; margin-bottom: 1rem;">
                    <p class="text-sm text-muted">Venue</p>
                    <p class="font-semibold"><?= sanitize($court['venue_name']) ?></p>
                </div>

                <div style="border-bottom: 1px solid var(--neutral-100); padding-bottom: 1rem; margin-bottom: 1rem;">
                    <p class="text-sm text-muted">Court & Sport</p>
                    <p class="font-semibold"><?= sanitize($court['name']) ?> (<?= sportName($court['sport_type']) ?>)</p>
                </div>

                <div style="border-bottom: 1px solid var(--neutral-100); padding-bottom: 1rem; margin-bottom: 1rem;">
                    <p class="text-sm text-muted">Selected Slot</p>
                    <p class="font-semibold" id="selectedSlotText">No slot selected</p>
                </div>

                <div style="border-bottom: 1px solid var(--neutral-100); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                    <p class="text-sm text-muted">Total Amount</p>
                    <p class="card__price" id="totalAmount">Rs. 0.00</p>
                </div>

                <form id="bookingForm">
                    <?= csrfField() ?>
                    <input type="hidden" name="court_id" value="<?= $courtId ?>">
                    <input type="hidden" name="slot_date" id="formSlotDate" value="<?= sanitize($selectedDate) ?>">
                    <input type="hidden" name="slot_start" id="formSlotStart" value="">

                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" id="paymentMethod" class="form-select">
                            <option value="online">Online Payment (PayHere Sandbox)</option>
                            <?php if ($reliability['cash_on_arrival_eligible']): ?>
                                <option value="cash_on_arrival">Cash on Arrival (Standard+ Unlocked)</option>
                            <?php else: ?>
                                <option value="cash_on_arrival" disabled>Cash on Arrival (Locked — Requires 5+ completed bookings & 70%+ score)</option>
                            <?php endif; ?>
                        </select>
                        <?php if (!$reliability['cash_on_arrival_eligible']): ?>
                            <p class="form-hint" style="color: var(--danger-500);">
                                🔒 Complete 5+ bookings to unlock Cash on Arrival privileges.
                            </p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn--primary btn--full btn--lg mt-4" id="bookBtn" disabled>
                        Confirm Booking →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- PayHere Sandbox Form (hidden auto-submit form) -->
<form id="payhereForm" action="" method="POST" style="display:none;">
    <input type="hidden" name="merchant_id" id="ph_merchant_id">
    <input type="hidden" name="return_url" id="ph_return_url">
    <input type="hidden" name="cancel_url" id="ph_cancel_url">
    <input type="hidden" name="notify_url" id="ph_notify_url">
    <input type="hidden" name="order_id" id="ph_order_id">
    <input type="hidden" name="items" id="ph_items">
    <input type="hidden" name="currency" id="ph_currency">
    <input type="hidden" name="amount" id="ph_amount">
    <input type="hidden" name="first_name" id="ph_first_name">
    <input type="hidden" name="last_name" id="ph_last_name">
    <input type="hidden" name="email" id="ph_email">
    <input type="hidden" name="phone" id="ph_phone">
    <input type="hidden" name="address" id="ph_address">
    <input type="hidden" name="city" id="ph_city">
    <input type="hidden" name="country" id="ph_country">
    <input type="hidden" name="hash" id="ph_hash">
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const courtId = <?= $courtId ?>;
    const datePicker = document.getElementById('datePicker');
    const slotsContainer = document.getElementById('slotsContainer');
    const selectedSlotText = document.getElementById('selectedSlotText');
    const totalAmount = document.getElementById('totalAmount');
    const formSlotDate = document.getElementById('formSlotDate');
    const formSlotStart = document.getElementById('formSlotStart');
    const bookBtn = document.getElementById('bookBtn');
    const bookingForm = document.getElementById('bookingForm');

    let selectedSlot = null;

    async function loadSlots(date) {
        slotsContainer.innerHTML = '<div class="spinner" style="margin: 2rem auto;"></div>';
        bookBtn.disabled = true;
        selectedSlot = null;
        formSlotStart.value = '';
        selectedSlotText.textContent = 'No slot selected';
        totalAmount.textContent = 'Rs. 0.00';
        formSlotDate.value = date;

        try {
            const data = await fetchApi(`<?= BASE_URL ?>/api/courts/slots?court_id=${courtId}&date=${date}`);
            
            if (!data.slots || data.slots.length === 0) {
                slotsContainer.innerHTML = '<div class="empty-state"><div class="empty-state__icon">🚫</div><p>No operating hours configured for this date.</p></div>';
                return;
            }

            let html = '<div class="slots-grid">';
            data.slots.forEach(slot => {
                let slotClass = 'slot';
                if (slot.is_past) slotClass += ' slot--past';
                else if (slot.is_booked) slotClass += ' slot--booked';
                else if (slot.is_flash) slotClass += ' slot--flash';
                else slotClass += ' slot--available';

                html += `
                    <div class="${slotClass}" 
                         data-start="${slot.slot_start}" 
                         data-display="${slot.display_start} – ${slot.display_end}"
                         data-price="${slot.price}"
                         ${!slot.is_available ? 'style="pointer-events:none;"' : ''}>
                        <div class="slot__time">${slot.display_start}</div>
                        <div class="slot__price">Rs. ${slot.price.toFixed(2)}</div>
                    </div>
                `;
            });
            html += '</div>';

            slotsContainer.innerHTML = html;

            // Add click events to available slots
            document.querySelectorAll('.slot--available, .slot--flash').forEach(el => {
                el.addEventListener('click', () => {
                    document.querySelectorAll('.slot--selected').forEach(s => s.classList.remove('slot--selected'));
                    el.classList.add('slot--selected');

                    selectedSlot = {
                        start: el.dataset.start,
                        display: el.dataset.display,
                        price: parseFloat(el.dataset.price)
                    };

                    formSlotStart.value = selectedSlot.start;
                    selectedSlotText.textContent = `${date} (${selectedSlot.display})`;
                    totalAmount.textContent = `Rs. ${selectedSlot.price.toFixed(2)}`;
                    bookBtn.disabled = false;
                });
            });

        } catch (err) {
            slotsContainer.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
        }
    }

    datePicker.addEventListener('change', (e) => {
        loadSlots(e.target.value);
    });

    loadSlots(datePicker.value);

    // Booking Submission
    bookingForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!selectedSlot) return;

        bookBtn.disabled = true;
        bookBtn.innerHTML = 'Processing...';

        const formData = new FormData(bookingForm);

        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/bookings/create', {
                method: 'POST',
                body: formData
            });

            if (data.payment_method === 'cash_on_arrival') {
                showToast(data.message, 'success');
                setTimeout(() => window.location.href = '<?= BASE_URL ?>/my-bookings', 1000);
            } else if (data.payhere_data) {
                // Populate PayHere form and submit to PayHere Sandbox URL
                const p = data.payhere_data;
                const phForm = document.getElementById('payhereForm');
                phForm.action = p.sandbox_url;
                document.getElementById('ph_merchant_id').value = p.merchant_id;
                document.getElementById('ph_return_url').value = p.return_url;
                document.getElementById('ph_cancel_url').value = p.cancel_url;
                document.getElementById('ph_notify_url').value = p.notify_url;
                document.getElementById('ph_order_id').value = p.order_id;
                document.getElementById('ph_items').value = p.items;
                document.getElementById('ph_currency').value = p.currency;
                document.getElementById('ph_amount').value = p.amount;
                document.getElementById('ph_first_name').value = p.first_name;
                document.getElementById('ph_last_name').value = p.last_name;
                document.getElementById('ph_email').value = p.email;
                document.getElementById('ph_phone').value = p.phone;
                document.getElementById('ph_address').value = p.address;
                document.getElementById('ph_city').value = p.city;
                document.getElementById('ph_country').value = p.country;
                document.getElementById('ph_hash').value = p.hash;

                showToast('Redirecting to PayHere Sandbox...', 'info');
                setTimeout(() => phForm.submit(), 600);
            }
        } catch (err) {
            showToast(err.message, 'error');
            bookBtn.disabled = false;
            bookBtn.innerHTML = 'Confirm Booking →';
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
