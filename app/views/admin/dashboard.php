<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Admin Portal</span>
 <span class="breadcrumb-separator">/</span>
 <span>Dashboard</span>
 </div>
 <h1 class="page-title">Platform Administration </h1>
 <div class="page-subtitle">Platform overview, pending approvals, verification requests, and dispute queues.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <a href="<?= url('/admin/venues') ?>" class="btn btn-primary">
 Review Venues (2)
 </a>
 <a href="<?= url('/admin/coaches') ?>" class="btn btn-outline">
 Coach Verification (3)
 </a>
 </div>
 </div>

 <!-- Admin KPI Stats -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 <div class="stat-card">
 <div class="stat-icon green">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
 </div>
 <div>
 <div class="stat-value">1,480</div>
 <div class="stat-label">Active Platform Users</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon blue">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
 </div>
 <div>
 <div class="stat-value">28</div>
 <div class="stat-label">Partner Venues (84 Courts)</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
 </div>
 <div>
 <div class="stat-value">918</div>
 <div class="stat-label">Bookings This Month</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon orange">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 </div>
 <div>
 <div class="stat-value">LKR 4.2M</div>
 <div class="stat-label">Total Booking Volume (MTD)</div>
 </div>
 </div>
 </div>

 <!-- Action Required Queue Grid -->
 <div class="grid grid-cols-3 gap-6" style="margin-bottom: var(--space-6);">
 
 <!-- Pending Venues Card -->
 <div class="card" style="border-top: 4px solid var(--color-warning);">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h4 class="card-title">Pending Venues</h4>
 <span class="badge badge-warning">2 Pending</span>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 10px;">
 <div style="padding: 10px; background: var(--color-bg); border-radius: var(--radius-md);">
 <div style="font-weight: 700; font-size: 13px; color: var(--color-text-heading);">Grand Arena Indoor Complex</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Rajagiriya · 4 Courts · Submitted Yesterday</div>
 </div>
 <div style="padding: 10px; background: var(--color-bg); border-radius: var(--radius-md);">
 <div style="font-weight: 700; font-size: 13px; color: var(--color-text-heading);">Nugegoda Pickleball Hub</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Nugegoda · 2 Courts · Submitted 2 days ago</div>
 </div>
 </div>
 <a href="<?= url('/admin/venues') ?>" class="btn btn-sm btn-outline" style="width: 100%; justify-content: center; margin-top: 12px;">
 Review Venue Queue →
 </a>
 </div>
 </div>

 <!-- Coach Verifications Card -->
 <div class="card" style="border-top: 4px solid #7c3aed;">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h4 class="card-title">Coach Verifications</h4>
 <span class="badge badge-primary">3 Pending</span>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 10px;">
 <div style="padding: 10px; background: var(--color-bg); border-radius: var(--radius-md);">
 <div style="font-weight: 700; font-size: 13px; color: var(--color-text-heading);">Coach Shanilka Fernando</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Squash · WSF Level 1 Certificate uploaded</div>
 </div>
 <div style="padding: 10px; background: var(--color-bg); border-radius: var(--radius-md);">
 <div style="font-weight: 700; font-size: 13px; color: var(--color-text-heading);">Coach Priyantha Dias</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Table Tennis · ITTF Certified Coach</div>
 </div>
 </div>
 <a href="<?= url('/admin/coaches') ?>" class="btn btn-sm btn-outline" style="width: 100%; justify-content: center; margin-top: 12px;">
 Verify Coach Badges →
 </a>
 </div>
 </div>

 <!-- Disputes Card -->
 <div class="card" style="border-top: 4px solid var(--color-danger);">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h4 class="card-title">Dispute Resolution</h4>
 <span class="badge badge-danger">1 No-Show Dispute</span>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 10px;">
 <div style="padding: 10px; background: var(--color-bg); border-radius: var(--radius-md);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
 <span style="font-weight: 700; font-size: 13px; color: var(--color-text-heading);">Danushka Wickramasinghe</span>
 <span class="badge badge-danger">No-Show</span>
 </div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Colombo Futsal Club · Claims power outage prevented play</div>
 </div>
 </div>
 <a href="<?= url('/admin/disputes/no-show') ?>" class="btn btn-sm btn-outline" style="width: 100%; justify-content: center; margin-top: 12px;">
 Resolve Dispute →
 </a>
 </div>
 </div>

 </div>

 <!-- Growth Chart & Recent Activity -->
 <div class="grid grid-cols-3 gap-6">
 <!-- Growth Chart -->
 <div class="card" style="grid-column: span 2;">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h3 class="card-title">Platform Weekly Booking Growth</h3>
 <span class="badge badge-confirmed">September 2026</span>
 </div>
 <div class="card-body">
 <div class="table-responsive"><table class="data-table"><thead><tr><th>Week</th><th>Total Bookings</th></tr></thead><tbody><tr><td>Week 1</td><td>142</td></tr><tr><td>Week 2</td><td>198</td></tr><tr><td>Week 3</td><td>260</td></tr><tr><td>Week 4</td><td>318</td></tr></tbody></table></div>
 </div>
 </div>

 <!-- System Status -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">System Health & Services</h3>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 14px;">
 <div style="display: flex; justify-content: space-between; align-items: center;">
 <div>
 <div style="font-weight: 600; font-size: 13px;">PayHere Payment Gateway</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Latency 142ms</div>
 </div>
 <span class="badge badge-confirmed">Operational</span>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: center;">
 <div>
 <div style="font-weight: 600; font-size: 13px;">Dialog SMS Gateway</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">99.98% delivery rate</div>
 </div>
 <span class="badge badge-confirmed">Operational</span>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: center;">
 <div>
 <div style="font-weight: 600; font-size: 13px;">Reliability Scoring Engine</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Automated nightly batch</div>
 </div>
 <span class="badge badge-confirmed">Active</span>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: center;">
 <div>
 <div style="font-weight: 600; font-size: 13px;">Resale Marketplace Daemon</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Cap enforcement <= 90%</div>
 </div>
 <span class="badge badge-confirmed">Active</span>
 </div>
 </div>
 </div>
 </div>
 </div>

 
 


