<?php
/** @var array $account @var array $errors */
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
$roleTitles = ['customer' => 'Customer', 'owner' => 'Venue Owner', 'coach' => 'Coach', 'admin' => 'Platform Admin'];
$initials = strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($account['name'])) ?: [], 0, 2))));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url(Auth::homeUrl()) ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Account Settings</span>
        </div>
        <h1 class="page-title">Account Settings</h1>
        <div class="page-subtitle">Update your name, contact number and password.</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
    <div>
        <form method="POST" action="<?= url('/account') ?>" class="card" style="padding: var(--space-6); margin-bottom: var(--space-6);" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <h3 style="font-size: 16px; margin-bottom: 16px;">Personal Information</h3>

            <div class="form-group">
                <label class="form-label" for="accName">Full Name <span class="required-star">*</span></label>
                <input type="text" name="name" id="accName" class="form-control<?= $invalid('name') ?>" value="<?= e($account['name']) ?>" maxlength="100" required>
                <span class="form-feedback invalid"><?= e($errors['name'] ?? '') ?></span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label class="form-label" for="accEmail">Email Address</label>
                    <input type="email" id="accEmail" class="form-control" value="<?= e($account['email']) ?>" readonly>
                    <span class="form-feedback">Email is your login and cannot be changed.</span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="accPhone">Contact Number <span class="required-star">*</span></label>
                    <input type="tel" name="phone" id="accPhone" class="form-control<?= $invalid('phone') ?>" value="<?= e($account['phone']) ?>" maxlength="20" required>
                    <span class="form-feedback invalid"><?= e($errors['phone'] ?? '') ?></span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <form method="POST" action="<?= url('/account/password') ?>" class="card" style="padding: var(--space-6);" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT">
            <h3 style="font-size: 16px; margin-bottom: 16px;">Change Password</h3>

            <div class="form-group">
                <label class="form-label" for="accCurrent">Current Password <span class="required-star">*</span></label>
                <input type="password" name="current_password" id="accCurrent" class="form-control<?= $invalid('current_password') ?>" autocomplete="current-password" required>
                <span class="form-feedback invalid"><?= e($errors['current_password'] ?? '') ?></span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label class="form-label" for="accNew">New Password <span class="required-star">*</span></label>
                    <input type="password" name="new_password" id="accNew" class="form-control<?= $invalid('new_password') ?>" minlength="8" maxlength="72" autocomplete="new-password" required>
                    <span class="form-feedback invalid"><?= e($errors['new_password'] ?? '') ?></span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="accConfirm">Confirm New Password <span class="required-star">*</span></label>
                    <input type="password" name="confirm_password" id="accConfirm" class="form-control<?= $invalid('confirm_password') ?>" autocomplete="new-password" required>
                    <span class="form-feedback invalid"><?= e($errors['confirm_password'] ?? '') ?></span>
                </div>
            </div>

            <button type="submit" class="btn btn-outline">Update Password</button>
        </form>
    </div>

    <div>
        <div class="card" style="padding: var(--space-6); background: var(--color-bg-subtle); text-align: center;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--color-primary-light); color: var(--color-primary-active); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; margin-bottom: 8px;">
                <?= e($initials) ?>
            </div>
            <div style="font-weight: 700; font-size: 16px;"><?= e($account['name']) ?></div>
            <div class="text-xs text-muted"><?= e($roleTitles[$account['role']] ?? '') ?> account</div>
            <?php if ($account['role'] === 'customer'): ?>
                <a href="<?= url('/customer/reliability') ?>" class="btn btn-outline-primary btn-block btn-sm" style="margin-top: 14px;">View Reliability &amp; Tier &rarr;</a>
            <?php elseif ($account['role'] === 'coach'): ?>
                <a href="<?= url('/coach/profile') ?>" class="btn btn-outline-primary btn-block btn-sm" style="margin-top: 14px;">Edit Coach Profile &rarr;</a>
            <?php endif; ?>
        </div>
    </div>
</div>
