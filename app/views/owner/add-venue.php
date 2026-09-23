<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Add Venue</span>
 </div>
 <h1 class="page-title">Register New Sports Facility (Venue CRUD Create)</h1>
 <div class="page-subtitle">Submit venue information and court specs for Platform Admin review.</div>
 </div>
 </div>

 <div class="card" style="max-width: 720px; padding: var(--space-8); margin: 0 auto;">
 
 <form id="addVenueForm" onsubmit="handleAddVenueSubmit(event)">
 
 <div class="form-group">
 <label class="form-label">Venue Name <span class="required-star">*</span></label>
 <input type="text" name="name" class="form-control" placeholder="e.g., Colombo Smash Arena" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
 <div class="form-group">
 <label class="form-label">City / Area <span class="required-star">*</span></label>
 <select name="city" class="form-select" required>
 <option value="">Select area...</option>
 <option>Colombo 07</option>
 <option>Dehiwala</option>
 <option>Rajagiriya</option>
 <option>Battaramulla</option>
 <option>Malabe</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Contact Phone <span class="required-star">*</span></label>
 <input type="tel" name="phone" class="form-control" placeholder="+94 11 273 8910" required>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Physical Address <span class="required-star">*</span></label>
 <input type="text" name="address" class="form-control" placeholder="Street number, road, landmark..." required>
 </div>

 <div class="form-group">
 <label class="form-label">Sports Accommodated <span class="required-star">*</span></label>
 <div style="display: flex; gap: 12px; flex-wrap: wrap;">
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" name="sports[]" value="badminton" checked> Badminton</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" name="sports[]" value="futsal"> Futsal</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" name="sports[]" value="pickleball"> Pickleball</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" name="sports[]" value="squash"> Squash</label>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Standard Operating Hours</label>
 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <input type="text" class="form-control" value="06:00 AM" placeholder="Opening Time">
 <input type="text" class="form-control" value="11:00 PM" placeholder="Closing Time">
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Facility Amenities</label>
 <div style="display: flex; gap: 10px; flex-wrap: wrap;">
 <label class="inline-flex items-center gap-1" style="font-size: 12px;"><input type="checkbox" checked> Floodlights</label>
 <label class="inline-flex items-center gap-1" style="font-size: 12px;"><input type="checkbox" checked> Changing Rooms</label>
 <label class="inline-flex items-center gap-1" style="font-size: 12px;"><input type="checkbox" checked> Showers</label>
 <label class="inline-flex items-center gap-1" style="font-size: 12px;"><input type="checkbox" checked> Parking</label>
 <label class="inline-flex items-center gap-1" style="font-size: 12px;"><input type="checkbox"> Café / Snacks</label>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Venue Description & Player Rules</label>
 <textarea class="form-control" rows="3" placeholder="Non-marking shoes required, cancellation guidelines, etc..."></textarea>
 </div>

 <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: var(--radius-md); padding: 12px; margin-bottom: 20px; font-size: 12px; color: #92400e;">
 ℹ️ <strong>Admin Approval Required (UC-VO-01, UC-VO-02):</strong> Upon submission, the venue status is set to <strong>Pending</strong>. Platform Admin will review credentials before making the venue public.
 </div>

 <div style="display: flex; justify-content: flex-end; gap: 12px;">
 <a href="<?= url('/owner/venues') ?>" class="btn btn-secondary">Cancel</a>
 <button type="submit" class="btn btn-primary">Submit Venue for Approval &rarr;</button>
 </div>
 </form>

 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassApp.setupFormValidation('addVenueForm');
});

function handleAddVenueSubmit(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Venue Submitted (UC-VO-01)', 'Venue submitted to Platform Admin for review.');
 setTimeout(() => {
 window.location.href = '<?= url('/owner/venues') ?>';
 }, 1200);
}
</script>
