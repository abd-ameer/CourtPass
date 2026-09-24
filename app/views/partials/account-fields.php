<?php
/**
 * Name, email, phone and password fields shared by the three sign-up forms (users table).
 * Expects $old, $errors and optional placeholders.
 */
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
?>
<div class="form-group">
    <label class="form-label" for="regName">Full Name <span class="required-star">*</span></label>
    <input type="text" name="name" id="regName" class="form-control<?= $invalid('name') ?>" value="<?= e($old['name'] ?? '') ?>" placeholder="<?= e($namePlaceholder ?? '') ?>" maxlength="100" autocomplete="name" required>
    <span class="form-feedback invalid"><?= e($errors['name'] ?? '') ?></span>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
    <div class="form-group">
        <label class="form-label" for="regEmail">Email Address <span class="required-star">*</span></label>
        <input type="email" name="email" id="regEmail" class="form-control<?= $invalid('email') ?>" value="<?= e($old['email'] ?? '') ?>" placeholder="<?= e($emailPlaceholder ?? '') ?>" maxlength="191" autocomplete="email" required>
        <span class="form-feedback invalid"><?= e($errors['email'] ?? '') ?></span>
    </div>
    <div class="form-group">
        <label class="form-label" for="regPhone">Contact Number <span class="required-star">*</span></label>
        <input type="tel" name="phone" id="regPhone" class="form-control<?= $invalid('phone') ?>" value="<?= e($old['phone'] ?? '') ?>" placeholder="+94 77 123 4567" maxlength="20" autocomplete="tel" required>
        <span class="form-feedback invalid"><?= e($errors['phone'] ?? '') ?></span>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
    <div class="form-group">
        <label class="form-label" for="regPassword">Password <span class="required-star">*</span></label>
        <input type="password" name="password" id="regPassword" class="form-control<?= $invalid('password') ?>" placeholder="At least 8 characters" minlength="8" maxlength="72" autocomplete="new-password" required>
        <div class="password-meter"><div class="password-meter-fill"></div></div>
        <span class="form-feedback invalid"><?= e($errors['password'] ?? '') ?></span>
    </div>
    <div class="form-group">
        <label class="form-label" for="regConfirm">Confirm Password <span class="required-star">*</span></label>
        <input type="password" name="confirm_password" id="regConfirm" class="form-control<?= $invalid('confirm_password') ?>" autocomplete="new-password" required>
        <span class="form-feedback invalid"><?= e($errors['confirm_password'] ?? '') ?></span>
    </div>
</div>
