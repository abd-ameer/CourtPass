<?php
/** @var array $venue @var array $sportTypes @var array $errors */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/owner/venues') ?>">My Venues</a>
            <span class="breadcrumb-separator">/</span>
            <span>Register Venue</span>
        </div>
        <h1 class="page-title">Register a Venue</h1>
        <div class="page-subtitle">The venue stays Pending until the Platform Admin approves it. Add courts and operating hours after approval.</div>
    </div>
</div>

<div class="card" style="max-width: 720px; padding: var(--space-8); margin: 0 auto;">
    <form id="addVenueForm" method="POST" action="<?= url('/owner/venues') ?>" novalidate>
        <?= csrf_field() ?>
        <?php View::partial('venue-form-fields', ['venue' => $venue, 'sportTypes' => $sportTypes, 'errors' => $errors]) ?>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?= url('/owner/venues') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit for Approval &rarr;</button>
        </div>
    </form>
</div>

<script>
CourtPassApp.setupFormValidation('addVenueForm');
</script>
