<main style="padding: var(--space-12) 0 var(--space-16);">
 <div class="container" style="max-width: 620px;">
 
 <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">
 
 <div style="margin-bottom: var(--space-6);">
 <div class="breadcrumb" style="margin-bottom: 8px;">
 <a href="<?= url('/register-role') ?>">&larr; Change Role</a>
 </div>
 <div class="badge" style="background: #f5f3ff; color: #7c3aed; margin-bottom: 8px;">Coach Module</div>
 <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Register as an Independent Coach</h1>
 <p class="text-sm" style="color: var(--color-text-muted);">
 Host paid 1-hour public and private coaching masterclasses across approved Sri Lankan sports venues.
 </p>
 </div>

 <form id="coachRegisterForm" onsubmit="handleCoachRegister(event)">
 
 <div class="form-group">
 <label class="form-label">Full Name <span class="required-star">*</span></label>
 <input type="text" name="name" class="form-control" placeholder="e.g., Coach Dilshan Perera" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <div class="form-group">
 <label class="form-label">Email Address <span class="required-star">*</span></label>
 <input type="email" name="email" class="form-control" placeholder="dilshan@coach.lk" required>
 <span class="form-feedback invalid"></span>
 </div>
 <div class="form-group">
 <label class="form-label">Contact Phone <span class="required-star">*</span></label>
 <input type="tel" name="phone" class="form-control" placeholder="+94 71 445 6789" required>
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

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <div class="form-group">
 <label class="form-label">Primary Coaching Sport <span class="required-star">*</span></label>
 <select name="sport" class="form-select" required>
 <option value="Badminton"> Badminton</option>
 <option value="Futsal"> Futsal</option>
 <option value="Pickleball"> Pickleball</option>
 <option value="Squash"> Squash</option>
 <option value="Table Tennis"> Table Tennis</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Years of Experience <span class="required-star">*</span></label>
 <input type="number" name="experience" min="1" max="40" class="form-control" value="5" required>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Certifications & Accreditations (Text Only) <span class="required-star">*</span></label>
 <input type="text" name="certifications" class="form-control" placeholder="e.g., BWF Coach Level 2 / AFC 'C' License / Diploma in Sports Science" required>
 <div class="text-xs" style="color: var(--color-text-muted); margin-top: 4px;">
 Per platform privacy standards, physical NIC or identity documents are not uploaded. Admin conducts offline verification before issuing the Verified badge.
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Coaching Bio & Methodology</label>
 <textarea name="bio" class="form-control" rows="3" placeholder="Describe your training philosophy, target age groups, and tournament achievements..."></textarea>
 </div>

 <div style="background: var(--color-bg-subtle); border-radius: var(--radius-md); padding: 12px; margin-bottom: 20px; font-size: 12px; color: var(--color-text-muted);">
 <strong>Coach Operational Rule:</strong> After registering, request venue approvals to schedule sessions on real court slots. Every coaching session is fixed at exactly 1 hour.
 </div>

 <button type="submit" class="btn btn-primary btn-block btn-lg" style="background: #7c3aed; border-color: #7c3aed;">
 Register as Independent Coach &rarr;
 </button>
 </form>

 </div>

 </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassApp.setupFormValidation('coachRegisterForm');
});

function handleCoachRegister(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Coach Account Created', 'Welcome Coach! Redirecting to your Coach Dashboard...');
 setTimeout(() => {
 CourtPassApp.setRole('coach');
 }, 1000);
}
</script>
