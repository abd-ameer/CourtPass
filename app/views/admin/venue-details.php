<?php
/** @var array $venue */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/admin/venues') ?>">Venue Approvals</a>
            <span class="breadcrumb-separator">/</span>
            <span><?= e($venue['name']) ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <h1 class="page-title"><?= e($venue['name']) ?></h1>
            <?= status_badge($venue['status']) ?>
            <?php if ($venue['status'] === 'approved' && !$venue['is_active']): ?>
                <span class="badge badge-inactive">Deactivated by owner</span>
            <?php endif; ?>
        </div>
        <div class="page-subtitle">
            Registered <?= e(format_datetime($venue['created_at'])) ?>
            <?php if ($venue['reviewed_at'] !== null): ?>
                · Reviewed <?= e(format_datetime($venue['reviewed_at'])) ?> by <?= e($venue['reviewer_name']) ?>
            <?php endif; ?>
        </div>
    </div>
    <div style="display: flex; gap: 10px;">
        <?php if ($venue['can_decide']): ?>
            <button type="button" class="btn btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Venue', 'A reason is required and is shown to the owner.', '/admin/venues/<?= (int) $venue['id'] ?>/reject')">Reject</button>
            <button type="button" class="btn btn-primary" onclick="CourtPassApp.confirmPost('Approve Venue', 'Approve this venue and list it publicly?', 'Approve', '/admin/venues/<?= (int) $venue['id'] ?>/approve')">Approve</button>
        <?php elseif ($venue['status'] === 'approved'): ?>
            <button type="button" class="btn btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Deactivate Venue', 'All future bookings and coaching sessions at this venue will be cancelled. A reason is required.', '/admin/venues/<?= (int) $venue['id'] ?>/deactivate')">Deactivate Venue</button>
            <?php if ($venue['listed']): ?>
                <a href="<?= url($venue['public_path']) ?>" target="_blank" class="btn btn-primary">View Public Page</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php if ($venue['status'] === 'rejected'): ?>
    <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
        <strong>Rejection reason:</strong> <?= e($venue['rejection_reason']) ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-3 gap-6">
    <div style="grid-column: span 2; display: flex; flex-direction: column; gap: var(--space-6);">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Venue Details</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
                    <div>
                        <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Address</span>
                        <div style="font-weight: 600; font-size: 14px; color: var(--color-text-heading);"><?= e($venue['address']) ?>, <?= e($venue['city']) ?></div>
                    </div>
                    <div>
                        <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Venue Phone</span>
                        <div style="font-weight: 600; font-size: 14px; color: var(--color-text-heading);"><?= e($venue['contact_phone']) ?></div>
                    </div>
                </div>
                <div style="margin-bottom: var(--space-4);">
                    <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Sports</span>
                    <div style="margin-top: 4px; display: flex; gap: 6px; flex-wrap: wrap;">
                        <?php foreach ($venue['sports'] as $sport): ?>
                            <span class="badge badge-confirmed"><?= e($sport['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php if ($venue['description'] !== null && $venue['description'] !== ''): ?>
                    <div>
                        <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Description</span>
                        <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-top: 4px; line-height: 1.5;"><?= nl2br(e($venue['description'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Courts (<?= count($venue['courts']) ?>)</h3>
            </div>
            <?php if ($venue['courts'] === []): ?>
                <div class="empty-state">
                    <div class="empty-state-desc">No courts yet. Owners add courts after the venue is approved.</div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Court</th>
                                <th>Sport</th>
                                <th>Hourly Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($venue['courts'] as $court): ?>
                                <tr>
                                    <td><strong><?= e($court['name']) ?></strong></td>
                                    <td><?= e($court['sport_name']) ?></td>
                                    <td><?= e(lkr($court['hourly_rate'])) ?></td>
                                    <td><?= $court['is_active'] ? status_badge('active') : '<span class="badge badge-inactive">Inactive</span>' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Owner</h3>
            </div>
            <div class="card-body" style="font-size: var(--font-size-xs); display: flex; flex-direction: column; gap: 8px;">
                <div style="font-weight: 700; font-size: 14px; color: var(--color-text-heading);"><?= e($venue['owner_name']) ?></div>
                <div><span style="color: var(--color-text-subtle);">Email:</span> <strong><?= e($venue['owner_email']) ?></strong></div>
                <div><span style="color: var(--color-text-subtle);">Phone:</span> <strong><?= e($venue['owner_phone'] ?? 'Not given') ?></strong></div>
            </div>
        </div>
    </div>
</div>
