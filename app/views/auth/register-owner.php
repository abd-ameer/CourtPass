<?php
/** @var array $old @var array $errors */
?>
<div style="padding: var(--space-12) 0 var(--space-16);">
    <div class="container" style="max-width: 620px;">
        <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">

            <div style="margin-bottom: var(--space-6);">
                <div class="breadcrumb" style="margin-bottom: 8px;">
                    <a href="<?= url('/register') ?>">&larr; Change Role</a>
                </div>
                <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Venue Owner Sign Up</h1>
                <p class="text-sm" style="color: var(--color-text-muted);">
                    Create your owner account. You will register your venue and courts right after signing up.
                </p>
            </div>

            <form id="ownerRegisterForm" method="POST" action="<?= url('/register/owner') ?>" novalidate>
                <?= csrf_field() ?>
                <?php View::partial('account-fields', ['old' => $old, 'errors' => $errors, 'namePlaceholder' => 'e.g., Nuwan Senanayake', 'emailPlaceholder' => 'owner@example.lk']) ?>

                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: var(--radius-md); padding: 12px; margin-bottom: 20px; font-size: 12px; color: #92400e;">
                    <strong>Admin Approval Workflow:</strong> Each venue you register enters a <strong>Pending</strong> state until the Platform Admin reviews it. Approved venues get a public page at <code>/venue/your-venue-name</code>.
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Create Owner Account &rarr;
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Already have an account? <a href="<?= url('/login') ?>" style="font-weight: 700;">Log in</a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => CourtPassApp.setupFormValidation('ownerRegisterForm'));
</script>
