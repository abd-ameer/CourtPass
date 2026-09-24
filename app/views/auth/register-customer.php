<?php
/** @var array $old @var array $errors */
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
?>
<div style="padding: var(--space-12) 0 var(--space-16);">
    <div class="container" style="max-width: 540px;">
        <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">

            <div style="margin-bottom: var(--space-6);">
                <div class="breadcrumb" style="margin-bottom: 8px;">
                    <a href="<?= url('/register') ?>">&larr; Change Role</a>
                </div>
                <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Customer Sign Up</h1>
                <p class="text-sm" style="color: var(--color-text-muted);">
                    Create your CourtPass account to book courts and join coaching sessions.
                </p>
            </div>

            <form id="customerRegisterForm" method="POST" action="<?= url('/register/customer') ?>" novalidate>
                <?= csrf_field() ?>
                <?php View::partial('account-fields', ['old' => $old, 'errors' => $errors, 'namePlaceholder' => 'e.g., Kasun Jayawardena', 'emailPlaceholder' => 'kasun@example.com']) ?>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: var(--space-4);">
                    Create Account &rarr;
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Already have an account? <a href="<?= url('/login') ?>" style="font-weight: 700;">Log in</a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => CourtPassApp.setupFormValidation('customerRegisterForm'));
</script>
