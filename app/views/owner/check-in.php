<?php
$search_query = $search_query ?? $_GET['q'] ?? '';
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
 <input type="text" id="checkInSearchInput" class="form-control" placeholder="Scan QR code or enter Pass Code (e.g. CP-78190) or Customer Name..." value="<?php echo htmlspecialchars($search_query); ?>" oninput="filterCheckIns()">
 <button type="button" class="btn btn-primary" onclick="simulateScan()">
 Scan Digital Pass
 </button>
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
 <strong>CP-78190</strong>
 <div class="text-xs text-muted">BK-9021</div>
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
 <button type="button" class="btn btn-sm btn-primary" id="btn-checkin-9021" onclick="executeCheckIn('BK-9021', 'CP-78190', 'Kasun Jayawardena')">
 Mark Checked-In
 </button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="flagNoShow('BK-9021', 'Kasun Jayawardena')">
 Flag No-Show
 </button>
 </td>
 </tr>

 <!-- Check-in Row 2 -->
 <tr class="checkin-row" data-code="cp-65201" data-name="nuwan perera">
 <td>
 <strong>CP-65201</strong>
 <div class="text-xs text-muted">BK-8942</div>
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
 <strong>CP-54120</strong>
 <div class="text-xs text-muted">BK-8902</div>
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
 <button type="button" class="btn btn-sm btn-primary" onclick="executeCheckIn('BK-8902', 'CP-54120', 'Shenal Gunaratne')">
 Mark Checked-In
 </button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="flagNoShow('BK-8902', 'Shenal Gunaratne')">
 Flag No-Show
 </button>
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
function filterCheckIns() {
 const query = (document.getElementById('checkInSearchInput').value || '').trim().toLowerCase();
 document.querySelectorAll('#checkInTableBody .checkin-row').forEach(row => {
 const code = row.dataset.code;
 const name = row.dataset.name;
 const match = !query || code.includes(query) || name.includes(query);
 row.style.display = match ? '' : 'none';
 });
}

function simulateScan() {
 document.getElementById('checkInSearchInput').value = 'CP-78190';
 filterCheckIns();
 CourtPassApp.showToast('info', 'QR Code Scanned', 'Recognized digital pass for Kasun Jayawardena (CP-78190).');
}

function executeCheckIn(bkId, passCode, customerName) {
 CourtPassApp.showToast('success', 'Arrival Verified (UC-VO-13)', `${customerName} successfully checked-in! Match recorded in Match History.`);
 const statusElem = document.getElementById('status-9021');
 const btnElem = document.getElementById('btn-checkin-9021');
 if (statusElem) {
 statusElem.className = 'badge badge-completed';
 statusElem.textContent = 'Checked-In';
 }
 if (btnElem) {
 btnElem.parentElement.innerHTML = '<span class="text-xs" style="color: var(--color-primary); font-weight: 700;"> Checked-In by Desk</span>';
 }
}

function flagNoShow(bkId, customerName) {
 CourtPassApp.confirmDialog(
 'Flag No-Show Incident (UC-AS-01, UC-AS-02)',
 `Are you sure you want to flag a No-Show for ${customerName}? This will increment the customer's no-show count, apply an automated reliability score deduction, and lock unpaid reservations.`,
 'Confirm No-Show Penalty',
 function() {
 CourtPassApp.showToast('error', 'No-Show Flagged', `No-show recorded for ${bkId}. Automated system penalty applied to customer reliability record.`);
 setTimeout(() => window.location.reload(), 1200);
 }
 );
}

document.addEventListener('DOMContentLoaded', () => {
 if (document.getElementById('checkInSearchInput').value) {
 filterCheckIns();
 }
});
</script>
