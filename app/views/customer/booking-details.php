<?php
/** @var int $bookingId */
// TODO: load the booking; the sample below shows booking 4 as cash on arrival and others as online-paid.
$is_cash = $bookingId === 4;
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/customer/bookings') ?>">My Bookings</a>
            <span class="breadcrumb-separator">/</span>
            <span>#<?= e($bookingId) ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <h1 class="page-title">Digital Court Pass</h1>
            <span class="badge badge-confirmed">Confirmed</span>
            <?php if ($is_cash): ?>
                <span class="badge badge-unpaid">Cash on Arrival</span>
            <?php else: ?>
                <span class="badge badge-paid">Online Paid (PayHere)</span>
            <?php endif; ?>
        </div>
        <div class="page-subtitle">Booking #<?= e($bookingId) ?></div>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="<?= url('/customer/bookings/' . $bookingId . '/cancel') ?>" class="btn btn-secondary" style="color: var(--color-danger);">
            Cancel / Resell
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
    
    <!-- Left: Digital Ticket Pass -->
    <div>
        <div class="card" style="border: 2px solid var(--color-primary-border); overflow: hidden; margin-bottom: var(--space-6);">
            
            <div style="background: var(--color-primary); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span class="badge" style="background: rgba(255,255,255,0.25); color: white; margin-bottom: 6px;">Official Digital Pass</span>
                    <h2 style="color: white; font-size: 22px; margin-bottom: 0;">
                        <?= $is_cash ? 'Kandy Court Zone' : 'Colombo Sports Hub' ?>
                    </h2>
                    <div style="font-size: 13px; opacity: 0.9;">
                        <?= $is_cash ? 'Squash Court 1' : 'Futsal Court A' ?>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; text-transform: uppercase; opacity: 0.85;">Pass Status</div>
                    <div style="font-size: 16px; font-weight: 800;">READY TO PLAY</div>
                </div>
            </div>

            <div class="card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; margin-bottom: 24px; border-bottom: 1px dashed var(--color-border); padding-bottom: 20px;">
                    <div>
                        <div class="text-xs text-muted">Reservation Date</div>
                        <div style="font-weight: 700; font-size: 15px;"><?= $is_cash ? 'Mon 28 Sep 2026' : 'Sun 27 Sep 2026' ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Slot Time</div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--color-primary-active);">
                            <?= $is_cash ? '10:00 - 11:00' : '19:00 - 20:00' ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Total Amount</div>
                        <div style="font-weight: 700; font-size: 15px;"><?= $is_cash ? 'LKR 3,000' : 'LKR 5,000' ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Customer Name</div>
                        <div style="font-weight: 700; font-size: 15px;">Kasun Jayawardena</div>
                    </div>
                </div>

                <!-- Check-in -->
                <div style="display: flex; align-items: center; gap: 24px; background: var(--color-bg-subtle); padding: 16px 20px; border-radius: var(--radius-xl);">
                    

                    <div>
                        <h4 style="margin-bottom: 4px;">Venue Check-in Instructions</h4>
                        <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 8px;">
                            Give booking number <strong>#<?= e($bookingId) ?></strong> at the front desk when you arrive. The venue marks your check-in.
                        </p>
                        <div class="text-xs" style="color: #166534; font-weight: 600;">
                            A check-in counts as an attended booking for your reliability score and lets you review the venue.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Right: Actions & Policies -->
    <div>
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
                <?php if ($is_cash): ?>
                    <div style="padding: 10px; background: #fef3c7; border-radius: var(--radius-md); font-size: 12px; color: #92400e; margin-bottom: 12px;">
                        <strong>Cash-on-Arrival Conversion Required:</strong> You must convert this booking to online payment before releasing it for resale.
                    </div>
                    <form method="POST" action="<?= url('/customer/bookings/' . $bookingId . '/release') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-primary btn-block btn-sm">Convert to Online &amp; Release</button>
                    </form>
                <?php else: ?>
                    <form method="POST" action="<?= url('/customer/bookings/' . $bookingId . '/release') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary btn-block btn-sm">Release Slot for Resale (90% Refund) &rarr;</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cancellation Policy Card -->
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Tiered Cancellation Rules</h3>
            </div>
            <div class="card-body" style="font-size: 12px; display: flex; flex-direction: column; gap: 8px;">
                <div style="padding: 8px; background: #ecfdf5; border-radius: var(--radius-md); border-left: 3px solid var(--color-primary);">
                    <strong>&gt; 48 Hours Before:</strong> 100% Full Simulated Refund.
                </div>
                <div style="padding: 8px; background: #fffbeb; border-radius: var(--radius-md); border-left: 3px solid #f59e0b;">
                    <strong>12 to 48 Hours Before:</strong> 50% Instant Refund or Release for Resale (90% if rebooked).
                </div>
                <div style="padding: 8px; background: #fef2f2; border-radius: var(--radius-md); border-left: 3px solid #ef4444;">
                    <strong>&lt; 12 Hours Before:</strong> No refund.
                </div>
            </div>
        </div>

    </div>

</div>
