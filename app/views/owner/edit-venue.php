<?php
/** @var int $venueId @var array $venue @var array $sportTypes @var array $errors */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/owner/venues') ?>">My Venues</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit Venue</span>
        </div>
        <h1 class="page-title">Edit <?= e($venue['name'] ?? 'Venue') ?></h1>
        <div class="page-subtitle">Changes to an approved venue are shown on its public page straight away.</div>
    </div>
</div>

<div class="card" style="max-width: 720px; padding: var(--space-8); margin: 0 auto;">
    <form id="editVenueForm" method="POST" action="<?= url('/owner/venues/' . $venueId) ?>" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <?php View::partial('venue-form-fields', ['venue' => $venue, 'sportTypes' => $sportTypes, 'errors' => $errors]) ?>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?= url('/owner/venues/' . $venueId) ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>

<script>
CourtPassApp.setupFormValidation('editVenueForm');
</script>
