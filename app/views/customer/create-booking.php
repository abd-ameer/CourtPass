<?php
/** @var int $courtId @var string $date @var string $start */
// TODO: court, venue, rate and the customer's tier come from the booking service; the price is never taken from the request.
$rate = 5000.00;
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/courts/1') ?>">Availability</a>
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
                        Colombo Sports Hub<br>
                        <span style="font-size: 13px; color: var(--color-primary-active); font-weight: 600;">Futsal Court A</span>
                    </div>
                </div>
                <div>
                    <div class="text-xs" style="color: var(--color-text-muted);">Date &amp; Time Slot</div>
                    <div style="font-weight: 700; color: var(--color-text-title); margin-top: 2px;">
                        <?= e($date) ?><br>
                        <span style="font-size: 13px; color: var(--color-text-main); font-weight: 600;"><?= e($start) ?> (1 Hour)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Reliability Tier Validation Banner (UC-CU-07) -->
        <div class="card" style="margin-bottom: var(--space-6); border: 1.5px solid #86efac; background: #f0fdf4;">
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 16px;">✅</span>
                        <strong style="color: #166534; font-size: 14px;">Reliability Tier Validation Passed</strong>
                    </div>
                    <span class="badge tier-standard">Standard Tier (88%)</span>
                </div>
                <p class="text-xs" style="color: #166534; margin-bottom: 0; line-height: 1.5;">
                    You have 14 completed bookings and a reliability score of 88% (exceeding the 70% threshold). You are eligible to choose between <strong>Online Payment</strong> and <strong>Cash on Arrival</strong>.
                </p>
            </div>
        </div>

        <!-- Payment Method Options Form -->
        <form id="bookingCheckoutForm" method="POST" action="<?= url('/customer/bookings') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="court_id" value="<?= e($courtId) ?>">
            <input type="hidden" name="slot_date" value="<?= e($date) ?>">
            <input type="hidden" name="start_time" value="<?= e($start) ?>">
            <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6);">
                <h3 style="font-size: 16px; margin-bottom: 14px;">Choose Payment Method</h3>

                <!-- Option 1: PayHere Online -->
                <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; border: 1.5px solid var(--color-primary); border-radius: var(--radius-lg); background: var(--color-primary-light); cursor: pointer; margin-bottom: 12px;">
                    <input type="radio" name="payment_method" value="online" checked style="margin-top: 3px;" onchange="updatePaymentRules('online')">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="color: var(--color-text-title); font-size: 14px;">Online Payment via PayHere Sandbox</strong>
                            <span class="badge badge-paid">Recommended</span>
                        </div>
                        <div class="text-xs" style="color: var(--color-text-main); margin-top: 4px;">
                            Confirmed once PayHere verifies the payment. Cancel more than 48 hours before for a full refund; between 12 and 48 hours for 50%, or release the slot for resale (90% refund if another customer books it).
                        </div>
                    </div>
                </label>

                <!-- Option 2: Cash on Arrival -->
                <label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px; border: 1.5px solid var(--color-border); border-radius: var(--radius-lg); background: var(--color-white); cursor: pointer;">
                    <input type="radio" name="payment_method" value="cash_on_arrival" style="margin-top: 3px;" onchange="updatePaymentRules('cash')">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="color: var(--color-text-title); font-size: 14px;">Pay Upon Arrival (Cash on Arrival)</strong>
                            <span class="badge tier-standard">Unlocked Privilege</span>
                        </div>
                        <div class="text-xs" style="color: var(--color-text-main); margin-top: 4px;">
                            Pay directly at the venue counter before entering the court. Cancellations within 12 hours or failing to show will incur a reliability penalty and can restrict this privilege.
                        </div>
                    </div>
                </label>

                <!-- Dynamic Policy Note -->
                <div id="paymentPolicyNote" style="margin-top: 14px; padding: 10px 14px; background: var(--color-bg-subtle); border-radius: var(--radius-md); font-size: 12px; color: var(--color-text-muted);">
                    ℹ️ <strong>Cancellation &amp; Resale Policy:</strong> Cancel &gt; 48 hours before slot time for a 100% full refund. Between 12 to 48 hours, choose between an instant 50% refund or releasing your slot for resale (90% refund when another customer books it; 10% processing fee).
                </div>
            </div>

            <!-- Agreement Checkbox -->
            <div style="margin-bottom: var(--space-6);">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--color-text-main); cursor: pointer;">
                    <input type="checkbox" required checked>
                    I agree to the CourtPass venue booking terms, no-show policy, and 1-hour slot rules.
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block" id="submitBookingBtn">
                Confirm &amp; Complete Reservation &rarr;
            </button>
        </form>

    </div>

    <!-- Right: Order Summary -->
    <div>
        <div class="card" style="padding: var(--space-6); position: sticky; top: calc(var(--header-height) + 20px);">
            <h3 style="font-size: 16px; margin-bottom: 14px;">Price Breakdown</h3>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                <span style="color: var(--color-text-muted);">Court Rate (1 Hr):</span>
                <strong>LKR <?= e(number_format($rate)) ?></strong>
            </div>

            <div style="border-top: 1px solid var(--color-border); margin: 14px 0;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <strong style="font-size: 16px;">Total Payable:</strong>
                <strong style="font-size: 20px; color: var(--color-navy);">LKR <?= e(number_format($rate)) ?></strong>
            </div>
        </div>
    </div>

</div>

<script>
function updatePaymentRules(mode) {
    const note = document.getElementById('paymentPolicyNote');
    const btn = document.getElementById('submitBookingBtn');
    if (mode === 'cash') {
        note.innerHTML = `⚠️ <strong>Cash on Arrival Notice:</strong> If you cancel less than 12 hours before slot start or fail to attend, your reservation will be marked <strong>Irresponsible</strong>, lowering your reliability score.`;
        btn.textContent = 'Confirm Cash-on-Arrival Booking →';
    } else {
        note.innerHTML = `ℹ️ <strong>Cancellation & Resale Policy:</strong> Cancel >48 hours before slot time for a 100% full refund. Between 12 to 48 hours, choose between an instant 50% refund or releasing your slot for resale (90% refund when another customer books it; 10% processing fee).`;
        btn.textContent = 'Continue to PayHere →';
    }
}

</script>
