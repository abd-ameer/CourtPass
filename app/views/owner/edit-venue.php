<?php
/** @var int $venueId @var array $venue @var string $status @var array $sportTypes @var array $errors */
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
        <h1 class="page-title">Edit Venue</h1>
        <div class="page-subtitle">
            <?php if ($status === 'rejected'): ?>
                Saving sends the venue back to the Platform Admin for approval.
            <?php elseif ($status === 'approved'): ?>
                Changes to an approved venue are shown on its public page straight away.
            <?php else: ?>
                The venue stays Pending until the Platform Admin approves it.
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card" style="max-width: 720px; padding: var(--space-8); margin: 0 auto;">
    <form id="editVenueForm" method="POST" action="<?= url('/owner/venues/' . $venueId) ?>" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <?php View::partial('venue-form-fields', ['venue' => $venue, 'sportTypes' => $sportTypes, 'errors' => $errors]) ?>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?= url('/owner/venues/' . $venueId) ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><?= $status === 'rejected' ? 'Save and Resubmit' : 'Save Changes' ?></button>
        </div>
    </form>
</div>

<script>
CourtPassApp.setupFormValidation('editVenueForm');
</script>
