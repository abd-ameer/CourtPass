<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/customer/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Colombo Futsal Club</span>
 </div>
 <div style="display: flex; align-items: center; gap: 10px;">
 <h1 class="page-title">Colombo Futsal Club</h1>
 <span class="badge badge-active">Active</span>
 <span class="badge badge-verified">Verified</span>
 </div>
 <div class="page-subtitle"> No. 42 Marine Drive, Dehiwala · 06:00 AM - 11:00 PM Daily</div>
 </div>

 <div>
 <a href="<?= url('/customer/court-availability') ?>?court=c1" class="btn btn-primary">
 + Reserve a Court Slot
 </a>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Court 1 -->
 <div class="card card-hover">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge badge-confirmed"> Futsal</span>
 <span class="badge badge-available">Open Slots Today</span>
 </div>
 <h3 style="font-size: 18px; margin-bottom: 4px;">Turf Court 1 (Floodlit)</h3>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 14px;">
 FIFA-grade synthetic turf with optimal evening floodlighting.
 </p>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-border-subtle);">
 <div>
 <div style="font-size: 18px; font-weight: 800; color: var(--color-text-title);">LKR 5,000</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per 1 hr slot</div>
 </div>
 <a href="<?= url('/customer/court-availability') ?>?court=c1" class="btn btn-primary btn-sm">Select Slot &rarr;</a>
 </div>
 </div>
 </div>

 <!-- Court 2 -->
 <div class="card card-hover">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge badge-confirmed"> Futsal</span>
 <span class="badge badge-flash"> Flash Deal: 30% OFF</span>
 </div>
 <h3 style="font-size: 18px; margin-bottom: 4px;">Turf Court 2 (Indoor)</h3>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 14px;">
 Weatherproof synthetic turf with roof and side netting.
 </p>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-border-subtle);">
 <div>
 <div style="font-size: 18px; font-weight: 800; color: var(--color-text-title);">LKR 4,500</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per 1 hr slot</div>
 </div>
 <a href="<?= url('/customer/court-availability') ?>?court=c2" class="btn btn-primary btn-sm">Select Slot &rarr;</a>
 </div>
 </div>
 </div>

 <!-- Court 3 -->
 <div class="card card-hover">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge badge-confirmed"> Badminton</span>
 <span class="badge badge-available">Open Slots Today</span>
 </div>
 <h3 style="font-size: 18px; margin-bottom: 4px;">Wooden Badminton Court A</h3>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 14px;">
 Teak hardwood flooring with glare-free high ceiling lighting.
 </p>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--color-border-subtle);">
 <div>
 <div style="font-size: 18px; font-weight: 800; color: var(--color-text-title);">LKR 2,500</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per 1 hr slot</div>
 </div>
 <a href="<?= url('/customer/court-availability') ?>?court=c3" class="btn btn-primary btn-sm">Select Slot &rarr;</a>
 </div>
 </div>
 </div>
