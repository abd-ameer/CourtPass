<?php
$booking_id = $booking_id ?? $_GET['id'] ?? 'BK-9021';
$is_cash = $is_cash ?? isset($_GET['cash']);
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/customer/bookings') ?>">My Bookings</a>
            <span class="breadcrumb-separator">/</span>
            <span>Cancel Reservation</span>
        </div>
        <h1 class="page-title">Cancel Court Reservation</h1>
        <div class="page-subtitle">Review policy implications, refund calculations, or choose to release for resale.</div>
    </div>
</div>

<div class="card" style="max-width: 680px; padding: var(--space-8); margin: 0 auto;">
    
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--space-4);">
        <div style="width: 44px; height: 44px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            ⚠️
        </div>
        <div>
            <h3 style="font-size: 18px; margin-bottom: 2px;">Cancellation Policy Breakdown</h3>
            <div class="text-xs text-muted">Booking Reference: <strong><?php echo htmlspecialchars($booking_id); ?></strong> (32 hours until start)</div>
        </div>
    </div>

    <?php if (!$is_cash): ?>
        <!-- Online Paid Tier Policy -->
        <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="color: #b45309; font-size: 14px;">Time Bracket: 12 to 48 Hours Before Match</strong>
                <span class="badge" style="background: #fef3c7; color: #92400e;">50% Instant Refund</span>
            </div>
            <p class="text-xs" style="color: #78350f; margin-bottom: 12px; line-height: 1.5;">
                Cancelling directly between 12 and 48 hours before match start issues an instant 50% refund (<strong>LKR 2,500</strong>) via PayHere Sandbox.
            </p>

            <div style="background: white; padding: 12px; border-radius: var(--radius-md); border: 1px dashed #f59e0b; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div>
                    <strong style="font-size: 13px; color: var(--color-text-title);">Want to receive a 90% refund instead?</strong>
                    <div class="text-xs text-muted">Release this slot to the standard booking pool. You get <strong>LKR 4,500 (90%)</strong> if another customer books it (10% platform fee).</div>
                </div>
                <a href="<?= url('/customer/my-resales') ?>?list=BK-9021" class="btn btn-primary btn-sm">
                    Release for Resale &rarr;
                </a>
            </div>
        </div>

    <?php else: ?>
        <!-- Cash on Arrival Policy -->
        <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="color: #1e40af; font-size: 14px;">Time Bracket: 8 Hours Before Match (&lt;12h)</strong>
                <span class="badge" style="background: #fee2e2; color: #991b1b;">Irresponsible Classification</span>
            </div>
            <p class="text-xs" style="color: #1e3a8a; margin-bottom: 0; line-height: 1.5;">
                Cancelling a Cash on Arrival reservation less than 12 hours before slot start is classified as an <strong>Irresponsible Cancellation</strong>. This applies a minor deduction to your reliability score and may restrict Cash on Arrival eligibility.
            </p>
        </div>
    <?php endif; ?>

    <!-- Form for cancellation reason -->
    <form onsubmit="handleCancelConfirm(event)">
        <div class="form-group">
            <label class="form-label">Mandatory Reason for Cancellation <span class="required-star">*</span></label>
            <select class="form-select" required>
                <option value="">Select a reason...</option>
                <option>Personal / Work Emergency</option>
                <option>Teammates unavailable / Injury</option>
                <option>Double booking / Scheduling clash</option>
                <option>Bad weather / Transport issues</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Additional Comments</label>
            <textarea class="form-control" rows="2" placeholder="Optional details for venue management..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
            <a href="<?= url('/customer/booking-details') ?>?id=<?php echo htmlspecialchars($booking_id); ?>" class="btn btn-secondary">
                Keep My Reservation
            </a>
            <button type="submit" class="btn btn-danger">
                Confirm Cancellation
            </button>
        </div>
    </form>

</div>

<script>
function handleCancelConfirm(e) {
    e.preventDefault();
    CourtPassApp.confirmDialog(
        'Confirm Booking Cancellation',
        'Are you sure you want to cancel this booking? This action is permanent.',
        'Yes, Cancel Booking',
        function() {
            CourtPassApp.showToast('info', 'Booking Cancelled', 'Your booking has been cancelled and simulated refund policy initiated.');
            setTimeout(() => {
                window.location.href = '<?= url('/customer/bookings') ?>';
            }, 1200);
        }
    );
}
</script>
