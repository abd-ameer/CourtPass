<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Revenue Analytics</span>
 </div>
 <h1 class="page-title">Revenue Analytics </h1>
 <div class="page-subtitle">Detailed financial reporting and channel split (Online PayHere vs Cash-on-Arrival).</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="CourtPassApp.showToast('Exporting Revenue Report (CSV)...', 'info')">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
 Export CSV
 </button>
 <button class="btn btn-primary" onclick="CourtPassApp.showToast('Generating Monthly Tax Invoice...', 'success')">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
 Download PDF Report
 </button>
 </div>
 </div>

 <!-- KPI Stats -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 <div class="stat-card">
 <div class="stat-icon green">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 </div>
 <div>
 <div class="stat-value">LKR 404,000</div>
 <div class="stat-label">Total Revenue MTD (Sep)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon blue">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
 </div>
 <div>
 <div class="stat-value">LKR 342,000</div>
 <div class="stat-label">Online Payments (84.6%)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon orange">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
 </div>
 <div>
 <div class="stat-value">LKR 62,000</div>
 <div class="stat-label">Cash on Arrival (15.4%)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
 </div>
 <div>
 <div class="stat-value">LKR 4,850</div>
 <div class="stat-label">Avg Rate / Slot</div>
 </div>
 </div>
 </div>

 <!-- Main Revenue Chart -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
 <div>
 <h3 class="card-title">Monthly Revenue Trends (Online vs Cash)</h3>
 <div class="card-subtitle">Excludes coaching fees which are disbursed directly to coaches.</div>
 </div>
 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-primary">Monthly</button>
 <button class="btn btn-sm btn-outline">Weekly</button>
 <button class="btn btn-sm btn-outline">Daily</button>
 </div>
 </div>
 <div class="card-body">
 <div style="height: 320px; position: relative;">
 <canvas id="ownerRevenueChart"></canvas>
 </div>
 </div>
 </div>

 <!-- Breakdown by Court & Sport -->
 <div class="grid grid-cols-2 gap-6" style="margin-bottom: var(--space-6);">
 <!-- Court Revenue Table -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Revenue by Court</h3>
 </div>
 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Court Name</th>
 <th>Sport</th>
 <th>Booked Hours</th>
 <th>Revenue (LKR)</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><strong>Turf Court 1 (Floodlit)</strong></td>
 <td><span class="badge badge-confirmed">Futsal</span></td>
 <td>48 hrs</td>
 <td style="font-weight: 700; color: var(--color-primary-active);">LKR 240,000</td>
 </tr>
 <tr>
 <td><strong>Turf Court 2 (Indoor)</strong></td>
 <td><span class="badge badge-confirmed">Futsal</span></td>
 <td>26 hrs</td>
 <td style="font-weight: 700; color: var(--color-primary-active);">LKR 117,000</td>
 </tr>
 <tr>
 <td><strong>Wooden Badminton Court A</strong></td>
 <td><span class="badge badge-primary">Badminton</span></td>
 <td>19 hrs</td>
 <td style="font-weight: 700; color: var(--color-primary-active);">LKR 47,000</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Payment Settlement Summary -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Payout & Settlement Status</h3>
 </div>
 <div class="card-body">
 <div style="background: var(--color-bg); border-radius: var(--radius-lg); padding: var(--space-4); margin-bottom: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span style="font-size: var(--font-size-xs); color: var(--color-text-subtle);">NEXT AUTOMATED SETTLEMENT:</span>
 <span class="badge badge-confirmed">30 Sep 2026</span>
 </div>
 <div style="font-size: 24px; font-weight: 900; color: var(--color-text-heading); margin-bottom: 4px;">
 LKR 335,160
 </div>
 <div style="font-size: 11px; color: var(--color-text-muted);">
 Gross Online (LKR 342,000) - 2.0% PayHere Processing Fee (LKR 6,840)
 </div>
 </div>

 <div style="font-size: var(--font-size-xs); color: var(--color-text-body); line-height: 1.6;">
 <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
 <span>Bank Account:</span>
 <strong>Sampath Bank (A/C: 0019 4432 9980)</strong>
 </div>
 <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
 <span>Account Holder:</span>
 <strong>Colombo Futsal Club (Pvt) Ltd</strong>
 </div>
 <div style="display: flex; justify-content: space-between;">
 <span>Settlement Frequency:</span>
 <strong>Bi-Weekly (15th & 30th)</strong>
 </div>
 </div>
 </div>
 </div>
 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 if (typeof CourtPassCharts !== 'undefined') {
 CourtPassCharts.initRevenueChart('ownerRevenueChart');
 }
});
</script>
