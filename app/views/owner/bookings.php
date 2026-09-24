<?php
/** @var array $bookings @var array $counts */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Bookings</span>
 </div>
 <h1 class="page-title">Venue Bookings Table (UC-VO-06, 07, 09, 11)</h1>
 <div class="page-subtitle">Manage customer reservations, process approvals/rejections, and monitor payment statuses.</div>
 </div>

 <a href="<?= url('/owner/check-in') ?>" class="btn btn-primary">
 Go to Check-in Desk
 </a>
 </div>

 <!-- Filter Tabs -->
 <div class="nav-tabs">
 <button type="button" class="tab-btn active" onclick="filterOwnerBookings('all', this)">All (<?= count($bookings) ?>)</button>
 <button type="button" class="tab-btn" onclick="filterOwnerBookings('pending', this)">Pending Review (<?= $counts['pending'] ?? 0 ?>)</button>
 <button type="button" class="tab-btn" onclick="filterOwnerBookings('confirmed', this)">Confirmed (<?= $counts['confirmed'] ?? 0 ?>)</button>
 <button type="button" class="tab-btn" onclick="filterOwnerBookings('completed', this)">Completed (<?= $counts['completed'] ?? 0 ?>)</button>
 </div>

 <!-- Bookings Table -->
 <div class="card">
 <?php if ($bookings === []): ?>
 <div class="empty-state">
 <div class="empty-state-title">No bookings yet</div>
 <div class="empty-state-desc">Bookings for courts at your approved venues appear here.</div>
 </div>
 <?php else: ?>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Booking ID</th>
 <th>Customer</th>
 <th>Court & Date Slot</th>
 <th>Payment Mode</th>
 <th>Amount</th>
 <th>Status</th>
 <th style="text-align: right;">Decisions & Actions</th>
 </tr>
 </thead>
 <tbody id="ownerBookingsBody">
 <?php foreach ($bookings as $b): ?>
 <tr class="owner-booking-row" data-status="<?= e($b['status']) ?>">
 <td>
 <strong>#<?= e($b['id']) ?></strong>
 <div class="text-xs text-muted">Booked <?= e(format_datetime($b['created_at'], false)) ?></div>
 </td>
 <td>
 <strong><?= e($b['customer_name']) ?></strong>
 <span class="tier-badge tier-<?= e($b['reliability_tier']) ?>" style="font-size: 10px; margin-left: 4px;"><?= $b['reliability_score'] === null ? '' : e(round($b['reliability_score'])) . '% ' ?><?= e(status_label($b['reliability_tier'])) ?></span>
 <div class="text-xs text-muted"><?= e($b['completed_count']) ?> completed · <?= e($b['no_show_count']) ?> no-shows</div>
 </td>
 <td>
 <strong><?= e($b['court_name']) ?></strong>
 <div class="text-xs text-muted"><?= e($b['venue_name']) ?> · <?= e(format_datetime($b['slot_date'], false)) ?> · <?= e($b['start']) ?> - <?= e($b['end']) ?></div>
 </td>
 <td><?= status_badge($b['payment_method']) ?></td>
 <td><strong><?= e(lkr($b['amount'])) ?></strong></td>
 <td><?= status_badge($b['status']) ?></td>
 <td style="text-align: right;">
 <?php if ($b['can_decide']): ?>
 <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Confirm Booking', 'Confirm booking #<?= (int) $b['id'] ?>?', 'Confirm', '/owner/bookings/<?= (int) $b['id'] ?>/confirm')">Confirm</button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/<?= (int) $b['id'] ?>/reject')">Reject...</button>
 <?php elseif ($b['status'] === 'pending_payment'): ?>
 <span class="text-xs text-muted" style="margin-right: 6px;">Confirms automatically once paid</span>
 <?php elseif ($b['can_owner_cancel']): ?>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Cancel Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/<?= (int) $b['id'] ?>/cancel')">Cancel...</button>
 <?php endif; ?>
 <a href="<?= url('/owner/bookings/' . $b['id']) ?>" class="btn btn-sm btn-outline">View</a>
 </td>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
 </div>
 <?php endif; ?>
 </div>

<script>
function filterOwnerBookings(status, btn) {
 document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
 btn.classList.add('active');

 document.querySelectorAll('#ownerBookingsBody .owner-booking-row').forEach(row => {
 row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
 });
}
</script>
