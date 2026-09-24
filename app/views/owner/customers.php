<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Customer Intelligence</span>
 </div>
 <h1 class="page-title">Customer Intelligence </h1>
 <div class="page-subtitle">Understand player booking habits, reliability scores, no-show histories, and top spenders.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Exporting Customer Directory (CSV)...')">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
 Export Directory
 </button>
 </div>
 </div>

 

 <!-- Search & Filters -->
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-primary">All Customers (124)</button>
 <button class="btn btn-sm btn-outline">Frequent Bookers (18)</button>
 <button class="btn btn-sm btn-outline">Restricted / High Risk (3)</button>
 </div>

 <div style="position: relative; width: 280px;">
 <input type="text" class="form-control" placeholder="Search by name, phone or email..." onkeyup="filterCustomerTable(this.value)" style="padding-left: 34px; font-size: var(--font-size-xs);">
 <svg style="position: absolute; left: 10px; top: 10px; color: var(--color-text-subtle);" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
 </div>
 </div>

 <!-- Customer Directory Table -->
 <div class="card">
 <div class="table-container">
 <table class="table" id="customers-table">
 <thead>
 <tr>
 <th>Customer</th>
 <th>Contact</th>
 <th>Reliability Score</th>
 <th>Bookings Here</th>
 <th>No-Shows</th>
 <th>Total Spend</th>
 <th>Pay-at-Venue Status</th>
 <th>Actions</th>
 </tr>
 </thead>
 <tbody>
 <!-- Customer 1 -->
 <tr>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #e0f2fe; color: #0284c7;">SJ</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Sahan Jayasuriya</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Member since Jun 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 444 8899</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">sahan.j@gmail.com</div>
 </td>
 <td>
 <div style="display: flex; align-items: center; gap: 6px;">
 <span class="badge badge-confirmed">98% Standard</span>
 </div>
 </td>
 <td><strong>14 bookings</strong></td>
 <td><span style="color: var(--color-success); font-weight: 700;">0</span></td>
 <td style="font-weight: 700; color: var(--color-text-heading);">LKR 72,000</td>
 <td><span class="badge badge-confirmed"> Eligible (Cash-on-Arrival)</span></td>
 <td>
 <button class="btn btn-sm btn-outline" onclick="viewCustomerModal('Sahan Jayasuriya', '98%', 14, 0, 'LKR 72,000')">Profile</button>
 </td>
 </tr>

 <!-- Customer 2 -->
 <tr>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #fef3c7; color: #d97706;">KM</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Kasun Mendis</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Member since Jul 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 123 4567</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">kasun.m@gmail.com</div>
 </td>
 <td>
 <div style="display: flex; align-items: center; gap: 6px;">
 <span class="badge badge-confirmed">92% Standard</span>
 </div>
 </td>
 <td><strong>8 bookings</strong></td>
 <td><span style="color: var(--color-success); font-weight: 700;">0</span></td>
 <td style="font-weight: 700; color: var(--color-text-heading);">LKR 38,000</td>
 <td><span class="badge badge-confirmed"> Eligible</span></td>
 <td>
 <button class="btn btn-sm btn-outline" onclick="viewCustomerModal('Kasun Mendis', '92%', 8, 0, 'LKR 38,000')">Profile</button>
 </td>
 </tr>

 <!-- Customer 3 (Restricted) -->
 <tr style="background: #fffafa;">
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #fee2e2; color: #dc2626;">DW</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Danushka Wickramasinghe</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Member since Aug 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 71 332 9981</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">danushka.w@gmail.com</div>
 </td>
 <td>
 <div style="display: flex; align-items: center; gap: 6px;">
 <span class="badge badge-danger">50% Restricted</span>
 </div>
 </td>
 <td><strong>4 bookings</strong></td>
 <td><span class="badge badge-danger">2 No-Shows</span></td>
 <td style="font-weight: 700; color: var(--color-text-heading);">LKR 10,000</td>
 <td><span class="badge badge-danger"> Online PayHere Only</span></td>
 <td>
 <button class="btn btn-sm btn-outline" onclick="viewCustomerModal('Danushka Wickramasinghe', '50%', 4, 2, 'LKR 10,000')">Profile</button>
 </td>
 </tr>

 <!-- Customer 4 (New Member) -->
 <tr>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="background: #ede9fe; color: #7c3aed;">NL</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Nuwan Liyanage</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Member since Sep 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 76 991 4452</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">nuwan.l@yahoo.com</div>
 </td>
 <td>
 <div style="display: flex; align-items: center; gap: 6px;">
 <span class="badge badge-warning">New (<5 Bookings)</span>
 </div>
 </td>
 <td><strong>2 bookings</strong></td>
 <td><span style="color: var(--color-success); font-weight: 700;">0</span></td>
 <td style="font-weight: 700; color: var(--color-text-heading);">LKR 10,000</td>
 <td><span class="badge badge-warning">Online PayHere Only</span></td>
 <td>
 <button class="btn btn-sm btn-outline" onclick="viewCustomerModal('Nuwan Liyanage', 'New', 2, 0, 'LKR 10,000')">Profile</button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 
 


<script>
function filterCustomerTable(query) {
 const q = query.toLowerCase();
 const rows = document.querySelectorAll('#customers-table tbody tr');
 rows.forEach(row => {
 const text = row.textContent.toLowerCase();
 row.style.display = text.includes(q) ? '' : 'none';
 });
}

function viewCustomerModal(name, score, bookings, noShows, spend) {
 CourtPassApp.showToast('info', 'Note', `Viewing intelligence profile for ${name}`);
}
</script>
