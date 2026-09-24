<?php
/** @var array $booking @var array $reasons */
$b = $booking;
$terms = $b['cancellation'];
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
        <div class="page-subtitle">Review what cancelling now means for your refund or reliability record.</div>
    </div>
</div>

<div class="card" style="max-width: 680px; padding: var(--space-8); margin: 0 auto;">

    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--space-4);">
        <div style="width: 44px; height: 44px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            ⚠️
        </div>
        <div>
            <h3 style="font-size: 18px; margin-bottom: 2px;">Cancellation Policy Breakdown</h3>
            <div class="text-xs text-muted">
                Booking <strong>#<?= e($b['id']) ?></strong> · <?= e($b['venue_name']) ?>, <?= e($b['court_name']) ?> · <?= e(format_datetime($b['slot_date'], false)) ?> <?= e($b['start']) ?>
                (<?= $terms['hours_left'] < 1 ? 'less than an hour' : e($terms['hours_left']) . ($terms['hours_left'] === 1 ? ' hour' : ' hours') ?> until start)
            </div>
        </div>
    </div>

    <?php if ($terms['class'] === null): ?>
        <!-- Online-paid refund tier -->
        <?php if ($terms['bracket'] === 'over_48'): ?>
            <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px; flex-wrap: wrap;">
                    <strong style="color: #047857; font-size: 14px;">Time Bracket: More Than 48 Hours Before Match</strong>
                    <span class="badge badge-paid">100% Refund</span>
                </div>
                <p class="text-xs" style="color: #065f46; margin-bottom: 0; line-height: 1.5;">
                    Cancelling now issues a full refund of <strong><?= e(lkr($terms['refund_amount'])) ?></strong> via PayHere Sandbox (simulated).
                </p>
            </div>
        <?php elseif ($terms['bracket'] === '12_to_48'): ?>
            <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px; flex-wrap: wrap;">
                    <strong style="color: #b45309; font-size: 14px;">Time Bracket: 12 to 48 Hours Before Match</strong>
                    <span class="badge" style="background: #fef3c7; color: #92400e;">50% Instant Refund</span>
                </div>
                <p class="text-xs" style="color: #78350f; margin-bottom: 12px; line-height: 1.5;">
                    Cancelling directly between 12 and 48 hours before match start issues an instant 50% refund (<strong><?= e(lkr($terms['refund_amount'])) ?></strong>) via PayHere Sandbox (simulated).
                </p>

                <div style="background: white; padding: 12px; border-radius: var(--radius-md); border: 1px dashed #f59e0b; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <strong style="font-size: 13px; color: var(--color-text-title);">Want to receive a 90% refund instead?</strong>
                        <div class="text-xs text-muted">Release this slot to the standard booking pool. You get <strong><?= e(lkr($terms['resale_refund'])) ?> (90%)</strong> if another customer books it (10% processing fee).</div>
                    </div>
                    <a href="<?= url('/customer/bookings/' . $b['id']) ?>" class="btn btn-primary btn-sm">
                        Release for Resale &rarr;
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div style="background: #fef2f2; border: 1.5px solid #fecaca; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px; flex-wrap: wrap;">
                    <strong style="color: #991b1b; font-size: 14px;">Time Bracket: Less Than 12 Hours Before Match</strong>
                    <span class="badge" style="background: #fee2e2; color: #991b1b;">No Refund</span>
                </div>
                <p class="text-xs" style="color: #7f1d1d; margin-bottom: 0; line-height: 1.5;">
                    Cancelling less than 12 hours before slot start gives no refund. The slot is released so the venue can rebook it.
                </p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Cash on Arrival classification -->
        <?php if ($terms['class'] === 'responsible'): ?>
            <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px; flex-wrap: wrap;">
                    <strong style="color: #047857; font-size: 14px;">Time Bracket: More Than 48 Hours Before Match</strong>
                    <span class="badge badge-paid">Responsible Classification</span>
                </div>
                <p class="text-xs" style="color: #065f46; margin-bottom: 0; line-height: 1.5;">
                    Cancelling a Cash on Arrival reservation more than 48 hours before slot start is recorded as a <strong>Responsible Cancellation</strong>.
                </p>
            </div>
        <?php elseif ($terms['class'] === 'moderate'): ?>
            <div style="background: #fffbeb; border: 1.5px solid #fde68a; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px; flex-wrap: wrap;">
                    <strong style="color: #b45309; font-size: 14px;">Time Bracket: 12 to 48 Hours Before Match</strong>
                    <span class="badge" style="background: #fef3c7; color: #92400e;">Moderate Classification</span>
                </div>
                <p class="text-xs" style="color: #78350f; margin-bottom: 0; line-height: 1.5;">
                    Cancelling a Cash on Arrival reservation between 12 and 48 hours before slot start is recorded as a <strong>Moderate Cancellation</strong> on your reliability record.
                </p>
            </div>
        <?php else: ?>
            <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: var(--radius-lg); padding: 16px; margin-bottom: var(--space-6);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; gap: 8px; flex-wrap: wrap;">
                    <strong style="color: #1e40af; font-size: 14px;">Time Bracket: Less Than 12 Hours Before Match</strong>
                    <span class="badge" style="background: #fee2e2; color: #991b1b;">Irresponsible Classification</span>
                </div>
                <p class="text-xs" style="color: #1e3a8a; margin-bottom: 0; line-height: 1.5;">
                    Cancelling a Cash on Arrival reservation less than 12 hours before slot start is classified as an <strong>Irresponsible Cancellation</strong>. This lowers your reliability score and may restrict Cash on Arrival eligibility.
                </p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Form for cancellation reason -->
    <form method="POST" action="<?= url('/customer/bookings/' . $b['id'] . '/cancel') ?>" onsubmit="return confirm('Cancel this booking? This cannot be undone.');">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="cancelReason">Reason (optional)</label>
            <select name="reason" id="cancelReason" class="form-select">
                <option value="">Select a reason...</option>
                <?php foreach ($reasons as $reason): ?>
                    <option value="<?= e($reason) ?>"><?= e($reason) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
            <a href="<?= url('/customer/bookings/' . $b['id']) ?>" class="btn btn-secondary">
                Keep My Reservation
            </a>
            <button type="submit" class="btn btn-danger">
                Confirm Cancellation
            </button>
        </div>
    </form>

</div>
