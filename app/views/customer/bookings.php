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
 <button type="button" class="tab-btn" onclick="filterBookingTabs('closed', this)">Cancelled / Rejected</button>
 </div>

 <!-- Bookings Table -->
 <div class="card">
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Booking Code</th>
 <th>Venue & Court</th>
 <th>Date & Slot</th>
 <th>Payment Mode</th>
 <th>Fee</th>
 <th>Status</th>
 <th style="text-align: right;">Available Actions</th>
 </tr>
 </thead>
 <tbody id="bookingsTableBody">
 
 <!-- Booking 1 -->
 <tr class="booking-row" data-status="confirmed">
 <td>
 <strong>#3</strong>
 <div class="text-xs text-muted">ID: #3</div>
 </td>
 <td>
 <strong>Colombo Futsal Club</strong>
 <div class="text-xs text-muted">Turf Court 1 (Floodlit)</div>
 </td>
 <td>
 <strong>Sept 24, 2026</strong>
 <div class="text-xs text-muted">08:00 PM - 09:00 PM (In 32 hrs)</div>
 </td>
 <td>
 <?= status_badge('online') ?>
 </td>
 <td>
 <strong>LKR 5,000</strong>
 </td>
 <td>
 <?= status_badge('confirmed') ?>
 </td>
 <td style="text-align: right;">
 <div style="display: inline-flex; gap: 6px;">
 <a href="<?= url('/customer/bookings/3') ?>" class="btn btn-sm btn-primary">
 View
 </a>
 <a href="<?= url('/customer/bookings/3/cancel') ?>" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">
 Cancel / Resell
 </a>
 </div>
 </td>
 </tr>

 <!-- Booking 2 -->
 <tr class="booking-row" data-status="pending">
 <td>
 <strong>#4</strong>
 <div class="text-xs text-muted">ID: #4</div>
 </td>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Court 1 - Yonex Mat</div>
 </td>
 <td>
 <strong>Sept 23, 2026</strong>
 <div class="text-xs text-muted">06:00 PM - 07:00 PM (In 8 hrs)</div>
 </td>
 <td>
 <?= status_badge('cash_on_arrival') ?>
 </td>
 <td>
 <strong>LKR 2,800</strong>
 </td>
 <td>
 <?= status_badge('pending') ?>
 </td>
 <td style="text-align: right;">
 <div style="display: inline-flex; gap: 6px;">
 <a href="<?= url('/customer/bookings/4') ?>" class="btn btn-sm btn-primary">
 View
 </a>
 <a href="<?= url('/customer/bookings/4/cancel') ?>" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">
 Cancel
 </a>
 </div>
 </td>
 </tr>

 <!-- Booking 3 -->
 <tr class="booking-row" data-status="completed">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">ID: #1</div>
 </td>
 <td>
 <strong>Otters Club Squash</strong>
 <div class="text-xs text-muted">Squash Court A</div>
 </td>
 <td>
 <strong>Sept 18, 2026</strong>
 <div class="text-xs text-muted">07:00 AM - 08:00 AM</div>
 </td>
 <td>
 <?= status_badge('online') ?>
 </td>
 <td>
 <strong>LKR 2,600</strong>
 </td>
 <td>
 <?= status_badge('completed') ?>
 </td>
 <td style="text-align: right;">
 <a href="<?= url('/customer/bookings/1/review') ?>" class="btn btn-sm btn-outline">
 Write Review
 </a>
 </td>
 </tr>

 <!-- Booking 4 -->
 <tr class="booking-row" data-status="cancelled">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">ID: #1</div>
 </td>
 <td>
 <strong>SpinMaster TT Club</strong>
 <div class="text-xs text-muted">Butterfly Table 1</div>
 </td>
 <td>
 <strong>Sept 12, 2026</strong>
 <div class="text-xs text-muted">05:00 PM - 06:00 PM</div>
 </td>
 <td>
 <span class="badge" style="background: #f1f5f9; color: #475569;">Refunded</span>
 </td>
 <td>
 <strong>LKR 1,600</strong>
 </td>
 <td>
 <?= status_badge('cancelled') ?>
 </td>
 <td style="text-align: right;">
 <span class="text-xs text-muted">100% Refund Simulated</span>
 </td>
 </tr>

 </tbody>
 </table>
 </div>
 </div>

 
 


<script>
function filterBookingTabs(group, btn) {
    const groups = {
        upcoming: ['pending_payment', 'pending', 'confirmed', 'released'],
        past: ['completed', 'completed_unattended', 'no_show', 'resold'],
        closed: ['cancelled', 'rejected', 'expired'],
    };
    document.querySelectorAll('.nav-tabs .tab-btn').forEach((b) => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.booking-row').forEach((row) => {
        row.style.display = group === 'all' || groups[group].includes(row.dataset.status) ? '' : 'none';
    });
}
</script>
