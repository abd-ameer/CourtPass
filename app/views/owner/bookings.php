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
 <strong>BK-9102</strong>
 <div class="text-xs text-muted">Pass: CP-81190</div>
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
 <button type="button" class="btn btn-sm btn-primary" onclick="confirmOwnerBooking('BK-9102')">Confirm</button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="promptRejectReason('BK-9102')">Reject...</button>
 <a href="<?= url('/owner/booking-details') ?>?id=BK-9102" class="btn btn-sm btn-outline">Profile</a>
 </td>
 </tr>

 <!-- Pending Booking 2 -->
 <tr class="owner-booking-row" data-status="pending">
 <td>
 <strong>BK-9105</strong>
 <div class="text-xs text-muted">Pass: CP-81204</div>
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
 <button type="button" class="btn btn-sm btn-primary" onclick="confirmOwnerBooking('BK-9105')">Confirm</button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="promptRejectReason('BK-9105')">Reject...</button>
 <a href="<?= url('/owner/booking-details') ?>?id=BK-9105" class="btn btn-sm btn-outline">Profile</a>
 </td>
 </tr>

 <!-- Confirmed Booking 1 -->
 <tr class="owner-booking-row" data-status="confirmed">
 <td>
 <strong>BK-9021</strong>
 <div class="text-xs text-muted">Pass: CP-78190</div>
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
 <a href="<?= url('/owner/booking-details') ?>?id=BK-9021" class="btn btn-sm btn-outline">Intel Profile</a>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="promptCancelReason('BK-9021')">Cancel...</button>
 </td>
 </tr>

 <!-- Confirmed Booking 2 -->
 <tr class="owner-booking-row" data-status="confirmed">
 <td>
 <strong>BK-8994</strong>
 <div class="text-xs text-muted">Pass: CP-77612</div>
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
 <a href="<?= url('/owner/booking-details') ?>?id=BK-8994" class="btn btn-sm btn-outline">Intel Profile</a>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="promptCancelReason('BK-8994')">Cancel...</button>
 </td>
 </tr>

 <!-- Completed Booking -->
 <tr class="owner-booking-row" data-status="completed">
 <td>
 <strong>BK-8812</strong>
 <div class="text-xs text-muted">Pass: CP-72019</div>
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

 </div>
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

function confirmOwnerBooking(id) {
 CourtPassApp.showToast('success', 'Booking Confirmed (UC-VO-06)', `Booking ${id} is now confirmed.`);
 setTimeout(() => window.location.reload(), 1000);
}

function promptRejectReason(id) {
 document.getElementById('reasonModalTitle').textContent = `Reject Booking ${id} (UC-VO-07)`;
 document.getElementById('reasonModalSubtitle').textContent = 'Mandatory reason required for rejecting a booking request.';
 document.getElementById('mandatoryReasonSubmitBtn').onclick = function() {
 const text = document.getElementById('mandatoryReasonText').value.trim();
 if (!text) {
 CourtPassApp.showToast('error', 'Mandatory Reason Required', 'Please enter a rejection reason.');
 return;
 }
 CourtPassApp.closeModal('mandatoryReasonModal');
 CourtPassApp.showToast('info', 'Booking Rejected', `Booking ${id} rejected with mandatory reason.`);
 setTimeout(() => window.location.reload(), 1000);
 };
 CourtPassApp.openModal('mandatoryReasonModal');
}

function promptCancelReason(id) {
 document.getElementById('reasonModalTitle').textContent = `Owner Cancel Booking ${id} (UC-VO-09)`;
 document.getElementById('reasonModalSubtitle').textContent = 'State the operational cancellation reason (customer will receive simulated refund).';
 document.getElementById('mandatoryReasonSubmitBtn').onclick = function() {
 const text = document.getElementById('mandatoryReasonText').value.trim();
 if (!text) {
 CourtPassApp.showToast('error', 'Mandatory Reason Required', 'Please enter a reason.');
 return;
 }
 CourtPassApp.closeModal('mandatoryReasonModal');
 CourtPassApp.showToast('info', 'Booking Cancelled', `Booking ${id} cancelled and simulated refund processed.`);
 setTimeout(() => window.location.reload(), 1000);
 };
 CourtPassApp.openModal('mandatoryReasonModal');
}
</script>
