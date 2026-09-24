<?php
/** @var array $bookings */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>My Bookings</span>
 </div>
 <h1 class="page-title">My Court Bookings</h1>
 <div class="page-subtitle">Track, release or cancel your court bookings.</div>
 </div>

 <a href="<?= url('/venues') ?>" class="btn btn-primary">
 + Book New Court Slot
 </a>
 </div>

 <!-- Booking Tabs -->
 <div class="nav-tabs">
 <button type="button" class="tab-btn active" onclick="filterBookingTabs('all', this)">All</button>
 <button type="button" class="tab-btn" onclick="filterBookingTabs('upcoming', this)">Upcoming</button>
 <button type="button" class="tab-btn" onclick="filterBookingTabs('past', this)">Past</button>
 <button type="button" class="tab-btn" onclick="filterBookingTabs('closed', this)">Cancelled / Closed</button>
 </div>

 <!-- Bookings Table -->
 <div class="card">
 <?php if ($bookings === []): ?>
 <div class="empty-state">
 <div class="empty-state-title">No bookings yet</div>
 <div class="empty-state-desc">Pick a venue, choose a court and book a free one-hour slot.</div>
 <a href="<?= url('/venues') ?>" class="btn btn-primary" style="margin-top: 12px;">Browse Venues</a>
 </div>
 <?php else: ?>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Booking</th>
 <th>Venue & Court</th>
 <th>Date & Slot</th>
 <th>Payment Mode</th>
 <th>Fee</th>
 <th>Status</th>
 <th style="text-align: right;">Available Actions</th>
 </tr>
 </thead>
 <tbody id="bookingsTableBody">
 <?php foreach ($bookings as $b): ?>
 <tr class="booking-row" data-group="<?= e($b['group']) ?>">
 <td>
 <strong>#<?= e($b['id']) ?></strong>
 <div class="text-xs text-muted">Booked <?= e(format_datetime($b['created_at'], false)) ?></div>
 </td>
 <td>
 <strong><?= e($b['venue_name']) ?></strong>
 <div class="text-xs text-muted"><?= e($b['court_name']) ?> · <?= e($b['sport_name']) ?></div>
 </td>
 <td>
 <strong><?= e(format_datetime($b['slot_date'], false)) ?></strong>
 <div class="text-xs text-muted"><?= e($b['start']) ?> - <?= e($b['end']) ?></div>
 </td>
 <td>
 <?= status_badge($b['payment_method']) ?>
 </td>
 <td>
 <strong><?= e(lkr($b['amount'])) ?></strong>
 <?php if ($b['refund_amount'] !== null): ?>
 <div class="text-xs text-muted"><?= e(round($b['refund_percent'])) ?>% refunded (<?= e(lkr($b['refund_amount'])) ?>)</div>
 <?php endif; ?>
 </td>
 <td>
 <?= status_badge($b['status']) ?>
 <?php if ($b['status'] === 'pending_payment'): ?>
 <div class="text-xs text-muted">Held until <?= e(date('H:i', strtotime($b['pending_expires_at']))) ?></div>
 <?php endif; ?>
 </td>
 <td style="text-align: right;">
 <div style="display: inline-flex; gap: 6px;">
 <a href="<?= url('/customer/bookings/' . $b['id']) ?>" class="btn btn-sm btn-primary">
 View
 </a>
 <?php if ($b['can_cancel']): ?>
 <a href="<?= url('/customer/bookings/' . $b['id'] . '/cancel') ?>" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">
 Cancel
 </a>
 <?php endif; ?>
 <?php if ($b['status'] === 'completed'): ?>
 <a href="<?= url('/customer/bookings/' . $b['id'] . '/review') ?>" class="btn btn-sm btn-outline">
 Write Review
 </a>
 <?php endif; ?>
 </div>
 </td>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
 </div>
 <?php endif; ?>
 </div>

<script>
function filterBookingTabs(group, btn) {
    document.querySelectorAll('.nav-tabs .tab-btn').forEach((b) => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.booking-row').forEach((row) => {
        row.style.display = group === 'all' || row.dataset.group === group ? '' : 'none';
    });
}
</script>
