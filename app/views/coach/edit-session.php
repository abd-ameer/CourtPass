<?php
/** @var array $session @var array $old @var array $errors @var int $maxCapacity @var int $maxFee */
$s = $session;
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
$feeValue = $s['can_change_fee'] ? ($old['fee'] ?? '') : $s['fee'];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions') ?>">Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions/' . $s['id']) ?>">#<?= (int) $s['id'] ?></a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Session: <?= e($s['title']) ?></h1>
        <div class="page-subtitle">Title, description, capacity and fee can change. Venue, court, date and time are fixed.</div>
    </div>
</div>

<form id="editSessionForm" method="POST" action="<?= url('/coach/sessions/' . $s['id']) ?>" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <div class="grid grid-cols-3 gap-6">
        <div style="grid-column: span 2;">
            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Session Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="editTitle">Title <span class="required-star">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control<?= $invalid('title') ?>" value="<?= e($old['title'] ?? '') ?>" maxlength="100" required>
                        <span class="form-feedback invalid"><?= e($errors['title'] ?? '') ?></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="editDesc">Description <span class="required-star">*</span></label>
                        <textarea name="description" id="editDesc" class="form-control<?= $invalid('description') ?>" rows="4" maxlength="2000" required><?= e($old['description'] ?? '') ?></textarea>
                        <span class="form-feedback invalid"><?= e($errors['description'] ?? '') ?></span>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Venue and Schedule</h3>
                    <span class="text-xs text-muted">Fixed after creation</span>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div class="text-xs text-muted">Venue</div>
                            <div style="font-weight: 600;"><?= e($s['venue_name']) ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-muted">Court</div>
                            <div style="font-weight: 600;"><?= e($s['court_name']) ?> (<?= e($s['sport_name']) ?>)</div>
                        </div>
                        <div>
                            <div class="text-xs text-muted">Date and Time</div>
                            <div style="font-weight: 600;"><?= e(format_datetime($s['starts_at'])) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Capacity and Fee</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="editCapacity">Max Participants <span class="required-star">*</span></label>
                            <input type="number" name="capacity" id="editCapacity" class="form-control<?= $invalid('capacity') ?>" value="<?= e($old['capacity'] ?? '') ?>" min="<?= (int) $s['min_capacity'] ?>" max="<?= (int) $maxCapacity ?>" required>
                            <span class="form-feedback invalid"><?= e($errors['capacity'] ?? '') ?></span>
                            <span class="text-xs text-muted">At least <?= (int) $s['min_capacity'] ?><?= $s['live_count'] > 0 ? ' (current registrations)' : '' ?>, at most <?= (int) $maxCapacity ?>.</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="editFee">Fee per Person (LKR) <span class="required-star">*</span></label>
                            <?php if ($s['can_change_fee']): ?>
                                <input type="number" name="fee" id="editFee" class="form-control<?= $invalid('fee') ?>" value="<?= e($feeValue) ?>" min="0" max="<?= (int) $maxFee ?>" step="0.01" required>
                                <span class="text-xs text-muted">Enter 0 for a free session. The fee locks once someone registers.</span>
                            <?php else: ?>
                                <input type="number" id="editFee" class="form-control" value="<?= e($feeValue) ?>" disabled>
                                <input type="hidden" name="fee" value="<?= e($feeValue) ?>">
                                <span class="text-xs text-muted">The fee cannot change while people are registered.</span>
                            <?php endif; ?>
                            <span class="form-feedback invalid"><?= e($errors['fee'] ?? '') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Current Status</h3>
                </div>
                <div class="card-body" style="font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Status</span><?= status_badge($s['status']) ?></div>
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Registrations</span><strong><?= (int) $s['live_count'] ?> / <?= (int) $s['capacity'] ?></strong></div>
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Visibility</span><strong><?= $s['visibility'] === 'private' ? 'Private' : 'Public' ?></strong></div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: var(--space-4);">Save Changes</button>
                    <a href="<?= url('/coach/sessions/' . $s['id']) ?>" class="btn btn-secondary" style="width: 100%; margin-top: var(--space-2); text-align: center;">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
CourtPassApp.setupFormValidation('editSessionForm');
</script>
