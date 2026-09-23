<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>My Bookings</span>
 </div>
 <h1 class="page-title">My Court Bookings</h1>
 <div class="page-subtitle">Track, reschedule, resell, or cancel your venue reservations.</div>
 </div>

 <a href="<?= url('/customer/venues') ?>" class="btn btn-primary">
 + Book New Court Slot
 </a>
 </div>

 <!-- Booking Tabs -->
 <div class="nav-tabs">
 <button class="tab-btn active" onclick="filterBookingTabs('all', this)">All Reservations (4)</button>
 <button class="tab-btn" onclick="filterBookingTabs('confirmed', this)">Confirmed (2)</button>
 <button class="tab-btn" onclick="filterBookingTabs('completed', this)">Completed (1)</button>
 <button class="tab-btn" onclick="filterBookingTabs('cancelled', this)">Cancelled / Refunded (1)</button>
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
 <strong>CP-78190</strong>
 <div class="text-xs text-muted">ID: BK-9021</div>
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
 <span class="badge badge-paid">Online (PayHere)</span>
 </td>
 <td>
 <strong>LKR 5,000</strong>
 </td>
 <td>
 <span class="badge badge-confirmed">Confirmed</span>
 </td>
 <td style="text-align: right;">
 <div style="display: inline-flex; gap: 6px;">
 <a href="<?= url('/customer/booking-details') ?>?id=BK-9021" class="btn btn-sm btn-primary">
 Pass / QR
 </a>
 <a href="<?= url('/customer/reschedule-booking') ?>?id=BK-9021" class="btn btn-sm btn-outline">
 Reschedule
 </a>
 <a href="<?= url('/customer/cancel-booking') ?>?id=BK-9021" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">
 Cancel / Resell
 </a>
 </div>
 </td>
 </tr>

 <!-- Booking 2 -->
 <tr class="booking-row" data-status="confirmed">
 <td>
 <strong>CP-65201</strong>
 <div class="text-xs text-muted">ID: BK-8942</div>
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
 <span class="badge badge-unpaid">Cash on Arrival</span>
 </td>
 <td>
 <strong>LKR 2,800</strong>
 </td>
 <td>
 <span class="badge badge-confirmed">Confirmed</span>
 </td>
 <td style="text-align: right;">
 <div style="display: inline-flex; gap: 6px;">
 <a href="<?= url('/customer/booking-details') ?>?id=BK-8942" class="btn btn-sm btn-primary">
 Pass / QR
 </a>
 <a href="<?= url('/customer/reschedule-booking') ?>?id=BK-8942" class="btn btn-sm btn-outline">
 Reschedule
 </a>
 <a href="<?= url('/customer/cancel-booking') ?>?id=BK-8942" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">
 Cancel
 </a>
 </div>
 </td>
 </tr>

 <!-- Booking 3 -->
 <tr class="booking-row" data-status="completed">
 <td>
 <strong>CP-43098</strong>
 <div class="text-xs text-muted">ID: BK-8810</div>
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
 <span class="badge badge-paid">Online (PayHere)</span>
 </td>
 <td>
 <strong>LKR 2,600</strong>
 </td>
 <td>
 <span class="badge badge-completed">Completed</span>
 </td>
 <td style="text-align: right;">
 <a href="<?= url('/customer/create-review') ?>?venue=venue-4" class="btn btn-sm btn-outline">
 Write Review
 </a>
 </td>
 </tr>

 <!-- Booking 4 -->
 <tr class="booking-row" data-status="cancelled">
 <td>
 <strong>CP-21980</strong>
 <div class="text-xs text-muted">ID: BK-8604</div>
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
 <span class="badge badge-cancelled">Cancelled</span>
 </td>
 <td style="text-align: right;">
 <span class="text-xs text-muted">100% Refund Simulated</span>
 </td>
 </tr>

 </tbody>
 </table>
 </div>
 </div>

 </div>
 </div>
</div>

<script>
function filterBookingTabs(status, btn) {
 document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
 btn.classList.add('active');

 const rows = document.querySelectorAll('#bookingsTableBody .booking-row');
 rows.forEach(row => {
 if (status === 'all' || row.dataset.status === status) {
 row.style.display = '';
 } else {
 row.style.display = 'none';
 }
 });
}
</script>
