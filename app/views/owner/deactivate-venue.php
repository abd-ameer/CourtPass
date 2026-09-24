<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Deactivate Venue</span>
 </div>
 <h1 class="page-title">Deactivate Venue </h1>
 <div class="page-subtitle">Temporarily remove the venue from public listings.</div>
 </div>
 </div>

 <div class="card" style="max-width: 650px; padding: var(--space-8); margin: 0 auto; border: 2px solid var(--color-danger-border);">
 <div style="display: flex; gap: 16px; margin-bottom: var(--space-6);">
 <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-danger-bg); color: var(--color-danger); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
 
 </div>
 <div>
 <h3 style="font-size: 18px; margin-bottom: 4px;">Deactivation Protocol (UC-VO-13)</h3>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 10px;">
 Deactivating <strong>this venue</strong> will delist the venue from public searches.
 </p>
 <div style="background: var(--color-bg-subtle); padding: 12px 16px; border-radius: var(--radius-md); font-size: 12px; color: var(--color-text-muted); line-height: 1.5;">
 <strong>Preservation Guarantee:</strong> All existing confirmed customer bookings (e.g. Sept 24 & Sept 25) and approved coaching sessions will be <strong>strictly preserved</strong>.<br>
 <strong>Lock:</strong> No new customer reservations, coaching clinics, or flash deals can be created until reactivated.
 </div>
 </div>
 </div>

 <form method="POST" action="<?= url('/owner/venues/' . $venueId . '/deactivate') ?>" onsubmit="return document.getElementById('confirmKeywordInput').value === 'DEACTIVATE' || (alert('Type DEACTIVATE to confirm.'), false);">
 <?= csrf_field() ?>
 <div class="form-group">
 <label class="form-label">Type "DEACTIVATE" to Confirm</label>
 <input type="text" id="confirmKeywordInput" class="form-control" placeholder="DEACTIVATE" required>
 </div>

 <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
 <a href="<?= url('/owner/venues/' . $venueId) ?>" class="btn btn-secondary">Cancel</a>
 <button type="submit" class="btn btn-danger">Confirm Deactivation</button>
 </div>
 </form>
 </div>

