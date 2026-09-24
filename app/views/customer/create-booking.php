<?php
/** @var array $draft @var string $method @var array $errors */
$cash = $method === 'cash_on_arrival';
$scoreText = $draft['score'] === null ? 'Not rated yet' : round($draft['score']) . '%';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/courts/' . $draft['court_id']) ?>">Availability</a>
            <span class="breadcrumb-separator">/</span>
            <span>Confirm Booking</span>
        </div>
        <h1 class="page-title">Review &amp; Confirm Court Reservation</h1>
        <div class="page-subtitle">Verify your selected slot and choose your payment method.</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;" class="checkout-grid">
    
    <!-- Left: Booking Form & Payment Selection -->
    <div>
        
        <!-- Slot Summary Box -->
        <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-6);">
            <h3 style="font-size: 16px; margin-bottom: 12px;">Reservation Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; background: var(--color-bg-subtle); padding: 14px 18px; border-radius: var(--radius-lg); border: 1px solid var(--color-border-subtle);">
                <div>
                    <div class="text-xs" style="color: var(--color-text-muted);">Venue &amp; Court</div>
                    <div style="font-weight: 700; color: var(--color-text-title); margin-top: 2px;">
                        <?= e($draft['venue_name']) ?><br>
                        <span style="font-size: 13px; color: var(--color-primary-active); font-weight: 600;"><?= e($draft['court_name']) ?><?= $draft['sport'] !== '' ? ' · ' . e($draft['sport']) : '' ?></span>
                    </div>
                </div>
                <div>
                    <div class="text-xs" style="color: var(--color-text-muted);">Date &amp; Time Slot</div>
                    <div style="font-weight: 700; color: var(--color-text-title); margin-top: 2px;">
                        <?= e(format_datetime($draft['date'], false)) ?><br>
                        <span style="font-size: 13px; color: var(--color-text-main); font-weight: 600;"><?= e($draft['start']) ?> - <?= e($draft['end']) ?> (1 Hour)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reliability tier check for cash on arrival (UC-CU-07) -->
        <?php if ($draft['tier'] === 'standard'): ?>
            <div class="card" style="margin-bottom: var(--space-6); border: 1.5px solid #86efac; background: #f0fdf4;">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; gap: 8px; flex-wrap: wrap;">
                        <strong style="color: #166534; font-size: 14px;">Cash on Arrival Available</strong>
                        <span class="badge tier-standard">Standard Tier (<?= e($scoreText) ?>)</span>
                    </div>
                    <p class="text-xs" style="color: #166534; margin-bottom: 0; line-height: 1.5;">
                        You have <?= e($draft['completed_count']) ?> completed bookings and a reliability score of <?= e($scoreText) ?>. You can pay online or in cash at the venue.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="card" style="margin-bottom: var(--space-6); border: 1.5px solid #fde68a; background: #fffbeb;">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; gap: 8px; flex-wrap: wrap;">
                        <strong style="color: #92400e; font-size: 14px;">Online Payment Required</strong>
                        <span class="badge tier-<?= e($draft['tier']) ?>"><?= e(status_label($draft['tier'])) ?> (<?= e($scoreText) ?>)</span>
                    </div>
                    <p class="text-xs" style="color: #92400e; margin-bottom: 0; line-height: 1.5;">
                        Cash on arrival is available to Standard tier customers only (a reliability score of 70% or more after 5 bookings). Pay online to book this slot.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <form id="bookingCheckoutForm" method="POST" action="<?= url('/customer/bookings') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="court_id" value="<?= e($draft['court_id']) ?>">
            <input type="hidden" name="slot_date" value="<?= e($draft['date']) ?>">
            <input type="hidden" name="start_time" value="<?= e($draft['start']) ?>">
            <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6);">
                <h3 style="font-size: 16px; margin-bottom: 14px;">Choose Payment Method</h3>

                <!-- Option 1: PayHere Online -->
                <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; border: 1.5px solid var(--color-primary); border-radius: var(--radius-lg); background: var(--color-primary-light); cursor: pointer; margin-bottom: 12px;">
                    <input type="radio" name="payment_method" value="online" <?= $cash ? '' : 'checked' ?> style="margin-top: 3px;" onchange="updatePaymentRules('online')">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="color: var(--color-text-title); font-size: 14px;">Online Payment via PayHere Sandbox</strong>
                            <span class="badge badge-paid">Recommended</span>
                        </div>
                        <div class="text-xs" style="color: var(--color-text-main); margin-top: 4px;">
                            Your slot is held for <?= e(BookingService::HOLD_MINUTES) ?> minutes and confirmed once PayHere verifies the payment. Cancel more than 48 hours before for a full refund; between 12 and 48 hours for 50%, or release the slot for resale (90% refund if another customer books it).
                        </div>
                    </div>
                </label>

                <!-- Option 2: Cash on Arrival -->
                <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); background: var(--color-white); cursor: <?= $draft['cash_allowed'] ? 'pointer' : 'not-allowed' ?>;<?= $draft['cash_allowed'] ? '' : ' opacity: 0.6;' ?>">
                    <input type="radio" name="payment_method" value="cash_on_arrival" <?= $cash ? 'checked' : '' ?> <?= $draft['cash_allowed'] ? '' : 'disabled' ?> style="margin-top: 3px;" onchange="updatePaymentRules('cash')">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="color: var(--color-text-title); font-size: 14px;">Pay Upon Arrival (Cash on Arrival)</strong>
                            <span class="badge tier-standard">Standard Tier</span>
                        </div>
                        <div class="text-xs" style="color: var(--color-text-main); margin-top: 4px;">
                            <?php if ($draft['cash_allowed']): ?>
                                The venue confirms your request before the slot. Pay at the venue counter before entering the court. Cancelling within 12 hours or failing to show lowers your reliability score and can restrict this option.
                            <?php else: ?>
                                <?= e($draft['cash_note']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </label>
                <?php if (isset($errors['payment_method'])): ?>
                    <div class="form-feedback invalid" style="margin-top: 8px;"><?= e($errors['payment_method']) ?></div>
                <?php endif; ?>

                <!-- Dynamic Policy Note -->
                <div id="paymentPolicyNote" style="margin-top: 14px; padding: 10px 14px; background: var(--color-bg-subtle); border-radius: var(--radius-md); font-size: 12px; color: var(--color-text-muted);"></div>
            </div>

            <!-- Agreement Checkbox -->
            <div style="margin-bottom: var(--space-6);">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--color-text-main); cursor: pointer;">
                    <input type="checkbox" required checked>
                    I agree to the CourtPass venue booking terms, no-show policy, and 1-hour slot rules.
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitBookingBtn">
                Hold Slot and Pay Online &rarr;
            </button>
        </form>

    </div>

    <!-- Right: Order Summary -->
    <div>
        <div class="card" style="padding: var(--space-6); position: sticky; top: calc(var(--header-height) + 20px);">
            <h3 style="font-size: 16px; margin-bottom: 14px;">Price Breakdown</h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                <span style="color: var(--color-text-muted);">Court Rate (1 Hr):</span>
                <strong><?= e(lkr($draft['amount'])) ?></strong>
            </div>

            <div style="border-top: 1px solid var(--color-border); margin: 14px 0;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <strong style="font-size: 16px;">Total Payable:</strong>
                <strong style="font-size: 20px; color: var(--color-navy);"><?= e(lkr($draft['amount'])) ?></strong>
            </div>
        </div>
    </div>

</div>

<script>
function updatePaymentRules(mode) {
    const note = document.getElementById('paymentPolicyNote');
    const btn = document.getElementById('submitBookingBtn');
    if (mode === 'cash') {
        note.innerHTML = '⚠️ <strong>Cash on Arrival Notice:</strong> Your booking stays pending until the venue confirms it. If you cancel less than 12 hours before slot start or fail to attend, it counts against your reliability score.';
        btn.textContent = 'Send Cash-on-Arrival Request →';
    } else {
        note.innerHTML = 'ℹ️ <strong>Cancellation &amp; Resale Policy:</strong> Cancel more than 48 hours before slot time for a 100% refund. Between 12 and 48 hours, choose a 50% refund or release your slot for resale (90% refund when another customer books it; 10% processing fee). No refund under 12 hours.';
        btn.textContent = 'Hold Slot and Pay Online →';
    }
}

document.addEventListener('DOMContentLoaded', () => updatePaymentRules(<?= $cash ? "'cash'" : "'online'" ?>));
</script>
