<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/admin/venue-approvals') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Colombo Futsal Club</span>
 </div>
 <h1 class="page-title">Venue Inspection: Colombo Futsal Club </h1>
 <div class="page-subtitle">Inspect registered courts, owner details, review statistics, and toggle administrative status.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="toggleVenueSuspension()">
 Suspend Venue
 </button>
 <a href="<?= url('/venue-details') ?>?id=venue-1" target="_blank" class="btn btn-primary">
 View Public Listing ↗
 </a>
 </div>
 </div>

 <!-- Quick Status Banner -->
 <div class="card" style="margin-bottom: var(--space-6); background: #e6f8f0; border: 1px solid #a7f3d0;">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; align-items: center; gap: 12px;">
 <span class="badge badge-confirmed" style="font-size: 13px; padding: 6px 14px;">Status: ACTIVE & VERIFIED</span>
 <span style="font-size: var(--font-size-xs); color: var(--color-text-body);">Approved on 12 Jan 2026 by Admin (ref #ADM-V-001)</span>
 </div>
 <div style="font-size: var(--font-size-xs); font-weight: 700; color: var(--color-primary-active);">
 Platform Commission: 5.0% Standard
 </div>
 </div>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Left 2 Cols: Venue & Courts Detail -->
 <div style="grid-column: span 2; display: flex; flex-direction: column; gap: var(--space-6);">

 <!-- General Details -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Venue Profile Information</h3>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Venue Name</span>
 <div style="font-weight: 700; font-size: 14px; color: var(--color-text-heading);">Colombo Futsal Club</div>
 </div>
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Location / City</span>
 <div style="font-weight: 600; font-size: 14px; color: var(--color-text-heading);">24 De Alwis Place, Dehiwala, Colombo</div>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Sports Handled</span>
 <div style="margin-top: 4px; display: flex; gap: 6px;">
 <span class="badge badge-confirmed">Futsal</span>
 <span class="badge badge-primary">Badminton</span>
 </div>
 </div>
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Operating Hours</span>
 <div style="font-weight: 600; font-size: 13px; color: var(--color-text-heading); margin-top: 4px;">06:00 AM - 11:00 PM (Daily)</div>
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Description</span>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-top: 4px; line-height: 1.5;">
 Premier multi-sport complex in Dehiwala featuring FIFA standard synthetic turf futsal courts with stadium lighting, high-grip wooden badminton court, pro-shop, and shower facilities.
 </p>
 </div>
 </div>
 </div>

 <!-- Courts List -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Registered Courts (3)</h3>
 </div>
 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Court Name</th>
 <th>Sport</th>
 <th>Surface</th>
 <th>Hourly Rate</th>
 <th>Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><strong>Turf Court 1 (Floodlit)</strong></td>
 <td><span class="badge badge-confirmed">Futsal</span></td>
 <td>Synthetic Turf (Outdoor)</td>
 <td><strong>LKR 5,000</strong></td>
 <td><span class="badge badge-confirmed">Active</span></td>
 </tr>
 <tr>
 <td><strong>Turf Court 2 (Indoor)</strong></td>
 <td><span class="badge badge-confirmed">Futsal</span></td>
 <td>Synthetic Turf (Covered)</td>
 <td><strong>LKR 4,500</strong></td>
 <td><span class="badge badge-confirmed">Active</span></td>
 </tr>
 <tr>
 <td><strong>Wooden Badminton Court A</strong></td>
 <td><span class="badge badge-primary">Badminton</span></td>
 <td>Teak Hardwood</td>
 <td><strong>LKR 2,500</strong></td>
 <td><span class="badge badge-confirmed">Active</span></td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 </div>

 <!-- Right Column: Owner & Admin Parameters -->
 <div style="display: flex; flex-direction: column; gap: var(--space-6);">
 
 <!-- Owner Contact Card -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Owner Information</h3>
 </div>
 <div class="card-body">
 <div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--space-4);">
 <div class="user-avatar" style="width: 44px; height: 44px; background: #e0f2fe; color: #0284c7;">KM</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Kusal Mendis</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Managing Partner</div>
 </div>
 </div>

 <div style="font-size: var(--font-size-xs); display: flex; flex-direction: column; gap: 8px;">
 <div>
 <span style="color: var(--color-text-subtle);">Email:</span>
 <strong style="color: var(--color-text-heading);"> owner@colombofutsal.lk</strong>
 </div>
 <div>
 <span style="color: var(--color-text-subtle);">Phone:</span>
 <strong style="color: var(--color-text-heading);"> +94 11 234 5678</strong>
 </div>
 <div>
 <span style="color: var(--color-text-subtle);">Business Reg (BRN):</span>
 <strong style="color: var(--color-text-heading);"> PV-00239108</strong>
 </div>
 <div>
 <span style="color: var(--color-text-subtle);">PayHere ID:</span>
 <strong style="color: var(--color-text-heading);"> 1224890 (Active)</strong>
 </div>
 </div>
 </div>
 </div>

 <!-- Commission & Policy Settings -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Administrative Overrides</h3>
 </div>
 <div class="card-body">
 <div class="form-group" style="margin-bottom: var(--space-3);">
 <label class="form-label">Platform Take Rate (%)</label>
 <input type="number" class="form-control" value="5.0" step="0.5" min="0" max="25">
 </div>

 <div class="form-group" style="margin-bottom: var(--space-4);">
 <label class="form-label">Cash-on-Arrival Support</label>
 <select class="form-control">
 <option selected>Enabled for Standard Users (>=70%)</option>
 <option>Disabled (Online Payments Only)</option>
 <option>Enabled for All Users</option>
 </select>
 </div>

 <button class="btn btn-sm btn-outline" style="width: 100%; justify-content: center;" onclick="CourtPassApp.showToast('Administrative parameters updated!', 'success')">
 Save Parameter Overrides
 </button>
 </div>
 </div>

 </div>

 </div>

 </div>
 </div>
</div>

<script>
function toggleVenueSuspension() {
 const reason = prompt('Please specify reason for suspending this venue (e.g. Safety complaints, unpaid disputes):');
 if (reason) {
 CourtPassApp.showToast('Venue suspended from public discovery and slot booking.', 'error');
 }
}
</script>
