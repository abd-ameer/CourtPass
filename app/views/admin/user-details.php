<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/admin/users') ?>">Users</a>
 <span class="breadcrumb-separator">/</span>
 <span>Kasun Mendis (#USR-1092)</span>
 </div>
 <h1 class="page-title">User Profile: Kasun Mendis </h1>
 <div class="page-subtitle">Inspect user booking history, reliability metrics, dispute records, and account controls.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Deactivate Account', 'The user loses access. A coach also has future sessions cancelled with full refunds.', 'Deactivate', '/admin/users/<?= (int) $userId ?>/deactivate')">
 Deactivate Account
 </button>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Left Column: User Summary & Reliability Score Override -->
 <div style="display: flex; flex-direction: column; gap: var(--space-6);">
 
 <div class="card">
 <div class="card-body" style="text-align: center; padding: var(--space-6);">
 <div class="user-avatar" style="width: 72px; height: 72px; font-size: 24px; margin: 0 auto var(--space-3); background: #e0f2fe; color: #0284c7;">
 KM
 </div>
 <h3 style="font-size: 18px; font-weight: 800; color: var(--color-text-heading); margin-bottom: 4px;">Kasun Mendis</h3>
 <div style="display: flex; justify-content: center; gap: 6px; margin-bottom: var(--space-4);">
 <span class="badge badge-primary">Customer</span>
 <span class="badge badge-confirmed">Active</span>
 </div>

 <div style="text-align: left; font-size: var(--font-size-xs); display: flex; flex-direction: column; gap: 8px; border-top: 1px solid var(--color-border); padding-top: var(--space-4);">
 <div><span style="color: var(--color-text-subtle);">Email:</span> <strong>kasun.m@gmail.com</strong></div>
 <div><span style="color: var(--color-text-subtle);">Phone:</span> <strong>+94 77 123 4567</strong></div>
 <div><span style="color: var(--color-text-subtle);">Joined:</span> <strong>12 Jun 2026</strong></div>
 </div>
 </div>
 </div>

 <!-- Reliability Tier Management -->
 <div class="card" style="border-left: 4px solid var(--color-primary-active);">
 <div class="card-header">
 <h3 class="card-title">Reliability Score Engine</h3>
 </div>
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-3);">
 <span style="font-size: var(--font-size-xs); color: var(--color-text-subtle);">CURRENT COMPUTED SCORE:</span>
 <span style="font-size: 22px; font-weight: 900; color: var(--color-primary-active);">98%</span>
 </div>

 <div style="font-size: 11px; color: var(--color-text-muted); margin-bottom: var(--space-4); line-height: 1.5;">
 Tier: <strong style="color: var(--color-text-heading);">Standard (>=70%)</strong><br>
 Cash-on-Arrival Privilege: <span class="badge badge-confirmed" style="display: inline;">Active</span>
 </div>

 <div class="form-group" style="margin-bottom: var(--space-3);">
 <label class="form-label" style="font-size: 11px;">Manual Score Override (%)</label>
 <input type="number" class="form-control" value="98" min="0" max="100" id="override-score">
 </div>

 <button class="btn btn-sm btn-outline" style="width: 100%; justify-content: center;" onclick="CourtPassApp.showToast('success', 'Done', 'Reliability score manually adjusted!')">
 Apply Score Override
 </button>
 </div>
 </div>

 </div>

 <!-- Right 2 Cols: Activity History Tabs -->
 <div style="grid-column: span 2; display: flex; flex-direction: column; gap: var(--space-6);">
 
 <div class="card">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h3 class="card-title">Booking & Attendance Activity (Last 5 Bookings)</h3>
 <span class="badge badge-confirmed">14 Total Bookings</span>
 </div>
 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Ref / Date</th>
 <th>Venue & Court</th>
 <th>Payment Mode</th>
 <th>Amount</th>
 <th>Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong style="color: var(--color-text-heading);">#CP-#1</strong>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Today, 20:00</div>
 </td>
 <td>
 <div style="font-weight: 600; font-size: 12px;">Colombo Futsal Club</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Turf Court 1</div>
 </td>
 <td><span class="badge badge-primary">PayHere Online</span></td>
 <td><strong>LKR 5,000</strong></td>
 <td><span class="badge badge-confirmed">Confirmed</span></td>
 </tr>

 <tr>
 <td>
 <strong style="color: var(--color-text-heading);">#CP-#1</strong>
 <div style="font-size: 11px; color: var(--color-text-subtle);">18 Sep 2026</div>
 </td>
 <td>
 <div style="font-weight: 600; font-size: 12px;">CR&FC Badminton</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Court 1</div>
 </td>
 <td><span class="badge badge-primary">PayHere Online</span></td>
 <td><strong>LKR 2,500</strong></td>
 <td><span class="badge badge-confirmed"> Completed</span></td>
 </tr>

 <tr>
 <td>
 <strong style="color: var(--color-text-heading);">#CP-#1</strong>
 <div style="font-size: 11px; color: var(--color-text-subtle);">12 Sep 2026</div>
 </td>
 <td>
 <div style="font-weight: 600; font-size: 12px;">Colombo Futsal Club</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Turf Court 2</div>
 </td>
 <td><span class="badge badge-confirmed">Cash-on-Arrival</span></td>
 <td><strong>LKR 4,500</strong></td>
 <td><span class="badge badge-confirmed"> Checked-In & Paid</span></td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Dispute / Penalty Audit -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Penalty & Dispute History</h3>
 </div>
 <div class="card-body">
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 0;">
 No unresolved disputes or no-show penalties found on record for this user account.
 </p>
 </div>
 </div>

 </div>

 </div>
