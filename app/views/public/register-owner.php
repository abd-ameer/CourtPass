<main style="padding: var(--space-12) 0 var(--space-16);">
 <div class="container" style="max-width: 620px;">
 
 <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">
 
 <div style="margin-bottom: var(--space-6);">
 <div class="breadcrumb" style="margin-bottom: 8px;">
 <a href="<?= url('/register-role') ?>">&larr; Change Role</a>
 </div>
 <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Register Your Sports Venue</h1>
 <p class="text-sm" style="color: var(--color-text-muted);">
 Join the CourtPass network to manage bookings, minimize no-shows, and expand your community reach.
 </p>
 </div>

 <form id="ownerRegisterForm" onsubmit="handleOwnerRegister(event)">
 
 <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 12px; letter-spacing: 0.05em; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">
 1. Owner Credentials
 </div>

 <div class="form-group">
 <label class="form-label">Owner Full Name <span class="required-star">*</span></label>
 <input type="text" name="name" class="form-control" placeholder="e.g., Nuwan Senanayake" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <div class="form-group">
 <label class="form-label">Business Email <span class="required-star">*</span></label>
 <input type="email" name="email" class="form-control" placeholder="nuwan@futsal.lk" required>
 <span class="form-feedback invalid"></span>
 </div>
 <div class="form-group">
 <label class="form-label">Contact Phone <span class="required-star">*</span></label>
 <input type="tel" name="phone" class="form-control" placeholder="+94 77 889 9123" required>
 <span class="form-feedback invalid"></span>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <div class="form-group">
 <label class="form-label">Password <span class="required-star">*</span></label>
 <input type="password" name="password" class="form-control" placeholder="••••••••" required>
 <span class="form-feedback invalid"></span>
 </div>
 <div class="form-group">
 <label class="form-label">Confirm Password <span class="required-star">*</span></label>
 <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
 <span class="form-feedback invalid"></span>
 </div>
 </div>

 <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--color-text-muted); margin: 20px 0 12px; letter-spacing: 0.05em; border-bottom: 1px solid var(--color-border); padding-bottom: 6px;">
 2. Primary Venue Details
 </div>

 <div class="form-group">
 <label class="form-label">Sports Venue Name <span class="required-star">*</span></label>
 <input type="text" name="venue_name" class="form-control" placeholder="e.g., Colombo Futsal Club" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div class="form-group">
 <label class="form-label">Physical Street Address <span class="required-star">*</span></label>
 <input type="text" name="address" class="form-control" placeholder="e.g., No. 42 Marine Drive, Dehiwala" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div class="form-group">
 <label class="form-label">Sports Offered at Facility <span class="required-star">*</span></label>
 <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px;">
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="futsal" checked> Futsal
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="badminton" checked> Badminton
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="pickleball"> Pickleball
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="squash"> Squash
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="billiards"> Billiards
 </label>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Facility Summary / Tagline</label>
 <textarea name="description" class="form-control" rows="2" placeholder="Brief description of court surfaces, lighting and parking amenities..."></textarea>
 </div>

 <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: var(--radius-md); padding: 12px; margin-bottom: 20px; font-size: 12px; color: #92400e;">
 <strong>Admin Approval Workflow:</strong> Registered venues enter a <strong>Pending</strong> state until reviewed by the Platform Admin. Once approved, you will receive a clean public URL (e.g. <code>platform.lk/venue/yourvenue</code>).
 </div>

 <button type="submit" class="btn btn-primary btn-block btn-lg">
 Submit Venue for Admin Approval &rarr;
 </button>
 </form>

 </div>

 </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassApp.setupFormValidation('ownerRegisterForm');
});

function handleOwnerRegister(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Venue Submitted', 'Your venue registration is pending admin verification. Opening Owner Dashboard...');
 setTimeout(() => {
 CourtPassApp.setRole('owner');
 }, 1000);
}
</script>
