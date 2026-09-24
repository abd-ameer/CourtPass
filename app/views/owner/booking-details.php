<?php
/** @var array $booking */
$b = $booking;
$cash = $b['payment_method'] === 'cash_on_arrival';
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/bookings') ?>">Bookings</a>
 <span class="breadcrumb-separator">/</span>
 <span>#<?= e($b['id']) ?></span>
 </div>
 <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
 <h1 class="page-title">Booking #<?= e($b['id']) ?> Overview</h1>
 <?= status_badge($b['status']) ?>
 <?php if ($cash): ?>
 <span class="badge badge-unpaid">Cash on Arrival</span>
 <?php elseif ($b['paid_order_id'] !== null): ?>
 <span class="badge badge-paid">Online Paid</span>
 <?php else: ?>
 <span class="badge badge-unpaid">Online Payment Due</span>
 <?php endif; ?>
 </div>
 <div class="page-subtitle"><?= e($b['venue_name']) ?> · <?= e($b['court_name']) ?> · <?= e(format_datetime($b['slot_date'], false)) ?> (<?= e($b['start']) ?> - <?= e($b['end']) ?>)</div>
 </div>

 <div style="display: flex; gap: 8px; flex-wrap: wrap;">
 <?php if ($b['can_decide']): ?>
 <button type="button" class="btn btn-primary" onclick="CourtPassApp.confirmPost('Confirm Booking', 'Confirm booking #<?= (int) $b['id'] ?>?', 'Confirm', '/owner/bookings/<?= (int) $b['id'] ?>/confirm')">Confirm Booking</button>
 <button type="button" class="btn btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/<?= (int) $b['id'] ?>/reject')">Reject...</button>
 <?php elseif ($b['status'] === 'confirmed'): ?>
 <a href="<?= url('/owner/check-in?q=' . $b['id']) ?>" class="btn btn-primary">
 Check-in Customer
 </a>
 <?php endif; ?>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">

 <!-- Left: Customer reliability -->
 <div class="card" style="border: 2px solid var(--color-primary-border);">
 <div class="card-header" style="background: var(--color-primary-light);">
 <h3 style="font-size: 15px; color: var(--color-primary-active); margin-bottom: 0;">Customer Profile</h3>
 <span class="badge tier-<?= e($b['reliability_tier']) ?>"><?= $b['reliability_score'] === null ? 'Not rated yet' : 'Score: ' . e(round($b['reliability_score'])) . '%' ?></span>
 </div>
 <div class="card-body">
 <div style="margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--color-border);">
 <div style="font-size: 16px; font-weight: 800;"><?= e($b['customer_name']) ?></div>
 <div class="text-xs text-muted">Phone: <?= e($b['customer_phone'] ?? 'Not given') ?> · Email: <?= e($b['customer_email']) ?></div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md);">
 <div class="text-xs text-muted">Completed Bookings</div>
 <div style="font-size: 18px; font-weight: 800; color: var(--color-text-title);"><?= e($b['completed_count']) ?></div>
 </div>
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md);">
 <div class="text-xs text-muted">No-Show Record</div>
 <div style="font-size: 18px; font-weight: 800; color: <?= $b['no_show_count'] === 0 ? '#166534' : '#991b1b' ?>;"><?= e($b['no_show_count']) ?></div>
 </div>
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md); grid-column: span 2;">
 <div class="text-xs text-muted">Payment Tier Standing</div>
 <div style="font-size: 15px; font-weight: 700; color: var(--color-primary);"><?= e(status_label($b['reliability_tier'])) ?><?= $b['reliability_tier'] === 'new_member' ? '' : ' Tier' ?></div>
 <div class="text-xs text-muted"><?= $b['reliability_tier'] === 'standard' ? 'Cash-on-Arrival Eligible' : 'Online payment only' ?></div>
 </div>
 </div>
 </div>
 </div>

 <!-- Right: Booking Financial & Slot Details -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Reservation & Payment Summary</h3>
 <span class="badge <?= $b['paid_order_id'] !== null ? 'badge-paid' : 'badge-unpaid' ?>"><?= e(lkr($b['amount'])) ?><?= $b['paid_order_id'] !== null ? ' Paid' : '' ?></span>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px; margin-bottom: 20px;">
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Court:</span>
 <strong><?= e($b['court_name']) ?> (<?= e($b['sport_name']) ?>)</strong>
 </div>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Date & Time:</span>
 <strong><?= e(format_datetime($b['slot_date'], false)) ?> (<?= e($b['start']) ?> - <?= e($b['end']) ?>)</strong>
 </div>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Payment Method:</span>
 <strong><?= $cash ? 'Cash on Arrival (pay at the counter)' : 'Online via PayHere Sandbox' ?></strong>
 </div>
 <?php if (!$cash): ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">PayHere Order Reference:</span>
 <strong style="color: var(--color-primary-active);"><?= $b['paid_order_id'] !== null ? e($b['paid_order_id']) : 'Not paid' ?></strong>
 </div>
 <?php endif; ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Requested:</span>
 <strong><?= e(format_datetime($b['created_at'])) ?></strong>
 </div>
 <?php if ($b['confirmed_at'] !== null): ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Confirmed:</span>
 <strong><?= e(format_datetime($b['confirmed_at'])) ?></strong>
 </div>
 <?php endif; ?>
 <?php if ($b['status'] === 'rejected'): ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Rejection Reason:</span>
 <strong><?= e($b['rejection_reason']) ?></strong>
 </div>
 <?php endif; ?>
 <?php if ($b['status'] === 'cancelled'): ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Cancelled:</span>
 <strong><?= e(format_datetime($b['cancelled_at'])) ?> by <?= (int) $b['cancelled_by'] === $b['customer_id'] ? 'the customer' : 'the venue' ?></strong>
 </div>
 <?php if ($b['cancel_reason'] !== null): ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Cancellation Reason:</span>
 <strong><?= e($b['cancel_reason']) ?></strong>
 </div>
 <?php endif; ?>
 <?php endif; ?>
 <?php if ($b['refund_amount'] !== null): ?>
 <div style="display: flex; justify-content: space-between; gap: 12px; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Refunded to Customer:</span>
 <strong><?= e(lkr($b['refund_amount'])) ?> (<?= e(round($b['refund_percent'])) ?>%)</strong>
 </div>
 <?php endif; ?>
 <div style="display: flex; justify-content: space-between; gap: 12px;">
 <span class="text-muted">Booking Amount:</span>
 <strong style="font-size: 16px; color: var(--color-primary-active);"><?= e(lkr($b['amount'])) ?></strong>
 </div>
 </div>

 <?php if ($b['can_owner_cancel']): ?>
 <button type="button" class="btn btn-secondary btn-block" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Cancel Booking', 'A reason is required and is shown to the customer.<?= $cash ? '' : ' The customer gets a full refund.' ?>', '/owner/bookings/<?= (int) $b['id'] ?>/cancel')">
 Cancel Reservation...
 </button>
 <?php endif; ?>
 </div>
 </div>

 </div>
