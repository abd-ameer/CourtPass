<?php
/** @var array $venue */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/owner/venues') ?>">My Venues</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/owner/venues/' . $venue['id']) ?>"><?= e($venue['name']) ?></a>
            <span class="breadcrumb-separator">/</span>
            <span>Deactivate</span>
        </div>
        <h1 class="page-title">Deactivate <?= e($venue['name']) ?></h1>
        <div class="page-subtitle">Temporarily remove the venue from public listings.</div>
    </div>
</div>

<div class="card" style="max-width: 650px; padding: var(--space-8); margin: 0 auto; border: 2px solid var(--color-danger-border);">
    <p class="text-sm" style="margin-bottom: 10px;">
        Deactivating <strong><?= e($venue['name']) ?></strong> hides it from public listings and stops new bookings and coaching sessions on its courts.
    </p>
    <div style="background: var(--color-bg-subtle); padding: 12px 16px; border-radius: var(--radius-md); font-size: 12px; color: var(--color-text-muted); line-height: 1.5; margin-bottom: var(--space-6);">
        Existing bookings and coaching sessions are kept. You can activate the venue again at any time from its page.
    </div>

    <form method="POST" action="<?= url('/owner/venues/' . $venue['id'] . '/deactivate') ?>" onsubmit="return document.getElementById('confirmKeywordInput').value === 'DEACTIVATE' || (alert('Type DEACTIVATE to confirm.'), false);">
        <?= csrf_field() ?>
        <div class="form-group">
            <label class="form-label" for="confirmKeywordInput">Type "DEACTIVATE" to confirm</label>
            <input type="text" id="confirmKeywordInput" class="form-control" placeholder="DEACTIVATE" autocomplete="off" required>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
            <a href="<?= url('/owner/venues/' . $venue['id']) ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-danger">Deactivate Venue</button>
        </div>
    </form>
</div>
