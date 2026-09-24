<?php
/** @var array $booking */
$b = $booking;
$cash = $b['payment_method'] === 'cash_on_arrival';
$terms = $b['cancellation'];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/customer/bookings') ?>">My Bookings</a>
            <span class="breadcrumb-separator">/</span>
            <span>#<?= e($b['id']) ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <h1 class="page-title">Digital Court Pass</h1>
            <?= status_badge($b['status']) ?>
            <?php if ($cash): ?>
                <span class="badge badge-unpaid">Cash on Arrival</span>
            <?php elseif ($b['paid_order_id'] !== null): ?>
                <span class="badge badge-paid">Online Paid (PayHere)</span>
            <?php else: ?>
                <span class="badge badge-unpaid">Online Payment Due</span>
            <?php endif; ?>
        </div>
        <div class="page-subtitle">Booking #<?= e($b['id']) ?></div>
    </div>

    <?php if ($b['can_cancel']): ?>
        <div style="display: flex; gap: 8px;">
            <a href="<?= url('/customer/bookings/' . $b['id'] . '/cancel') ?>" class="btn btn-secondary" style="color: var(--color-danger);">
                Cancel Booking
            </a>
        </div>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">

    <!-- Left: Digital Ticket Pass -->
    <div>
        <div class="card" style="border: 2px solid var(--color-primary-border); overflow: hidden; margin-bottom: var(--space-6);">

            <div style="background: var(--color-primary); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <div>
                    <span class="badge" style="background: rgba(255,255,255,0.25); color: white; margin-bottom: 6px;">Official Digital Pass</span>
                    <h2 style="color: white; font-size: 22px; margin-bottom: 0;"><?= e($b['venue_name']) ?></h2>
                    <div style="font-size: 13px; opacity: 0.9;"><?= e($b['court_name']) ?> · <?= e($b['sport_name']) ?></div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; text-transform: uppercase; opacity: 0.85;">Pass Status</div>
                    <div style="font-size: 16px; font-weight: 800;"><?= $b['status'] === 'confirmed' ? 'READY TO PLAY' : e(strtoupper(status_label($b['status']))) ?></div>
                </div>
            </div>

            <div class="card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; margin-bottom: 24px; border-bottom: 1px dashed var(--color-border); padding-bottom: 20px;">
                    <div>
                        <div class="text-xs text-muted">Reservation Date</div>
                        <div style="font-weight: 700; font-size: 15px;"><?= e(format_datetime($b['slot_date'], false)) ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Slot Time</div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--color-primary-active);"><?= e($b['start']) ?> - <?= e($b['end']) ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Total Amount</div>
                        <div style="font-weight: 700; font-size: 15px;"><?= e(lkr($b['amount'])) ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Customer Name</div>
                        <div style="font-weight: 700; font-size: 15px;"><?= e($b['customer_name']) ?></div>
                    </div>
                </div>

                <!-- Status notice -->
                <?php if ($b['status'] === 'pending_payment'): ?>
                    <div style="padding: 14px 18px; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-lg); font-size: 13px; color: #92400e;">
                        <strong>Awaiting online payment.</strong> This slot is held for you until <?= e(date('H:i', strtotime($b['pending_expires_at']))) ?>. The booking is confirmed automatically once PayHere verifies the payment; if it is not paid by then, the hold ends and the slot is released.
                    </div>
                <?php elseif ($b['status'] === 'pending'): ?>
                    <div style="padding: 14px 18px; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-lg); font-size: 13px; color: #92400e;">
                        <strong>Waiting for the venue.</strong> <?= e($b['venue_name']) ?> will confirm or reject this cash-on-arrival request before the slot starts.
                    </div>
                <?php elseif ($b['status'] === 'confirmed'): ?>
                    <div style="display: flex; align-items: center; gap: 24px; background: var(--color-bg-subtle); padding: 16px 20px; border-radius: var(--radius-xl);">
                        <div>
                            <h4 style="margin-bottom: 4px;">Venue Check-in Instructions</h4>
                            <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 8px;">
                                Give booking number <strong>#<?= e($b['id']) ?></strong> at the front desk when you arrive. The venue marks your check-in.
                                <?php if ($cash): ?>Pay <?= e(lkr($b['amount'])) ?> at the counter before you play.<?php endif; ?>
                            </p>
                            <div class="text-xs" style="color: #166534; font-weight: 600;">
                                A check-in counts as an attended booking for your reliability score and lets you review the venue.
                            </div>
                        </div>
                    </div>
                <?php elseif ($b['status'] === 'rejected'): ?>
                    <div style="padding: 14px 18px; background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-lg); font-size: 13px; color: #991b1b;">
                        <strong>Rejected by the venue.</strong> Reason: <?= e($b['rejection_reason']) ?>
                    </div>
                <?php elseif ($b['status'] === 'cancelled'): ?>
                    <div style="padding: 14px 18px; background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius-lg); font-size: 13px; color: #991b1b;">
                        <strong><?= (int) $b['cancelled_by'] === $b['customer_id'] ? 'You cancelled this booking' : 'The venue cancelled this booking' ?></strong>
                        on <?= e(format_datetime($b['cancelled_at'])) ?>.
                        <?php if ($b['cancel_reason'] !== null): ?>Reason: <?= e($b['cancel_reason']) ?>.<?php endif; ?>
                        <?php if ($b['refund_amount'] !== null): ?>
                            <br>Refund: <?= e(round($b['refund_percent'])) ?>% (<?= e(lkr($b['refund_amount'])) ?>) returned to your payment method.
                        <?php elseif (!$cash): ?>
                            <br>No refund applies to this cancellation.
                        <?php endif; ?>
                        <?php if ($b['cancellation_class'] !== null): ?>
                            <br>Cancellation class: <?= e(status_label($b['cancellation_class'])) ?>.
                        <?php endif; ?>
                    </div>
                <?php elseif ($b['status'] === 'expired'): ?>
                    <div style="padding: 14px 18px; background: var(--color-bg-subtle); border: 1px solid var(--color-border); border-radius: var(--radius-lg); font-size: 13px; color: var(--color-text-main);">
                        <strong>Expired.</strong>
                        <?= $cash ? 'The venue did not confirm this request before the slot started.' : 'The online payment was not completed within the ' . e(BookingService::HOLD_MINUTES) . '-minute hold.' ?>
                    </div>
                <?php elseif ($b['status'] === 'released'): ?>
                    <div style="padding: 14px 18px; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-lg); font-size: 13px; color: #92400e;">
                        <strong>Released for resale.</strong> This slot is back in the booking calendar. If another customer books it before the slot starts, you receive a 90% refund.
                    </div>
                <?php elseif ($b['status'] === 'resold'): ?>
                    <div style="padding: 14px 18px; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: var(--radius-lg); font-size: 13px; color: #6d28d9;">
                        <strong>Resold.</strong> Another customer booked this slot.
                        <?php if ($b['refund_amount'] !== null): ?>Refund: <?= e(lkr($b['refund_amount'])) ?>.<?php endif; ?>
                    </div>
                <?php else: ?>
                    <div style="padding: 14px 18px; background: var(--color-bg-subtle); border: 1px solid var(--color-border); border-radius: var(--radius-lg); font-size: 13px; color: var(--color-text-main);">
                        <strong><?= e(status_label($b['status'])) ?>.</strong> This slot has finished.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Right: Actions & Policies -->
    <div>
        <?php if ($b['status'] === 'confirmed' && $terms !== null && $terms['bracket'] === '12_to_48'): ?>
            <!-- Resale Safeguard Card -->
            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Resale Safeguard</h3>
                    <span class="badge badge-confirmed">90% Refund</span>
                </div>
                <div class="card-body">
                    <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 12px; line-height: 1.5;">
                        Between 12 and 48 hours before play, you can release your slot back into the standard booking calendar. If another customer books it before session start time, you receive a <strong>90% refund</strong> (10% resale processing fee).
                    </p>
                    <?php if ($cash): ?>
                        <div style="padding: 10px; background: #fef3c7; border-radius: var(--radius-md); font-size: 12px; color: #92400e; margin-bottom: 12px;">
                            <strong>Cash-on-Arrival Conversion Required:</strong> You must convert this booking to online payment before releasing it for resale.
                        </div>
                        <form method="POST" action="<?= url('/customer/bookings/' . $b['id'] . '/release') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-primary btn-block btn-sm">Convert to Online &amp; Release</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="<?= url('/customer/bookings/' . $b['id'] . '/release') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary btn-block btn-sm">Release Slot for Resale (90% Refund) &rarr;</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($b['status'] === 'released'): ?>
            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Changed Your Mind?</h3>
                </div>
                <div class="card-body">
                    <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 12px; line-height: 1.5;">
                        You can take this booking back while no other customer has booked the slot.
                    </p>
                    <form method="POST" action="<?= url('/customer/bookings/' . $b['id'] . '/take-back') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-primary btn-block btn-sm">Take Back Booking</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Cancellation Policy Card -->
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;"><?= $cash ? 'Cash-on-Arrival Cancellation Rules' : 'Tiered Cancellation Rules' ?></h3>
            </div>
            <div class="card-body" style="font-size: 12px; display: flex; flex-direction: column; gap: 8px;">
                <?php if ($cash): ?>
                    <div style="padding: 8px; background: #ecfdf5; border-radius: var(--radius-md); border-left: 3px solid var(--color-primary);">
                        <strong>&gt; 48 Hours Before:</strong> Responsible cancellation.
                    </div>
                    <div style="padding: 8px; background: #fffbeb; border-radius: var(--radius-md); border-left: 3px solid #f59e0b;">
                        <strong>12 to 48 Hours Before:</strong> Moderate cancellation.
                    </div>
                    <div style="padding: 8px; background: #fef2f2; border-radius: var(--radius-md); border-left: 3px solid #ef4444;">
                        <strong>&lt; 12 Hours Before:</strong> Irresponsible cancellation; lowers your reliability score.
                    </div>
                <?php else: ?>
                    <div style="padding: 8px; background: #ecfdf5; border-radius: var(--radius-md); border-left: 3px solid var(--color-primary);">
                        <strong>&gt; 48 Hours Before:</strong> 100% Full Simulated Refund.
                    </div>
                    <div style="padding: 8px; background: #fffbeb; border-radius: var(--radius-md); border-left: 3px solid #f59e0b;">
                        <strong>12 to 48 Hours Before:</strong> 50% Instant Refund or Release for Resale (90% if rebooked).
                    </div>
                    <div style="padding: 8px; background: #fef2f2; border-radius: var(--radius-md); border-left: 3px solid #ef4444;">
                        <strong>&lt; 12 Hours Before:</strong> No refund.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>
