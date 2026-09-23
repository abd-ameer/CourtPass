<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Edit Venue</span>
 </div>
 <h1 class="page-title">Edit Venue Details (Venue CRUD Update)</h1>
 <div class="page-subtitle">Update operating contact, amenities, and facility information.</div>
 </div>
 </div>

 <div class="card" style="max-width: 680px; padding: var(--space-8); margin: 0 auto;">
 <form onsubmit="handleEditVenueSubmit(event)">
 
 <div class="form-group">
 <label class="form-label">Venue Name <span class="required-star">*</span></label>
 <input type="text" class="form-control" value="Colombo Futsal Club" required>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
 <div class="form-group">
 <label class="form-label">City / Area</label>
 <input type="text" class="form-control" value="Dehiwala" required>
 </div>
 <div class="form-group">
 <label class="form-label">Contact Phone</label>
 <input type="tel" class="form-control" value="+94 11 273 8910" required>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Physical Address</label>
 <input type="text" class="form-control" value="No. 42 Marine Drive, Dehiwala, Colombo" required>
 </div>

 <div class="form-group">
 <label class="form-label">Tagline & Highlight</label>
 <input type="text" class="form-control" value="Premier FIFA-standard synthetic turf by the coast">
 </div>

 <div class="form-group">
 <label class="form-label">Amenities</label>
 <div style="display: flex; gap: 12px; flex-wrap: wrap;">
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Floodlights</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Changing Rooms</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Showers</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Parking</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Café</label>
 </div>
 </div>

 <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
 <a href="<?= url('/owner/venue-details') ?>?id=venue-1" class="btn btn-secondary">Cancel</a>
 <button type="submit" class="btn btn-primary">Save Changes &rarr;</button>
 </div>
 </form>
 </div>

 </div>
 </div>
</div>

<script>
function handleEditVenueSubmit(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Venue Updated (UC-VO-01 Update)', 'Venue details saved successfully.');
 setTimeout(() => {
 window.location.href = '<?= url('/owner/venue-details') ?>?id=venue-1';
 }, 1000);
}
</script>
