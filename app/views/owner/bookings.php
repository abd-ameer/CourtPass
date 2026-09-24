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
 <button class="tab-btn active" onclick="filterOwnerBookings('all', this)">All (5)</button>
 <button class="tab-btn" onclick="filterOwnerBookings('pending', this)">Pending Review (2)</button>
 <button class="tab-btn" onclick="filterOwnerBookings('confirmed', this)">Confirmed (2)</button>
 <button class="tab-btn" onclick="filterOwnerBookings('completed', this)">Completed (1)</button>
 </div>

 <!-- Bookings Table -->
 <div class="card">
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Booking ID</th>
 <th>Customer Intelligence</th>
 <th>Court & Date Slot</th>
 <th>Payment Mode</th>
 <th>Amount</th>
 <th>Status</th>
 <th style="text-align: right;">Decisions & Actions</th>
 </tr>
 </thead>
 <tbody id="ownerBookingsBody">
 
 <!-- Pending Booking 1 -->
 <tr class="owner-booking-row" data-status="pending">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">Pass: #1</div>
 </td>
 <td>
 <strong>Pradeep Bandara</strong>
 <span class="tier-badge tier-standard" style="font-size: 10px; margin-left: 4px;">82% Standard</span>
 <div class="text-xs text-muted">7 venue visits · 0 no-shows</div>
 </td>
 <td>
 <strong>Turf Court 1</strong>
 <div class="text-xs text-muted">Sept 25, 2026 · 06:00 PM - 07:00 PM</div>
 </td>
 <td><span class="badge badge-unpaid">Cash on Arrival</span></td>
 <td><strong>LKR 5,000</strong></td>
 <td><span class="badge badge-pending">Pending</span></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Confirm Booking', 'Confirm this booking request?', 'Confirm', '/owner/bookings/4/confirm')">Confirm</button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/4/reject')">Reject...</button>
 <a href="<?= url('/owner/bookings/5') ?>" class="btn btn-sm btn-outline">Profile</a>
 </td>
 </tr>

 <!-- Pending Booking 2 -->
 <tr class="owner-booking-row" data-status="pending">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">Pass: #1</div>
 </td>
 <td>
 <strong>Dinesh Pathirana</strong>
 <span class="tier-badge tier-new" style="font-size: 10px; margin-left: 4px;">New Member</span>
 <div class="text-xs text-muted">First-time reservation</div>
 </td>
 <td>
 <strong>Turf Court 2 (Indoor)</strong>
 <div class="text-xs text-muted">Sept 25, 2026 · 07:00 PM - 08:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online (PayHere)</span></td>
 <td><strong>LKR 4,500</strong></td>
 <td><span class="badge badge-pending">Pending</span></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Confirm Booking', 'Confirm this booking request?', 'Confirm', '/owner/bookings/4/confirm')">Confirm</button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/4/reject')">Reject...</button>
 <a href="<?= url('/owner/bookings/4') ?>" class="btn btn-sm btn-outline">Profile</a>
 </td>
 </tr>

 <!-- Confirmed Booking 1 -->
 <tr class="owner-booking-row" data-status="confirmed">
 <td>
 <strong>#3</strong>
 <div class="text-xs text-muted">Pass: #3</div>
 </td>
 <td>
 <strong>Kasun Jayawardena</strong>
 <span class="tier-badge tier-standard" style="font-size: 10px; margin-left: 4px;">88% Standard</span>
 <div class="text-xs text-muted">14 venue visits · 0 no-shows</div>
 </td>
 <td>
 <strong>Turf Court 1</strong>
 <div class="text-xs text-muted">Sept 24, 2026 · 08:00 PM - 09:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online (PayHere)</span></td>
 <td><strong>LKR 5,000</strong></td>
 <td><span class="badge badge-confirmed">Confirmed</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/owner/bookings/3') ?>" class="btn btn-sm btn-outline">Intel Profile</a>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Cancel Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/3/cancel')">Cancel...</button>
 </td>
 </tr>

 <!-- Confirmed Booking 2 -->
 <tr class="owner-booking-row" data-status="confirmed">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">Pass: #1</div>
 </td>
 <td>
 <strong>Akila Samarasinghe</strong>
 <span class="tier-badge tier-standard" style="font-size: 10px; margin-left: 4px;">91% Standard</span>
 <div class="text-xs text-muted">16 venue visits · 0 no-shows</div>
 </td>
 <td>
 <strong>Turf Court 2</strong>
 <div class="text-xs text-muted">Sept 24, 2026 · 06:00 PM - 07:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online (PayHere)</span></td>
 <td><strong>LKR 4,500</strong></td>
 <td><span class="badge badge-confirmed">Confirmed</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/owner/bookings/1') ?>" class="btn btn-sm btn-outline">Intel Profile</a>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Cancel Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/1/cancel')">Cancel...</button>
 </td>
 </tr>

 <!-- Completed Booking -->
 <tr class="owner-booking-row" data-status="completed">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">Pass: #1</div>
 </td>
 <td>
 <strong>Shenal Gunaratne</strong>
 <span class="tier-badge tier-restricted" style="font-size: 10px; margin-left: 4px;">62% Restricted</span>
 <div class="text-xs text-muted">8 venue visits · 3 no-shows</div>
 </td>
 <td>
 <strong>Turf Court 1</strong>
 <div class="text-xs text-muted">Sept 14, 2026 · 08:00 PM - 09:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online (PayHere)</span></td>
 <td><strong>LKR 5,000</strong></td>
 <td><span class="badge badge-completed">Completed</span></td>
 <td style="text-align: right;">
 <span class="text-xs text-muted">Checked in by Desk</span>
 </td>
 </tr>

 </tbody>
 </table>
 </div>
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
