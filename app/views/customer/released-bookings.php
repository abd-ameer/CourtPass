<?php
/** @var array $released the customer's released and resold bookings */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Released Bookings</span>
        </div>
        <h1 class="page-title">Slot Resale &amp; Refund Management</h1>
        <div class="page-subtitle">Release unwanted booking slots back into the standard booking pool to receive a 90% refund when rebooked.</div>
    </div>

    <a href="<?= url('/customer/bookings') ?>" class="btn btn-outline">
        View All Bookings &rarr;
    </a>
</div>

<!-- Resale Policy & Process Information Banner -->
<div class="card" style="margin-bottom: var(--space-6); background: #f0fdf4; border: 1.5px solid #86efac; padding: var(--space-5);">
    <div style="display: flex; gap: 14px; align-items: flex-start;">
        <div style="width: 36px; height: 36px; border-radius: 50%; background: #16a34a; color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; font-weight: bold;">
            ℹ️
        </div>
        <div>
            <h3 style="font-size: 15px; font-weight: 700; color: #14532d; margin-bottom: 6px;">How Slot Resale Works</h3>
            <div style="font-size: 13px; color: #166534; line-height: 1.6; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 8px;">
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>1. Re-enters Standard Booking:</strong> Your released slot immediately becomes available for other customers through the regular venue booking flow at standard rates.
                </div>
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>2. 90% Refund on Rebooking:</strong> When another customer successfully purchases the released slot, you will receive a <strong>90% refund</strong> of your original payment.
                </div>
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>3. 10% Platform Fee:</strong> The remaining <strong>10% is retained</strong> by CourtPass as a resale processing fee.
                </div>
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>4. Unsold Slots:</strong> If no other customer books the slot before session start time, no refund is issued. You can take back a release at any time before someone books the slot.
                </div>
            </div>
        </div>
    </div>
</div>

<p class="text-sm" style="color: var(--color-text-muted); margin-bottom: var(--space-6);">
    To release a booking, open it from <a href="<?= url('/customer/bookings') ?>">My Bookings</a> and choose <strong>Release Slot for Resale</strong>.
</p>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 16px; margin-bottom: 0;">Released Bookings</h3>
            <span class="text-xs text-muted">Released slots are booked by other customers at the normal court price.</span>
        </div>
    </div>
    <?php if ($released === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No released bookings</div>
            <div class="empty-state-desc">Online-paid bookings you release for resale appear here.</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Venue &amp; Slot</th>
                        <th>Paid</th>
                        <th>Refund</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($released as $b): ?>
                        <tr>
                            <td><strong>#<?= (int) $b['id'] ?></strong></td>
                            <td>
                                <strong><?= e($b['venue_name']) ?></strong>
                                <div class="text-xs text-muted"><?= e($b['court_name']) ?> · <?= e(format_datetime($b['slot_date'], false)) ?> · <?= e($b['start']) ?> - <?= e($b['end']) ?></div>
                            </td>
                            <td><?= e(lkr($b['amount'])) ?></td>
                            <td class="text-sm"><?= $b['status'] === 'resold' && $b['refund_amount'] !== null ? e(lkr($b['refund_amount'])) . ' refunded' : '90% if another customer books it' ?></td>
                            <td><?= status_badge($b['status']) ?></td>
                            <td style="text-align: right;">
                                <?php if ($b['status'] === 'released' && $b['group'] === 'upcoming'): ?>
                                    <form method="POST" action="<?= url('/customer/bookings/' . $b['id'] . '/take-back') ?>" class="inline-form">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-secondary">Take Back</button>
                                    </form>
                                <?php else: ?>
                                    <a href="<?= url('/customer/bookings/' . $b['id']) ?>" class="btn btn-sm btn-outline">View</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
