<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Colombo Futsal Club</span>
 </div>
 <div style="display: flex; align-items: center; gap: 10px;">
 <h1 class="page-title">Colombo Futsal Club (Venue CRUD Read)</h1>
 <span class="badge badge-active">Active</span>
 <span class="badge badge-verified">Admin Approved</span>
 </div>
 <div class="page-subtitle"> Clean Shareable Public URL: <strong>platform.lk/venue/colombofutsal</strong></div>
 </div>

 <div style="display: flex; gap: 8px;">
 <a href="<?= url('/owner/venues/1/edit') ?>" class="btn btn-outline">
 Edit Venue Info
 </a>
 <a href="<?= url('/owner/courts?venue=1') ?>" class="btn btn-primary">
 Manage Courts (3) &rarr;
 </a>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
 
 <!-- Left: Venue Details & Courts Summary -->
 <div>
 <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-6);">
 <h3 style="font-size: 16px; margin-bottom: 12px;">Venue Profile & Contact</h3>
 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13px;">
 <div>
 <span class="text-muted">Street Address:</span>
 <div style="font-weight: 600;">No. 42 Marine Drive, Dehiwala</div>
 </div>
 <div>
 <span class="text-muted">Direct Phone:</span>
 <div style="font-weight: 600;">+94 11 273 8910</div>
 </div>
 <div>
 <span class="text-muted">Primary Sports:</span>
 <div style="font-weight: 600;">Futsal, Badminton</div>
 </div>
 <div>
 <span class="text-muted">Operating Hours:</span>
 <div style="font-weight: 600;">06:00 AM - 11:00 PM (Daily)</div>
 </div>
 </div>
 </div>

 <!-- Courts Inventory -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Court Inventory (3)</h3>
 <a href="<?= url('/owner/courts') ?>" style="font-size: 13px; font-weight: 600;">Add Court &rarr;</a>
 </div>
 <div class="card-body" style="padding: 0;">
 <div style="padding: 14px 20px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
 <div>
 <strong>Turf Court 1 (Floodlit)</strong>
 <div class="text-xs text-muted">Synthetic Grass · LKR 5,000 / hr</div>
 </div>
 <span class="badge badge-active">Active</span>
 </div>
 <div style="padding: 14px 20px; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
 <div>
 <strong>Turf Court 2 (Indoor)</strong>
 <div class="text-xs text-muted">Synthetic Turf · LKR 4,500 / hr</div>
 </div>
 <span class="badge badge-active">Active</span>
 </div>
 <div style="padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;">
 <div>
 <strong>Wooden Badminton Court A</strong>
 <div class="text-xs text-muted">Teak Wood · LKR 2,500 / hr</div>
 </div>
 <span class="badge badge-active">Active</span>
 </div>
 </div>
 </div>
 </div>

 <!-- Right: Actions & Status -->
 <div>
 <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6);">
 <h3 style="font-size: 15px; margin-bottom: 12px;">Quick Management Actions</h3>
 <div style="display: flex; flex-direction: column; gap: 8px;">
 <a href="<?= url('/owner/slots') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">
 Block Court Slots / Walk-in
 </a>
 <a href="<?= url('/owner/flash-slots') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">
 Create Flash Deal
 </a>
 <a href="<?= url('/owner/announcements') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">
 Post Announcement
 </a>
 <a href="<?= url('/owner/coach-requests') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">
 Review Coach Approvals
 </a>
 <div style="border-top: 1px solid var(--color-border); margin: 8px 0;"></div>
 <a href="<?= url('/owner/venues/1/deactivate') ?>" class="btn btn-secondary btn-block" style="color: var(--color-danger); justify-content: flex-start;">
 Deactivate Venue...
 </a>
 </div>
 </div>
 </div>
</div>

