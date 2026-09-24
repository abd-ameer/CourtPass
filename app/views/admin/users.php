<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Users</span>
 </div>
 <h1 class="page-title">User Management </h1>
 <div class="page-subtitle">Manage registered customers, venue owners, certified coaches, and platform administrators.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Exporting User Directory CSV...')">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
 Export CSV
 </button>
 </div>
 </div>

 <!-- Filters & Search Toolbar -->
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; gap: 8px; flex-wrap: wrap;">
 <button class="btn btn-sm btn-primary user-filter-btn" onclick="filterUserRole('all', this)">All (1,480)</button>
 <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('customer', this)">Customers (1,390)</button>
 <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('owner', this)">Venue Owners (28)</button>
 <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('coach', this)">Coaches (42)</button>
 <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('suspended', this)">Suspended (12)</button>
 </div>

 <div style="position: relative; width: 280px;">
 <input type="text" class="form-control" placeholder="Search by name, email or phone..." onkeyup="filterUserTable(this.value)" style="padding-left: 34px; font-size: var(--font-size-xs);">
 <svg style="position: absolute; left: 10px; top: 10px; color: var(--color-text-subtle);" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
 </div>
 </div>

 <!-- User Directory Table -->
 <div class="card">
 <div class="table-container">
 <table class="table" id="users-table">
 <thead>
 <tr>
 <th>User Details</th>
 <th>Role</th>
 <th>Contact</th>
 <th>Reliability / Status</th>
 <th>Activity</th>
 <th>Joined</th>
 <th>Actions</th>
 </tr>
 </thead>
 <tbody>
 <!-- User 1: Customer -->
 <tr data-role="customer">
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #e0f2fe; color: #0284c7;">KM</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Kasun Mendis</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">ID: #USR-1092</div>
 </div>
 </div>
 </td>
 <td><span class="badge badge-primary">Customer</span></td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 123 4567</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">kasun.m@gmail.com</div>
 </td>
 <td><span class="badge badge-confirmed">98% Standard</span></td>
 <td>14 Bookings · 0 No-Shows</td>
 <td>12 Jun 2026</td>
 <td>
 <div style="display: flex; gap: 6px;">
 <a href="<?= url('/admin/users/7') ?>" class="btn btn-sm btn-outline">Inspect</a>
 </div>
 </td>
 </tr>

 <!-- User 2: Owner -->
 <tr data-role="owner">
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #dcfce7; color: #16a34a;">KM</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Kusal Mendis</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Colombo Futsal Club</div>
 </div>
 </div>
 </td>
 <td><span class="badge badge-confirmed">Venue Owner</span></td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 11 234 5678</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">owner@colombofutsal.lk</div>
 </td>
 <td><span class="badge badge-confirmed"> Verified BRN</span></td>
 <td>3 Courts · LKR 342k MTD</td>
 <td>12 Jan 2026</td>
 <td>
 <div style="display: flex; gap: 6px;">
 <a href="<?= url('/admin/users/2') ?>" class="btn btn-sm btn-outline">Inspect</a>
 </div>
 </td>
 </tr>

 <!-- User 3: Coach -->
 <tr data-role="coach">
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #ede9fe; color: #7c3aed;">DP</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Coach Dilshan Perera</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Badminton Specialist</div>
 </div>
 </div>
 </td>
 <td><span class="badge" style="background: #ede9fe; color: #7c3aed;">Coach</span></td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 123 4567</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">dilshan.coach@gmail.com</div>
 </td>
 <td><span class="badge badge-confirmed"> SLBA Level 2</span></td>
 <td>34 Sessions · 4.9 Rating</td>
 <td>18 Feb 2026</td>
 <td>
 <div style="display: flex; gap: 6px;">
 <a href="<?= url('/admin/users/4') ?>" class="btn btn-sm btn-outline">Inspect</a>
 </div>
 </td>
 </tr>

 <!-- User 4: Suspended / High Risk -->
 <tr data-role="suspended" style="background: #fff5f5;">
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #fee2e2; color: #dc2626;">DW</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Danushka Wickramasinghe</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">ID: #USR-1489</div>
 </div>
 </div>
 </td>
 <td><span class="badge badge-primary">Customer</span></td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 71 332 9981</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">danushka.w@gmail.com</div>
 </td>
 <td><span class="badge badge-danger">50% Restricted</span></td>
 <td>4 Bookings · 2 No-Shows</td>
 <td>14 Aug 2026</td>
 <td>
 <div style="display: flex; gap: 6px;">
 <a href="<?= url('/admin/users/6') ?>" class="btn btn-sm btn-outline">Inspect</a>
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger);" onclick="CourtPassApp.showToast('error', 'Error', 'User suspended!')">Ban</button>
 </div>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 
 


<script>
function filterUserRole(role, btn) {
 document.querySelectorAll('.user-filter-btn').forEach(b => {
 b.classList.remove('btn-primary');
 b.classList.add('btn-outline');
 });
 btn.classList.remove('btn-outline');
 btn.classList.add('btn-primary');

 const rows = document.querySelectorAll('#users-table tbody tr');
 rows.forEach(row => {
 if (role === 'all' || row.dataset.role === role) {
 row.style.display = '';
 } else {
 row.style.display = 'none';
 }
 });
}

function filterUserTable(query) {
 const q = query.toLowerCase();
 const rows = document.querySelectorAll('#users-table tbody tr');
 rows.forEach(row => {
 const text = row.textContent.toLowerCase();
 row.style.display = text.includes(q) ? '' : 'none';
 });
}
</script>
