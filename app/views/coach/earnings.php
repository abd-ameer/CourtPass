<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Earnings</span>
 </div>
 <h1 class="page-title">Earnings & Payouts </h1>
 <div class="page-subtitle">Track your coaching session revenue, platform commission deductions, and bank payouts.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="CourtPassApp.showToast('Downloading Earnings Statement PDF...', 'info')">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
 Download Statement
 </button>
 <button class="btn btn-primary" onclick="requestPayoutModal()">
 Request Early Payout
 </button>
 </div>
 </div>

 <!-- KPI Stats Grid -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 <div class="stat-card">
 <div class="stat-icon green">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 </div>
 <div>
 <div class="stat-value">LKR 87,500</div>
 <div class="stat-label">Earnings This Month (Sep)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
 </div>
 <div>
 <div class="stat-value">LKR 28,000</div>
 <div class="stat-label">Pending Next Payout (Sep 30)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon blue">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
 </div>
 <div>
 <div class="stat-value">LKR 355,500</div>
 <div class="stat-label">Lifetime Coaching Earnings</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon orange">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
 </div>
 <div>
 <div class="stat-value">34</div>
 <div class="stat-label">Completed Sessions</div>
 </div>
 </div>
 </div>

 <!-- Chart & Bank Details Row -->
 <div class="grid grid-cols-3 gap-6" style="margin-bottom: var(--space-6);">
 <!-- Chart -->
 <div class="card" style="grid-column: span 2;">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h3 class="card-title">Monthly Coaching Revenue (LKR)</h3>
 <span class="badge badge-active">Last 5 Months</span>
 </div>
 <div class="card-body">
 <div style="height: 260px; position: relative;">
 <canvas id="coachEarningsChart"></canvas>
 </div>
 </div>
 </div>

 <!-- Bank Payout Info -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Payout Bank Account</h3>
 </div>
 <div class="card-body">
 <div style="background: var(--color-bg); border-radius: var(--radius-lg); padding: var(--space-4); margin-bottom: var(--space-4);">
 <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-3);">
 <div style="font-weight: 700; color: var(--color-text-heading);">Commercial Bank PLC</div>
 <span class="badge badge-confirmed">Verified</span>
 </div>
 <div style="font-size: var(--font-size-xs); color: var(--color-text-subtle); margin-bottom: 4px;">Account Number:</div>
 <div style="font-family: monospace; font-size: 15px; font-weight: 700; letter-spacing: 1px; color: var(--color-text-heading); margin-bottom: var(--space-2);">8004 9281 0021</div>
 <div style="font-size: var(--font-size-xs); color: var(--color-text-subtle);">Branch: Colombo 07 (Torrington)</div>
 <div style="font-size: var(--font-size-xs); color: var(--color-text-subtle);">Beneficiary: D. R. Perera</div>
 </div>

 <div style="font-size: var(--font-size-xs); color: var(--color-text-muted); line-height: 1.5; margin-bottom: var(--space-4);">
 <strong style="color: var(--color-text-heading);">Payout Cycle:</strong> Earnings are automatically disbursed to your verified bank account bi-weekly (15th and last day of each month) with 0% withdrawal fee.
 </div>

 <a href="<?= url('/coach/settings') ?>#payout-settings" class="btn btn-sm btn-outline" style="width: 100%; justify-content: center;">
 Update Bank Account
 </a>
 </div>
 </div>
 </div>

 <!-- Breakdown Table -->
 <div class="card">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
 <div>
 <h3 class="card-title">Session-by-Session Earnings Breakdown</h3>
 <div class="card-subtitle">Transparent breakdown of fees, student counts, and platform deductions.</div>
 </div>

 <div style="display: flex; gap: 8px;">
 <select class="form-control" style="font-size: var(--font-size-xs); width: 140px;" onchange="filterMonth(this.value)">
 <option value="all">All Months</option>
 <option value="sep" selected>September 2026</option>
 <option value="aug">August 2026</option>
 <option value="jul">July 2026</option>
 </select>
 </div>
 </div>

 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Date & Session</th>
 <th>Venue & Court</th>
 <th>Students</th>
 <th>Gross (LKR)</th>
 <th>Platform Fee (5%)</th>
 <th>Net Payout (LKR)</th>
 <th>Status</th>
 <th>Action</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <div style="font-weight: 700; color: var(--color-text-heading);">22 Sep 2026</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Advanced Smash & Footwork Drills</div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 600;">CR&FC Badminton Complex</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Court 1 · 18:00 - 19:00</div>
 </td>
 <td><strong>8 / 8</strong> <span class="badge badge-confirmed">Full</span></td>
 <td>LKR 28,000</td>
 <td style="color: var(--color-danger);">-LKR 1,400</td>
 <td style="font-weight: 800; color: var(--color-primary-active);">LKR 26,600</td>
 <td><span class="badge badge-warning">Pending Payout</span></td>
 <td>
 <a href="<?= url('/coach/session-details') ?>?id=ses-1" class="btn btn-sm btn-outline">Details</a>
 </td>
 </tr>

 <tr>
 <td>
 <div style="font-weight: 700; color: var(--color-text-heading);">18 Sep 2026</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Intermediate Net Control & Spin</div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 600;">Colombo Futsal Club</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Turf 2 · 17:00 - 18:00</div>
 </td>
 <td><strong>6 / 6</strong> <span class="badge badge-confirmed">Full</span></td>
 <td>LKR 21,000</td>
 <td style="color: var(--color-danger);">-LKR 1,050</td>
 <td style="font-weight: 800; color: var(--color-primary-active);">LKR 19,950</td>
 <td><span class="badge badge-confirmed"> Disbursed (Sep 20)</span></td>
 <td>
 <a href="<?= url('/coach/session-details') ?>?id=ses-2" class="btn btn-sm btn-outline">Details</a>
 </td>
 </tr>

 <tr>
 <td>
 <div style="font-weight: 700; color: var(--color-text-heading);">14 Sep 2026</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Beginners Badminton Basics</div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 600;">CR&FC Badminton Complex</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Court 2 · 09:00 - 10:00</div>
 </td>
 <td><strong>6 / 8</strong></td>
 <td>LKR 18,000</td>
 <td style="color: var(--color-danger);">-LKR 900</td>
 <td style="font-weight: 800; color: var(--color-primary-active);">LKR 17,100</td>
 <td><span class="badge badge-confirmed"> Disbursed (Sep 15)</span></td>
 <td>
 <a href="<?= url('/coach/session-details') ?>?id=ses-3" class="btn btn-sm btn-outline">Details</a>
 </td>
 </tr>

 <tr>
 <td>
 <div style="font-weight: 700; color: var(--color-text-heading);">08 Sep 2026</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Power Smash Conditioning</div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 600;">Otters Aquatic Club</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Squash Court 1 · 18:00 - 19:00</div>
 </td>
 <td><strong>8 / 8</strong> <span class="badge badge-confirmed">Full</span></td>
 <td>LKR 25,000</td>
 <td style="color: var(--color-danger);">-LKR 1,250</td>
 <td style="font-weight: 800; color: var(--color-primary-active);">LKR 23,750</td>
 <td><span class="badge badge-confirmed"> Disbursed (Sep 15)</span></td>
 <td>
 <a href="<?= url('/coach/session-details') ?>?id=ses-1" class="btn btn-sm btn-outline">Details</a>
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
document.addEventListener('DOMContentLoaded', () => {
 if (typeof CourtPassCharts !== 'undefined') {
 CourtPassCharts.initCoachEarningsChart('coachEarningsChart');
 }
});

function requestPayoutModal() {
 CourtPassApp.showToast('Your early payout request of LKR 28,000 has been submitted for admin approval.', 'success');
}

function filterMonth(m) {
 CourtPassApp.showToast(`Filtering earnings breakdown for ${m}`, 'info');
}
</script>
