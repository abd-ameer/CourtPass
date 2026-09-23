<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Owner Portal</span>
 <span class="breadcrumb-separator">/</span>
 <span>Dashboard</span>
 </div>
 <h1 class="page-title">Colombo Futsal Club — Management Desk</h1>
 <div class="page-subtitle">Real-time court bookings, walk-in slot controls, check-ins, and revenue metrics.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <a href="<?= url('/owner/check-in') ?>" class="btn btn-primary">
 Quick Customer Check-in
 </a>
 <a href="<?= url('/owner/slot-management') ?>" class="btn btn-outline">
 Slot Blocker & Deals
 </a>
 </div>
 </div>

 <!-- Owner Stat Cards -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 
 <div class="stat-card">
 <div class="stat-icon green">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 </div>
 <div>
 <div class="stat-value">LKR 342k</div>
 <div class="stat-label">Sep Revenue (Excl. Coaches)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon amber">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
 </div>
 <div>
 <div class="stat-value">2</div>
 <div class="stat-label">Pending Bookings</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon blue">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
 </div>
 <div>
 <div class="stat-value">5 / 8</div>
 <div class="stat-label">Today's Check-ins</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
 </div>
 <div>
 <div class="stat-value">78%</div>
 <div class="stat-label">Court Utilisation Rate</div>
 </div>
 </div>

 </div>

 <!-- Pending Booking Requests (UC-VO-06, UC-VO-07, UC-VO-08) -->
 <div class="card" style="margin-bottom: var(--space-8); border: 2px solid #fde68a;">
 <div class="card-header" style="background: #fffbeb;">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <h3 style="font-size: 15px; color: #92400e; margin-bottom: 0;">Pending Reservation Requests Requiring Action (2)</h3>
 </div>
 <span class="badge badge-pending">Action Required</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Customer Profile & Reliability</th>
 <th>Court & Requested Slot</th>
 <th>Payment Mode</th>
 <th>Amount</th>
 <th style="text-align: right;">Owner Decision</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Pradeep Bandara</strong>
 <span class="tier-badge tier-standard" style="font-size: 10px; margin-left: 6px;">82% Standard</span>
 <div class="text-xs text-muted">History: 7 bookings at this venue · 0 no-shows</div>
 </td>
 <td>
 <strong>Turf Court 1</strong>
 <div class="text-xs text-muted">Sept 25, 2026 · 06:00 PM - 07:00 PM</div>
 </td>
 <td><span class="badge badge-unpaid">Cash on Arrival</span></td>
 <td><strong>LKR 5,000</strong></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" onclick="confirmOwnerBooking('BK-9102')">
 Confirm Booking
 </button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="promptRejectReason('BK-9102')">
 Reject...
 </button>
 </td>
 </tr>
 <tr>
 <td>
 <strong>Dinesh Pathirana</strong>
 <span class="tier-badge tier-new" style="font-size: 10px; margin-left: 6px;">New Member</span>
 <div class="text-xs text-muted">First-time player at Colombo Futsal Club</div>
 </td>
 <td>
 <strong>Turf Court 2 (Indoor)</strong>
 <div class="text-xs text-muted">Sept 25, 2026 · 07:00 PM - 08:00 PM</div>
 </td>
 <td><span class="badge badge-paid">Online (PayHere)</span></td>
 <td><strong>LKR 4,500</strong></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" onclick="confirmOwnerBooking('BK-9105')">
 Confirm Booking
 </button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="promptRejectReason('BK-9105')">
 Reject...
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Analytics Charts Preview (Utilisation & Revenue) -->
 <div class="grid grid-cols-2 gap-6">
 
 <!-- Court Utilisation Heatmap Bar Chart (Chart.js) -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Hourly Court Utilisation (Includes Coaching)</h3>
 <a href="<?= url('/owner/utilisation') ?>" style="font-size: 12px; font-weight: 600;">Detailed Heatmap &rarr;</a>
 </div>
 <div class="card-body">
 <div style="height: 220px; position: relative;">
 <canvas id="ownerUtilisationChart"></canvas>
 </div>
 <div class="text-xs text-muted" style="text-align: center; margin-top: 8px;">
 Peak hours: 18:00 - 22:00 (100% capacity). Coaching sessions counted as used hours.
 </div>
 </div>
 </div>

 <!-- Monthly Revenue Breakdown (Chart.js) -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Revenue Overview (Online vs Cash-on-Arrival)</h3>
 <a href="<?= url('/owner/revenue') ?>" style="font-size: 12px; font-weight: 600;">Full Revenue Report &rarr;</a>
 </div>
 <div class="card-body">
 <div style="height: 220px; position: relative;">
 <canvas id="ownerRevenueChart"></canvas>
 </div>
 <div class="text-xs text-muted" style="text-align: center; margin-top: 8px;">
 Excludes coach fees per platform policy. Cash-on-arrival is verified upon front desk check-in.
 </div>
 </div>
 </div>

 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassCharts.initUtilisationChart('ownerUtilisationChart');
 CourtPassCharts.initRevenueChart('ownerRevenueChart');
});

function confirmOwnerBooking(id) {
 CourtPassApp.showToast('success', 'Booking Confirmed (UC-VO-06)', `Booking ${id} status moved from Pending to Confirmed.`);
 setTimeout(() => window.location.reload(), 1000);
}

function promptRejectReason(id) {
 document.getElementById('reasonModalTitle').textContent = `Reject Booking ${id} (UC-VO-07)`;
 document.getElementById('reasonModalSubtitle').textContent = 'Please enter a mandatory rejection reason for the customer.';
 document.getElementById('mandatoryReasonSubmitBtn').onclick = function() {
 const text = document.getElementById('mandatoryReasonText').value.trim();
 if (!text) {
 CourtPassApp.showToast('error', 'Mandatory Reason Required', 'You must provide a rejection reason.');
 return;
 }
 CourtPassApp.closeModal('mandatoryReasonModal');
 CourtPassApp.showToast('info', 'Booking Rejected', `Booking ${id} rejected with reason: "${text}"`);
 setTimeout(() => window.location.reload(), 1000);
 };
 CourtPassApp.openModal('mandatoryReasonModal');
}
</script>
