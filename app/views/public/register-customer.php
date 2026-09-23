<main style="padding: var(--space-12) 0 var(--space-16);">
 <div class="container" style="max-width: 540px;">
 
 <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">
 
 <div style="margin-bottom: var(--space-6);">
 <div class="breadcrumb" style="margin-bottom: 8px;">
 <a href="<?= url('/register-role') ?>">&larr; Change Role</a>
 </div>
 <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Player Sign Up</h1>
 <p class="text-sm" style="color: var(--color-text-muted);">
 Create your CourtPass account to start booking courts and tracking your Match History.
 </p>
 </div>

 <form id="customerRegisterForm" onsubmit="handleCustomerRegister(event)">
 <div class="form-group">
 <label class="form-label">Full Name <span class="required-star">*</span></label>
 <input type="text" name="name" class="form-control" placeholder="e.g., Kasun Jayawardena" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
 <div class="form-group">
 <label class="form-label">Email Address <span class="required-star">*</span></label>
 <input type="email" name="email" class="form-control" placeholder="kasun@example.com" required>
 <span class="form-feedback invalid"></span>
 </div>
 <div class="form-group">
 <label class="form-label">Mobile Number <span class="required-star">*</span></label>
 <input type="tel" name="phone" class="form-control" placeholder="+94 77 123 4567" required>
 <span class="form-feedback invalid"></span>
 </div>
 </div>

 <!-- Password with Strength Meter -->
 <div class="form-group">
 <label class="form-label">Password <span class="required-star">*</span></label>
 <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
 <div class="password-meter">
 <div class="password-meter-fill"></div>
 </div>
 <span class="form-feedback invalid"></span>
 </div>

 <div class="form-group">
 <label class="form-label">Confirm Password <span class="required-star">*</span></label>
 <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
 <span class="form-feedback invalid"></span>
 </div>

 <div class="form-group">
 <label class="form-label">Favorite Sports</label>
 <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px;">
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="badminton" checked> Badminton
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="futsal" checked> Futsal
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="pickleball"> Pickleball
 </label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="squash"> Squash
 </label>
 </div>
 </div>


 <button type="submit" class="btn btn-primary btn-block btn-lg">
 Complete Registration &rarr;
 </button>
 </form>

 <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
 Already have an account? <a href="<?= url('/login') ?>" style="font-weight: 700;">Log In</a>
 </div>

 </div>

 </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassApp.setupFormValidation('customerRegisterForm');
});

function handleCustomerRegister(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Registration Successful!', 'Welcome to CourtPass. Redirecting to your customer dashboard...');
 setTimeout(() => {
 CourtPassApp.setRole('customer');
 }, 1000);
}
</script>
