<?php
/** @var int $courtId @var string $courtName @var int $venueId @var array $hours @var array $errors */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/owner/venues/' . $venueId) ?>">Venue</a>
            <span class="breadcrumb-separator">/</span>
            <span>Operating Hours</span>
        </div>
        <h1 class="page-title">Operating Hours: <?= e($courtName) ?></h1>
        <div class="page-subtitle">Slots are generated from these hours. Changing them does not affect existing bookings. A closing time of 00:00 means midnight.</div>
    </div>
</div>

<form method="POST" action="<?= url('/owner/courts/' . $courtId . '/hours') ?>" class="card" style="padding: var(--space-6);">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="PUT">
    <?php if (isset($errors['hours'])): ?>
        <p class="form-feedback invalid" style="display: block; margin-bottom: 8px;"><?= e($errors['hours']) ?></p>
    <?php endif; ?>
    <?php View::partial('court-hours-fields', ['hours' => $hours]) ?>
    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-4);">
        <a href="<?= url('/owner/venues/' . $venueId) ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Hours</button>
    </div>
</form>
