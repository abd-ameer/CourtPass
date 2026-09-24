<?php
/** @var array $old @var array $errors */
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
?>
<div style="padding: var(--space-12) 0 var(--space-16);">
    <div class="container" style="max-width: 480px;">
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

            <form id="loginForm" method="POST" action="<?= url('/login') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label" for="loginEmail">Email Address <span class="required-star">*</span></label>
                    <input type="email" name="email" id="loginEmail" class="form-control<?= $invalid('email') ?>" value="<?= e($old['email'] ?? '') ?>" maxlength="191" autocomplete="email" required autofocus>
                    <span class="form-feedback invalid"><?= e($errors['email'] ?? '') ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="loginPassword">Password <span class="required-star">*</span></label>
                    <input type="password" name="password" id="loginPassword" class="form-control<?= $invalid('password') ?>" autocomplete="current-password" required>
                    <span class="form-feedback invalid"><?= e($errors['password'] ?? '') ?></span>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-4);">
                    Sign In &rarr;
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Don't have an account? <a href="<?= url('/register') ?>" style="font-weight: 700;">Create an account</a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => CourtPassApp.setupFormValidation('loginForm'));
</script>
