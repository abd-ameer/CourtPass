<?php
/** @var string $search */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Check-in Desk</span>
 </div>
 <h1 class="page-title">Front Desk Customer Check-in (UC-VO-13)</h1>
 <div class="page-subtitle">Scan customer digital passes, verify physical arrival, and enforce no-show policies.</div>
 </div>

 <div class="badge badge-confirmed" style="font-size: 13px; padding: 6px 14px;">
 Today's Total: 5 Checked-in / 3 Pending
 </div>
 </div>

 <!-- Quick Search Bar -->
 <div class="card" style="padding: 16px 20px; margin-bottom: var(--space-6);">
 <div style="display: flex; gap: 12px; align-items: center;">
 <input type="search" name="q" id="checkInSearchInput" class="form-control" placeholder="Booking number or customer name" value="<?= e($search) ?>" oninput="filterCheckIns()">
 </div>
 </div>

 <!-- Today's Check-in Queue -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Today's Court Arrivals Schedule</h3>
 <span class="text-xs text-muted">Thursday, Sept 24, 2026</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Pass Code</th>
 <th>Customer Name & Standing</th>
 <th>Court & Time Slot</th>
 <th>Payment Mode</th>
 <th>Status</th>
 <th style="text-align: right;">Verification Action</th>
 </tr>
 </thead>
 <tbody id="checkInTableBody">
 
 <!-- Check-in Row 1 -->
 <tr class="checkin-row" data-code="cp-78190" data-name="kasun jayawardena">
 <td>
 <strong>#3</strong>
 <div class="text-xs text-muted">#3</div>
 </td>
 <td>
 <strong>Kasun Jayawardena</strong>
 <span class="tier-badge tier-standard" style="font-size: 10px; margin-left: 4px;">88% Standard</span>
 </td>
 <td>
 <strong>Turf Court 1</strong>
 <div class="text-xs text-muted">08:00 PM - 09:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online Paid</span></td>
 <td><span class="badge badge-confirmed" id="status-9021">Confirmed</span></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" id="btn-checkin-9021" onclick="CourtPassApp.confirmPost('Confirm Check-in', 'Mark this customer as arrived for booking #3?', 'Check In', '/owner/bookings/3/check-in')">
 Mark Checked-In
 </button>
 </td>
 </tr>

 <!-- Check-in Row 2 -->
 <tr class="checkin-row" data-code="cp-65201" data-name="nuwan perera">
 <td>
 <strong>#4</strong>
 <div class="text-xs text-muted">#4</div>
 </td>
 <td>
 <strong>Nuwan Perera</strong>
 <span class="tier-badge tier-standard" style="font-size: 10px; margin-left: 4px;">92% Standard</span>
 </td>
 <td>
 <strong>Turf Court 2</strong>
 <div class="text-xs text-muted">06:00 PM - 07:00 PM</div>
 </td>
 <td><span class="badge badge-unpaid">Cash on Arrival (LKR 4,500)</span></td>
 <td><span class="badge badge-completed">Checked-In</span></td>
 <td style="text-align: right;">
 <span class="text-xs" style="color: var(--color-primary); font-weight: 700;"> Checked-In & Paid</span>
 </td>
 </tr>

 <!-- Check-in Row 3 -->
 <tr class="checkin-row" data-code="cp-54120" data-name="shenal gunaratne">
 <td>
 <strong>#1</strong>
 <div class="text-xs text-muted">#1</div>
 </td>
 <td>
 <strong>Shenal Gunaratne</strong>
 <span class="tier-badge tier-restricted" style="font-size: 10px; margin-left: 4px;">62% Restricted</span>
 </td>
 <td>
 <strong>Wooden Badminton Court A</strong>
 <div class="text-xs text-muted">07:00 PM - 08:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online Paid</span></td>
 <td><span class="badge badge-confirmed">Confirmed</span></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Confirm Check-in', 'Mark this customer as arrived for booking #1?', 'Check In', '/owner/bookings/1/check-in')">
 Mark Checked-In
 </button>
 </td>
 </tr>

 </tbody>
 </table>
 </div>
 </div>

 
 


<script>
function filterCheckIns() {
 const query = (document.getElementById('checkInSearchInput').value || '').trim().toLowerCase();
 document.querySelectorAll('#checkInTableBody .checkin-row').forEach(row => {
 const code = row.dataset.code;
 const name = row.dataset.name;
 const match = !query || code.includes(query) || name.includes(query);
 row.style.display = match ? '' : 'none';
 });
}




document.addEventListener('DOMContentLoaded', () => {
 if (document.getElementById('checkInSearchInput').value) {
 filterCheckIns();
 }
});
</script>
