<?php
/** @var array $old @var array $errors @var array $sportTypes @var array $experienceLevels */
$chosenSports = array_map('intval', $old['sports'] ?? []);
?>
<div style="padding: var(--space-12) 0 var(--space-16);">
    <div class="container" style="max-width: 620px;">
        <div class="card" style="padding: var(--space-8); border-radius: var(--radius-2xl); box-shadow: var(--shadow-elevation);">

            <div style="margin-bottom: var(--space-6);">
                <div class="breadcrumb" style="margin-bottom: 8px;">
                    <a href="<?= url('/register') ?>">&larr; Change Role</a>
                </div>
                <h1 style="font-size: var(--font-size-xl); margin-bottom: 4px;">Coach Sign Up</h1>
                <p class="text-sm" style="color: var(--color-text-muted);">
                    A coach account is for running sessions only. After signing up, request approval at the venues you want to coach at.
                </p>
            </div>

            <form id="coachRegisterForm" method="POST" action="<?= url('/register/coach') ?>" novalidate>
                <?= csrf_field() ?>
                <?php View::partial('account-fields', ['old' => $old, 'errors' => $errors, 'namePlaceholder' => 'e.g., Dilshan Perera', 'emailPlaceholder' => 'coach@example.lk']) ?>

                <div class="form-group">
                    <span class="form-label">Sports You Coach <span class="required-star">*</span></span>
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px;">
                        <?php foreach ($sportTypes as $sport): ?>
                            <label class="inline-flex items-center gap-1" style="font-size: 13px;">
                                <input type="checkbox" name="sports[]" value="<?= e($sport['id']) ?>" <?= in_array((int) $sport['id'], $chosenSports, true) ? 'checked' : '' ?>>
                                <?= e($sport['name']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <span class="form-feedback invalid"><?= e($errors['sports'] ?? '') ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regExperience">Experience Level <span class="required-star">*</span></label>
                    <select name="experience_level" id="regExperience" class="form-select" required>
                        <option value="">Choose a level</option>
                        <?php foreach ($experienceLevels as $level): ?>
                            <option value="<?= e($level) ?>" <?= ($old['experience_level'] ?? '') === $level ? 'selected' : '' ?>><?= e(ucfirst($level)) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-feedback invalid"><?= e($errors['experience_level'] ?? '') ?></span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regBio">Short Bio <span class="required-star">*</span></label>
                    <textarea name="bio" id="regBio" class="form-control<?= isset($errors['bio']) ? ' is-invalid' : '' ?>" rows="3" maxlength="1000" placeholder="Your coaching background and who you coach" required><?= e($old['bio'] ?? '') ?></textarea>
                    <span class="form-feedback invalid"><?= e($errors['bio'] ?? '') ?></span>
                </div>

                <div style="background: var(--color-bg-subtle); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 12px; margin-bottom: 20px; font-size: 12px; color: var(--color-text-muted);">
                    Certifications can be added to your profile after sign-up. The Verified badge is set by the Platform Admin after offline identity checks; no ID documents are collected.
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Create Coach Account &rarr;
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--space-6); font-size: var(--font-size-sm); color: var(--color-text-muted);">
                Already have an account? <a href="<?= url('/login') ?>" style="font-weight: 700;">Log in</a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => CourtPassApp.setupFormValidation('coachRegisterForm'));
</script>
