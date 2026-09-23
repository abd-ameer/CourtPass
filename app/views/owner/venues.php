<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Venues</span>
 </div>
 <h1 class="page-title">My Sports Facilities</h1>
 <div class="page-subtitle">Manage venue profiles, shareable public URLs, and court inventories.</div>
 </div>

 <a href="<?= url('/owner/add-venue') ?>" class="btn btn-primary">
 + Register New Venue
 </a>
 </div>

 <!-- Venues Grid -->
 <div class="grid grid-cols-2 gap-6">
 
 <!-- Venue 1: Active -->
 <div class="card">
 <div style="height: 180px; position: relative; overflow: hidden;">
 <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80" alt="Colombo Futsal Club" style="width: 100%; height: 100%; object-fit: cover;">
 <div style="position: absolute; top: 12px; left: 12px;">
 <span class="badge badge-active">Active</span>
 <span class="badge badge-verified">Admin Approved</span>
 </div>
 <div class="venue-price-badge">Shareable: platform.lk/venue/colombofutsal</div>
 </div>
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
 <div>
 <h3 style="font-size: 18px; margin-bottom: 2px;">Colombo Futsal Club</h3>
 <div class="text-xs text-muted"> No. 42 Marine Drive, Dehiwala · 3 Courts</div>
 </div>
 <span style="font-weight: 700; color: #d97706; font-size: 13px;"> 4.8 (124)</span>
 </div>
 <p class="text-sm text-muted" style="margin-bottom: 16px;">
 Premier synthetic turf by the coast. Operating 06:00 AM - 11:00 PM daily.
 </p>
 <div style="display: flex; gap: 8px; flex-wrap: wrap; border-top: 1px solid var(--color-border); padding-top: 14px;">
 <a href="<?= url('/owner/venue-details') ?>?id=venue-1" class="btn btn-sm btn-outline flex-1">Overview</a>
 <a href="<?= url('/owner/edit-venue') ?>?id=venue-1" class="btn btn-sm btn-outline flex-1">Edit Info</a>
 <a href="<?= url('/owner/courts') ?>?venue=venue-1" class="btn btn-sm btn-primary flex-1">Manage Courts</a>
 <a href="<?= url('/owner/delete-venue') ?>?id=venue-1" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">Deactivate</a>
 </div>
 </div>
 </div>

 <!-- Venue 2: Pending Approval -->
 <div class="card" style="border: 1.5px dashed #fde68a;">
 <div style="height: 180px; position: relative; overflow: hidden; background: #e2e8f0; display: flex; align-items: center; justify-content: center;">
 <div style="font-size: 40px;"></div>
 <div style="position: absolute; top: 12px; left: 12px;">
 <span class="badge badge-pending">Pending Admin Review</span>
 </div>
 </div>
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
 <div>
 <h3 style="font-size: 18px; margin-bottom: 2px;">CFC Indoor Badminton Arena</h3>
 <div class="text-xs text-muted"> Hill Street, Dehiwala · 2 Courts</div>
 </div>
 </div>
 <p class="text-sm text-muted" style="margin-bottom: 16px;">
 New multi-court badminton annex currently undergoing admin registration review.
 </p>
 <div style="display: flex; gap: 8px; border-top: 1px solid var(--color-border); padding-top: 14px;">
 <span class="text-xs text-muted" style="line-height: 2;">Awaiting platform approval · ID: VEN-098</span>
 <a href="<?= url('/owner/edit-venue') ?>?id=venue-pending" class="btn btn-sm btn-outline" style="margin-left: auto;">Edit Details</a>
 </div>
 </div>
 </div>
