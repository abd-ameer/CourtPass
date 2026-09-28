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
                <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Forgot Your Password?</h1>
                <p class="text-sm" style="color: var(--color-text-muted);">
                    Enter the email address you registered with and we will help you reset your password
                </p>
            </div>

            <form id="forgotPasswordForm" method="POST" action="<?= url('/forgot-password') ?>" novalidate>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label" for="forgotEmail">Email Address <span class="required-star">*</span></label>
                    <input type="email" name="email" id="forgotEmail" class="form-control<?= $invalid('email') ?>" value="<?= e($old['email'] ?? '') ?>" maxlength="191" autocomplete="email" required autofocus>
                    <span class="form-feedback invalid"><?= e($errors['email'] ?? '') ?></span>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-4);">
                    Send Reset Link &rarr;
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Remembered it? <a href="<?= url('/login') ?>" style="font-weight: 700;">Back to log in</a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => CourtPassApp.setupFormValidation('forgotPasswordForm'));
</script>
