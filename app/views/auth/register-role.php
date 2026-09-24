<div style="padding: var(--space-12) 0 var(--space-16);">
 <div class="container" style="max-width: 800px;">
 
 <div style="text-align: center; margin-bottom: var(--space-8);">
 <h1 style="font-size: var(--font-size-2xl); margin-bottom: 8px;">How would you like to join CourtPass?</h1>
 <p class="lead" style="color: var(--color-text-muted);">
 Select the role that fits you. Each account receives dedicated tools and portal access.
 </p>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Option 1: Customer -->
 <div class="card card-hover" style="display: flex; flex-direction: column; padding: var(--space-6); text-align: center;">
 <div style="font-size: 40px; margin-bottom: 12px;"></div>
 <h3 style="font-size: 18px; margin-bottom: 8px;">Sports Customer</h3>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
 Book courts, join coaching sessions, build your reliability score and unlock Cash on Arrival.
 </p>
 <div style="margin-top: auto;">
 <a href="<?= url('/register/customer') ?>" class="btn btn-primary btn-block">
 Register as Player &rarr;
 </a>
 </div>
 </div>

 <!-- Option 2: Venue Owner -->
 <div class="card card-hover" style="display: flex; flex-direction: column; padding: var(--space-6); text-align: center; border-color: var(--color-primary-border);">
 <div style="font-size: 40px; margin-bottom: 12px;"></div>
 <h3 style="font-size: 18px; margin-bottom: 8px;">Sports Venue Owner</h3>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
 Register sports facilities, manage courts and 1-hour slots, post flash deals, and eliminate no-show losses.
 </p>
 <div style="margin-top: auto;">
 <a href="<?= url('/register/owner') ?>" class="btn btn-outline btn-block" style="border-color: var(--color-primary); color: var(--color-primary);">
 Register as Owner &rarr;
 </a>
 </div>
 </div>

 <!-- Option 3: Independent Coach -->
 <div class="card card-hover" style="display: flex; flex-direction: column; padding: var(--space-6); text-align: center;">
 <div style="font-size: 40px; margin-bottom: 12px;"></div>
 <h3 style="font-size: 18px; margin-bottom: 8px;">Independent Coach</h3>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 20px; line-height: 1.5;">
 Get approved at premier venues, host public or private clinics, collect PayHere fees, and build verified ratings.
 </p>
 <div style="margin-top: auto;">
 <a href="<?= url('/register/coach') ?>" class="btn btn-secondary btn-block">
 Register as Coach &rarr;
 </a>
 </div>
 </div>

 </div>

 <div style="text-align: center; margin-top: var(--space-8); font-size: var(--font-size-sm); color: var(--color-text-muted);">
 Already have an account? <a href="<?= url('/login') ?>" style="font-weight: 700;">Log In here</a>
 </div>

 </div>
</div>
