<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <span>Sessions</span>
 </div>
 <h1 class="page-title">My Coaching Sessions</h1>
 <div class="page-subtitle">Manage all your scheduled, completed, and cancelled sessions across venues.</div>
 </div>
 <a href="<?= url('/coach/create-session') ?>" class="btn btn-primary">
 + Create New Session
 </a>
 </div>

 <!-- Session Filters -->
 <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
 <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
 <div class="tab-group">
 <button class="tab active" onclick="filterSessions('all', this)">All Sessions (7)</button>
 <button class="tab" onclick="filterSessions('upcoming', this)">Upcoming (3)</button>
 <button class="tab" onclick="filterSessions('completed', this)">Completed (3)</button>
 <button class="tab" onclick="filterSessions('cancelled', this)">Cancelled (1)</button>
 </div>
 <div style="margin-left: auto;">
 <select class="form-input" style="max-width: 200px; height: 36px; font-size: 13px;">
 <option>All Venues</option>
 <option>CR&FC Badminton Complex</option>
 <option>Royal College Sports Complex</option>
 </select>
 </div>
 </div>
 </div>

 <!-- Sessions List -->
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Session</th>
 <th>Venue & Court</th>
 <th>Date & Time</th>
 <th>Registrations</th>
 <th>Type</th>
 <th>Status</th>
 <th style="text-align: right;">Actions</th>
 </tr>
 </thead>
 <tbody id="sessionsTableBody">
 <!-- Upcoming -->
 <tr data-status="upcoming">
 <td>
 <strong>Badminton Fundamentals</strong>
 <div class="text-xs text-muted">#CS-201 · LKR 1,500/person</div>
 </td>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Court 2 (Wooden)</div>
 </td>
 <td>
 <strong>Sept 22, 2026</strong>
 <div class="text-xs text-muted">05:00 PM - 06:00 PM</div>
 </td>
 <td>
 <strong>6 / 8</strong>
 <div class="text-xs" style="color: var(--color-primary);">2 spots left</div>
 </td>
 <td><span class="badge badge-confirmed">Public</span></td>
 <td><span class="badge badge-confirmed">Upcoming</span></td>
 <td style="text-align: right;">
 <div style="display: flex; gap: 6px; justify-content: flex-end;">
 <a href="<?= url('/coach/session-details') ?>?id=CS-201" class="btn btn-sm btn-outline">View</a>
 <a href="<?= url('/coach/edit-session') ?>?id=CS-201" class="btn btn-sm btn-outline">Edit</a>
 <a href="<?= url('/coach/attendance') ?>?id=CS-201" class="btn btn-sm btn-primary">Attendance</a>
 </div>
 </td>
 </tr>
 <tr data-status="upcoming">
 <td>
 <strong>Advanced Doubles Strategy</strong>
 <div class="text-xs text-muted">#CS-205 · LKR 2,000/person</div>
 </td>
 <td>
 <strong>Royal College Sports Complex</strong>
 <div class="text-xs text-muted">Court 1 (Synthetic)</div>
 </td>
 <td>
 <strong>Sept 22, 2026</strong>
 <div class="text-xs text-muted">07:00 PM - 08:00 PM</div>
 </td>
 <td>
 <strong>4 / 4</strong>
 <div class="text-xs" style="color: var(--color-danger);">Full</div>
 </td>
 <td><span class="badge badge-warning">Private</span></td>
 <td><span class="badge badge-confirmed">Upcoming</span></td>
 <td style="text-align: right;">
 <div style="display: flex; gap: 6px; justify-content: flex-end;">
 <a href="<?= url('/coach/session-details') ?>?id=CS-205" class="btn btn-sm btn-outline">View</a>
 <a href="<?= url('/coach/edit-session') ?>?id=CS-205" class="btn btn-sm btn-outline">Edit</a>
 <a href="<?= url('/coach/attendance') ?>?id=CS-205" class="btn btn-sm btn-primary">Attendance</a>
 </div>
 </td>
 </tr>
 <tr data-status="upcoming">
 <td>
 <strong>Footwork Drills Intensive</strong>
 <div class="text-xs text-muted">#CS-208 · LKR 1,500/person</div>
 </td>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Court 1 (Synthetic)</div>
 </td>
 <td>
 <strong>Sept 24, 2026</strong>
 <div class="text-xs text-muted">06:00 PM - 07:00 PM</div>
 </td>
 <td>
 <strong>3 / 6</strong>
 <div class="text-xs" style="color: var(--color-primary);">3 spots left</div>
 </td>
 <td><span class="badge badge-confirmed">Public</span></td>
 <td><span class="badge badge-confirmed">Upcoming</span></td>
 <td style="text-align: right;">
 <div style="display: flex; gap: 6px; justify-content: flex-end;">
 <a href="<?= url('/coach/session-details') ?>?id=CS-208" class="btn btn-sm btn-outline">View</a>
 <a href="<?= url('/coach/edit-session') ?>?id=CS-208" class="btn btn-sm btn-outline">Edit</a>
 <a href="<?= url('/coach/cancel-session') ?>?id=CS-208" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">Cancel</a>
 </div>
 </td>
 </tr>

 <!-- Completed -->
 <tr data-status="completed">
 <td>
 <strong>Beginner Badminton</strong>
 <div class="text-xs text-muted">#CS-190 · LKR 1,200/person</div>
 </td>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Court 3 (Training)</div>
 </td>
 <td>
 <strong>Sept 19, 2026</strong>
 <div class="text-xs text-muted">05:00 PM - 06:00 PM</div>
 </td>
 <td><strong>7 / 8</strong></td>
 <td><span class="badge badge-confirmed">Public</span></td>
 <td><span class="badge" style="background: #dbeafe; color: #1d4ed8;">Completed</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/coach/session-details') ?>?id=CS-190" class="btn btn-sm btn-outline">View Report</a>
 </td>
 </tr>
 <tr data-status="completed">
 <td>
 <strong>Smash Technique Workshop</strong>
 <div class="text-xs text-muted">#CS-185 · LKR 1,800/person</div>
 </td>
 <td>
 <strong>Royal College Sports Complex</strong>
 <div class="text-xs text-muted">Court 1 (Synthetic)</div>
 </td>
 <td>
 <strong>Sept 17, 2026</strong>
 <div class="text-xs text-muted">07:00 PM - 08:00 PM</div>
 </td>
 <td><strong>5 / 6</strong></td>
 <td><span class="badge badge-warning">Private</span></td>
 <td><span class="badge" style="background: #dbeafe; color: #1d4ed8;">Completed</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/coach/session-details') ?>?id=CS-185" class="btn btn-sm btn-outline">View Report</a>
 </td>
 </tr>
 <tr data-status="completed">
 <td>
 <strong>Youth Development Camp</strong>
 <div class="text-xs text-muted">#CS-180 · LKR 1,000/person</div>
 </td>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Court 2 (Wooden)</div>
 </td>
 <td>
 <strong>Sept 15, 2026</strong>
 <div class="text-xs text-muted">04:00 PM - 05:00 PM</div>
 </td>
 <td><strong>10 / 10</strong></td>
 <td><span class="badge badge-confirmed">Public</span></td>
 <td><span class="badge" style="background: #dbeafe; color: #1d4ed8;">Completed</span></td>
 <td style="text-align: right;">
 <a href="<?= url('/coach/session-details') ?>?id=CS-180" class="btn btn-sm btn-outline">View Report</a>
 </td>
 </tr>

 <!-- Cancelled -->
 <tr data-status="cancelled">
 <td>
 <strong>Rally Practice</strong>
 <div class="text-xs text-muted">#CS-178 · LKR 1,500/person</div>
 </td>
 <td>
 <strong>Royal College Sports Complex</strong>
 <div class="text-xs text-muted">Court 2 (Concrete)</div>
 </td>
 <td>
 <strong>Sept 12, 2026</strong>
 <div class="text-xs text-muted">06:00 PM - 07:00 PM</div>
 </td>
 <td><strong>1 / 6</strong></td>
 <td><span class="badge badge-confirmed">Public</span></td>
 <td><span class="badge badge-cancelled">Cancelled</span></td>
 <td style="text-align: right;">
 <span class="text-xs text-muted">1 refund issued</span>
 </td>
 </tr>
 </tbody>
 </table>
 </div>

 </div>
 </div>
</div>

<script>
function filterSessions(status, btn) {
 document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
 btn.classList.add('active');
 
 document.querySelectorAll('#sessionsTableBody tr').forEach(row => {
 if (status === 'all' || row.dataset.status === status) {
 row.style.display = '';
 } else {
 row.style.display = 'none';
 }
 });
}
</script>
