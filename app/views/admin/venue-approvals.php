<?php
/** @var array $venues */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Venue Approvals</span>
        </div>
        <h1 class="page-title">Venue Approvals</h1>
        <div class="page-subtitle">Review newly registered venues and approve them for public listing, or reject them with a reason.</div>
    </div>
    <div>
        <span class="badge badge-warning" style="font-size: 13px; padding: 6px 14px;"><?= count($venues) ?> awaiting review</span>
    </div>
</div>

<?php if ($venues === []): ?>
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-title">No venues waiting for approval</div>
            <div class="empty-state-desc">New venue registrations appear here.</div>
        </div>
    </div>
<?php else: ?>
    <div style="display: flex; flex-direction: column; gap: var(--space-6);">
        <?php foreach ($venues as $venue): ?>
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                            <h3 class="card-title" style="font-size: 18px;"><?= e($venue['name']) ?></h3>
                            <?= status_badge($venue['status']) ?>
                            <span class="badge badge-confirmed"><?= e(implode(' · ', array_column($venue['sports'], 'name'))) ?></span>
                        </div>
                        <div class="card-subtitle">
                            <?= e($venue['address']) ?>, <?= e($venue['city']) ?> · Submitted <?= e(format_datetime($venue['created_at'])) ?>
                            by <strong><?= e($venue['owner_name']) ?></strong> (<?= e($venue['contact_phone']) ?>)
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?= url('/admin/venues/' . $venue['id']) ?>" class="btn btn-sm btn-outline">View Details</a>
                        <button type="button" class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Venue', 'A reason is required and is shown to the owner.', '/admin/venues/<?= (int) $venue['id'] ?>/reject')">Reject</button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Approve Venue', 'Approve this venue and list it publicly?', 'Approve', '/admin/venues/<?= (int) $venue['id'] ?>/approve')">Approve</button>
                    </div>
                </div>
                <?php if ($venue['description'] !== null && $venue['description'] !== ''): ?>
                    <div class="card-body">
                        <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0;"><?= nl2br(e($venue['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
