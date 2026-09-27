<?php
/** @var array $account name, email, phone @var array $sessions the coach's sessions @var array $profile @var array $sportTypes @var array $experienceLevels */
$sportNames = array_column(array_filter($sportTypes, fn (array $s) => in_array((int) $s['id'], $profile['sport_type_ids'], true)), 'name');
$rating = $sessions[0]['coach_rating'] ?? null;
$initials = strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($account['name'])) ?: [], 0, 2))));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <span>Coach Portal</span>
            <span class="breadcrumb-separator">/</span>
            <span>Profile & Certifications</span>
        </div>
        <h1 class="page-title">My Coaching Profile</h1>
        <div class="page-subtitle">Your public coach profile, sports and certifications.</div>
    </div>
    <a href="#editProfile" class="btn btn-primary">Edit Profile</a>
</div>

<div class="grid grid-cols-3 gap-6" style="margin-bottom: var(--space-6);">
    <div class="card" style="text-align: center; padding: var(--space-6);">
        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #00b562, #009a53); margin: 0 auto var(--space-4); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: 700;">
            <?= e($initials) ?>
        </div>
        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;"><?= e($account['name']) ?></h2>
        <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap; margin-bottom: var(--space-4);">
            <span class="badge" style="background: #dbeafe; color: #1d4ed8;">Verified Coach</span>
        </div>
        <div style="display: flex; justify-content: center; gap: var(--space-6); margin-bottom: var(--space-4); padding: var(--space-4) 0; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border);">
            <div style="text-align: center;">
                <div style="font-size: 20px; font-weight: 700; color: var(--color-primary);"><?= $rating === null ? '-' : e(number_format((float) $rating, 1)) ?></div>
                <div class="text-xs text-muted">Rating</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 20px; font-weight: 700; color: var(--color-primary);"><?= array_sum(array_column($sessions, 'registration_count')) ?></div>
                <div class="text-xs text-muted">Registrations</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 20px; font-weight: 700; color: var(--color-primary);"><?= count($sessions) ?></div>
                <div class="text-xs text-muted">Sessions</div>
            </div>
        </div>
        <div style="text-align: left; font-size: 13px;">
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid var(--color-border-light);">
                <span class="text-muted">Email</span>
                <strong><?= e($account['email']) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                <span class="text-muted">Phone</span>
                <strong><?= e($account['phone']) ?></strong>
            </div>
        </div>
    </div>

    <div style="grid-column: span 2;">
        <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">About Me</h3>
            </div>
            <div class="card-body">
                <p style="font-size: 14px; line-height: 1.7; margin-bottom: 0;"><?= e($profile['bio']) ?></p>
            </div>
        </div>

        <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Sports & Experience</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
                    <?php foreach ($sportNames as $name): ?>
                        <span class="badge badge-confirmed"><?= e($name) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="text-sm">Experience level: <strong><?= e(ucfirst($profile['experience_level'])) ?></strong></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Certifications & Qualifications</h3>
            </div>
            <div class="card-body">
                <p class="text-sm" style="margin-bottom: 6px;"><?= e($profile['certifications'] ?: 'No certifications listed.') ?></p>
                <p class="text-xs text-muted" style="margin-bottom: 0;">Listed as text. The Platform Admin sets the Verified badge after offline checks; no documents are collected.</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile -->
 <form id="editProfile" method="POST" action="<?= url('/coach/profile') ?>" class="card" style="padding: var(--space-6);">
 <?= csrf_field() ?>
 <input type="hidden" name="_method" value="PUT">
 <h3 style="font-size: 15px; margin-bottom: 14px;">Edit Profile</h3>
 <div class="form-group">
 <label class="form-label" for="profBio">Bio <span class="required-star">*</span></label>
 <textarea name="bio" id="profBio" class="form-control" rows="4" maxlength="1000" required><?= e($profile['bio']) ?></textarea>
 </div>
 <div class="form-group">
 <span class="form-label">Sport Types <span class="required-star">*</span></span>
 <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 6px;">
 <?php foreach ($sportTypes as $sport): ?>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;">
 <input type="checkbox" name="sports[]" value="<?= e($sport['id']) ?>" <?= in_array((int) $sport['id'], $profile['sport_type_ids'], true) ? 'checked' : '' ?>> <?= e($sport['name']) ?>
 </label>
 <?php endforeach; ?>
 </div>
 </div>
 <div class="form-group">
 <label class="form-label" for="profLevel">Experience Level <span class="required-star">*</span></label>
 <select name="experience_level" id="profLevel" class="form-select" required>
 <?php foreach ($experienceLevels as $level): ?>
 <option value="<?= e($level) ?>" <?= $profile['experience_level'] === $level ? 'selected' : '' ?>><?= e(ucfirst($level)) ?></option>
 <?php endforeach; ?>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label" for="profCerts">Certifications (text only)</label>
 <textarea name="certifications" id="profCerts" class="form-control" rows="3" maxlength="2000"><?= e($profile['certifications']) ?></textarea>
 </div>
 <button type="submit" class="btn btn-primary">Save Profile</button>
 </form>
