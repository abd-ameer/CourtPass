<main style="padding: var(--space-12) 0 var(--space-16);">
    <div class="container" style="max-width: 480px;">
    
        <!-- Login Card -->
        <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">
            
            <div style="text-align: center; margin-bottom: var(--space-6);">
                <div style="width: 48px; height: 48px; background: var(--color-primary); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 22px; box-shadow: var(--shadow-brand); margin-bottom: 12px;">
                    CP
                </div>
                <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Welcome Back</h1>
                <p class="text-sm" style="color: var(--color-text-muted);">
                    Sign in to manage court bookings, sessions, and venues
                </p>
            </div>

            <!-- Quick Demo Role Logins for Interim Defense -->
            <div style="background: var(--color-bg-subtle); border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: 12px; margin-bottom: var(--space-6);">
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 8px; letter-spacing: 0.05em;">
                    Interim Defense Quick Logins
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                    <a href="<?= url('/demo-login?role=customer') ?>" class="btn btn-sm btn-outline" style="font-size: 11px; justify-content: flex-start;">
                        Customer (Kasun)
                    </a>
                    <a href="<?= url('/demo-login?role=owner') ?>" class="btn btn-sm btn-outline" style="font-size: 11px; justify-content: flex-start;">
                        Venue Owner (Nuwan)
                    </a>
                    <a href="<?= url('/demo-login?role=coach') ?>" class="btn btn-sm btn-outline" style="font-size: 11px; justify-content: flex-start;">
                        Coach (Dilshan)
                    </a>
                    <a href="<?= url('/demo-login?role=admin') ?>" class="btn btn-sm btn-outline" style="font-size: 11px; justify-content: flex-start;">
                        Platform Admin
                    </a>
                </div>
            </div>

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="<?= url('/login') ?>">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label">Email Address <span class="required-star">*</span></label>
                    <input type="email" name="email" id="loginEmail" class="form-control" placeholder="e.g., kasun.j@gmail.com" required>
                    <span class="form-feedback invalid"></span>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <label class="form-label" style="margin-bottom: 0;">Password <span class="required-star">*</span></label>
                        <a href="#" onclick="CourtPassApp.showToast('info', 'Password Reset', 'Password recovery instructions sent to registered email.'); return false;" style="font-size: 12px;">Forgot password?</a>
                    </div>
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" required>
                    <span class="form-feedback invalid"></span>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-4);">
                    Sign In &rarr;
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Don't have an account? <a href="<?= url('/register-role') ?>" style="font-weight: 700;">Create an account</a>
            </div>

        </div>

    </div>
</main>
