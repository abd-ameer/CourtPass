<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Court Utilisation</span>
 </div>
 <h1 class="page-title">Court Utilisation & Heatmap </h1>
 <div class="page-subtitle">Analyze hourly slot demand, identify idle time windows, and optimize court profitability.</div>
 </div>

 <div>
 <a href="<?= url('/owner/flash-slots') ?>" class="btn btn-primary">
 Create Flash Deal on Idle Hours
 </a>
 </div>
 </div>

 <!-- KPI Stats -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 <div class="stat-card">
 <div class="stat-icon green">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
 </div>
 <div>
 <div class="stat-value">78.4%</div>
 <div class="stat-label">Average Weekly Occupancy</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
 </div>
 <div>
 <div class="stat-value">94.2%</div>
 <div class="stat-label">Peak Hours (18:00 - 22:00)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon orange">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
 </div>
 <div>
 <div class="stat-value">54.0%</div>
 <div class="stat-label">Off-Peak (10:00 - 15:00)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon blue">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
 </div>
 <div>
 <div class="stat-value">93 hrs</div>
 <div class="stat-label">Total Utilised Court Hours</div>
 </div>
 </div>
 </div>

 <!-- Main Utilisation Chart -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <div>
 <h3 class="card-title">Hourly Court Utilisation Breakdown (Today / Typical Day)</h3>
 <div class="card-subtitle">Hours booked by customers vs approved coaching sessions vs owner maintenance blocks.</div>
 </div>
 <span class="badge badge-confirmed">3 Courts Combined</span>
 </div>
 <div class="card-body">
 <div style="height: 320px; position: relative;">
 <canvas id="ownerUtilisationChart"></canvas>
 </div>
 </div>
 </div>

 <!-- Smart Recommendations & Court Performance -->
 <div class="grid grid-cols-3 gap-6">
 <!-- Smart AI Optimization Notice -->
 <div class="card" style="background: linear-gradient(135deg, #e6f8f0 0%, #ffffff 100%); border: 1px solid #a7f3d0;">
 <div class="card-body">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
 <span class="badge badge-confirmed"> Smart Pricing Insight</span>
 </div>
 <h4 style="font-size: 15px; font-weight: 800; color: var(--color-text-heading); margin-bottom: 8px;">
 Off-Peak Utilization Opportunity
 </h4>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); line-height: 1.5; margin-bottom: var(--space-4);">
 Turf Court 2 has an average occupancy of only <strong>38% between 11:00 AM and 03:00 PM</strong> on weekdays. Publishing a <strong>25% Flash Slot</strong> deal is projected to yield an extra <strong>LKR 28,000/week</strong> in booked revenue.
 </p>
 <a href="<?= url('/owner/flash-slots') ?>" class="btn btn-sm btn-primary" style="width: 100%; justify-content: center;">
 Launch Flash Deal Now
 </a>
 </div>
 </div>

 <!-- Court Breakdown -->
 <div class="card" style="grid-column: span 2;">
 <div class="card-header">
 <h3 class="card-title">Individual Court Efficiency</h3>
 </div>
 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Court</th>
 <th>Sport</th>
 <th>Weekly Hours</th>
 <th>Occupancy Rate</th>
 <th>Peak Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><strong>Turf Court 1 (Floodlit)</strong></td>
 <td><span class="badge badge-confirmed">Futsal</span></td>
 <td>48 / 56 hrs</td>
 <td>
 <div style="display: flex; align-items: center; gap: 8px;">
 <div style="flex: 1; height: 6px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 85.7%; height: 100%; background: var(--color-primary-active);"></div>
 </div>
 <span style="font-weight: 700; font-size: 11px;">85.7%</span>
 </div>
 </td>
 <td><span class="badge badge-confirmed">High Demand</span></td>
 </tr>

 <tr>
 <td><strong>Turf Court 2 (Indoor)</strong></td>
 <td><span class="badge badge-confirmed">Futsal</span></td>
 <td>26 / 56 hrs</td>
 <td>
 <div style="display: flex; align-items: center; gap: 8px;">
 <div style="flex: 1; height: 6px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 46.4%; height: 100%; background: #f59e0b;"></div>
 </div>
 <span style="font-weight: 700; font-size: 11px;">46.4%</span>
 </div>
 </td>
 <td><span class="badge badge-warning">Moderate</span></td>
 </tr>

 <tr>
 <td><strong>Wooden Badminton Court A</strong></td>
 <td><span class="badge badge-primary">Badminton</span></td>
 <td>19 / 42 hrs</td>
 <td>
 <div style="display: flex; align-items: center; gap: 8px;">
 <div style="flex: 1; height: 6px; background: var(--color-bg); border-radius: 9999px; overflow: hidden;">
 <div style="width: 45.2%; height: 100%; background: #f59e0b;"></div>
 </div>
 <span style="font-weight: 700; font-size: 11px;">45.2%</span>
 </div>
 </td>
 <td><span class="badge badge-warning">Moderate</span></td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>
 </div>

 
 


<script src="<?= asset('vendor/chartjs/chart.umd.min.js') ?>"></script>
<script src="<?= asset('js/charts.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
 if (typeof CourtPassCharts !== 'undefined') {
 CourtPassCharts.initUtilisationChart('ownerUtilisationChart');
 }
});
</script>
