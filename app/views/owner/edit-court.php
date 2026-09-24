<?php
/** @var int|null $courtId null when adding @var int $venueId @var array $court @var array $hours @var array $venues @var array $sportTypes @var array $errors */
$isEdit = $courtId !== null;
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/owner/courts') ?>">Courts</a>
            <span class="breadcrumb-separator">/</span>
            <span><?= $isEdit ? 'Edit Court' : 'Add Court' ?></span>
        </div>
        <h1 class="page-title"><?= $isEdit ? 'Edit Court' : 'Add a Court' ?></h1>
        <div class="page-subtitle">Courts can be added to approved venues. Every slot is exactly one hour.</div>
    </div>
</div>

<div class="card" style="max-width: 760px; padding: var(--space-8); margin: 0 auto;">
    <form id="courtForm" method="POST" action="<?= url($isEdit ? '/owner/courts/' . $courtId : '/owner/courts') ?>" novalidate>
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php else: ?>
            <div class="form-group">
                <label class="form-label" for="courtVenue">Venue <span class="required-star">*</span></label>
                <select name="venue_id" id="courtVenue" class="form-select" required>
                    <option value="">Choose a venue</option>
                    <?php foreach ($venues as $venue): ?>
                        <option value="<?= e($venue['id']) ?>" <?= $venueId === (int) $venue['id'] ? 'selected' : '' ?>><?= e($venue['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form-feedback invalid"><?= e($errors['venue_id'] ?? '') ?></span>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label" for="courtName">Court Name <span class="required-star">*</span></label>
            <input type="text" name="name" id="courtName" class="form-control<?= $invalid('name') ?>" value="<?= e($court['name'] ?? '') ?>" maxlength="100" placeholder="e.g., Badminton Court 3" required>
            <span class="form-feedback invalid"><?= e($errors['name'] ?? '') ?></span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
            <div class="form-group">
                <label class="form-label" for="courtSport">Sport Type <span class="required-star">*</span></label>
                <select name="sport_type_id" id="courtSport" class="form-select" required>
                    <option value="">Choose a sport</option>
                    <?php foreach ($sportTypes as $sport): ?>
                        <option value="<?= e($sport['id']) ?>" <?= (int) ($court['sport_type_id'] ?? 0) === (int) $sport['id'] ? 'selected' : '' ?>><?= e($sport['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form-feedback invalid"><?= e($errors['sport_type_id'] ?? '') ?></span>
            </div>
            <div class="form-group">
                <label class="form-label" for="courtRate">Hourly Rate (LKR) <span class="required-star">*</span></label>
                <input type="number" name="hourly_rate" id="courtRate" class="form-control<?= $invalid('hourly_rate') ?>" value="<?= e($court['hourly_rate'] ?? '') ?>" min="1" step="0.01" required>
                <span class="form-feedback invalid"><?= e($errors['hourly_rate'] ?? '') ?></span>
            </div>
        </div>

        <?php if ($isEdit): ?>
            <div class="form-group">
                <label class="inline-flex items-center gap-2" style="font-size: 14px; font-weight: 600; cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" <?= !empty($court['is_active']) ? 'checked' : '' ?>> Court is active (unticking blocks new bookings but keeps existing ones)
                </label>
            </div>
            <p class="text-sm"><a href="<?= url('/owner/courts/' . $courtId . '/hours') ?>">Edit operating hours &rarr;</a></p>
        <?php else: ?>
            <h3 style="font-size: 15px; margin: 20px 0 10px;">Operating Hours</h3>
            <?php View::partial('court-hours-fields', ['hours' => $hours]) ?>
        <?php endif; ?>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
            <a href="<?= url('/owner/courts') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save Court' : 'Add Court' ?></button>
        </div>
    </form>
</div>

<script>
CourtPassApp.setupFormValidation('courtForm');
</script>
