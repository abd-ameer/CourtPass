<?php
/** @var array $venues */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>My Venues</span>
        </div>
        <h1 class="page-title">My Venues</h1>
        <div class="page-subtitle">Register venues, track their approval and manage their courts.</div>
    </div>
    <a href="<?= url('/owner/venues/create') ?>" class="btn btn-primary">+ Register New Venue</a>
</div>

<?php if ($venues === []): ?>
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-title">No venues yet</div>
            <div class="empty-state-desc">Register your first venue. It is listed publicly once the Platform Admin approves it.</div>
            <a href="<?= url('/owner/venues/create') ?>" class="btn btn-primary" style="margin-top: 12px;">Register a Venue</a>
        </div>
    </div>
<?php else: ?>
    <div class="grid grid-cols-2 gap-6">
        <?php foreach ($venues as $venue): ?>
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 8px;">
                        <div>
                            <h3 style="font-size: 18px; margin-bottom: 2px;"><?= e($venue['name']) ?></h3>
                            <div class="text-xs text-muted"><?= e($venue['address']) ?>, <?= e($venue['city']) ?> · <?= e($venue['court_count']) ?> <?= $venue['court_count'] === 1 ? 'court' : 'courts' ?></div>
                        </div>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; justify-content: flex-end;">
                            <?= status_badge($venue['status']) ?>
                            <?php if ($venue['status'] === 'approved' && !$venue['is_active']): ?>
                                <span class="badge badge-inactive">Deactivated by you</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-sm text-muted" style="margin-bottom: 12px;">
                        <?= e(implode(' · ', array_column($venue['sports'], 'name'))) ?>
                    </div>
                    <?php if ($venue['status'] === 'rejected'): ?>
                        <p class="text-sm" style="color: var(--color-danger); margin-bottom: 12px;">Not approved: <?= e($venue['rejection_reason']) ?></p>
                    <?php elseif ($venue['status'] === 'pending'): ?>
                        <p class="text-sm text-muted" style="margin-bottom: 12px;">Waiting for Platform Admin approval.</p>
                    <?php elseif ($venue['listed']): ?>
                        <p class="text-sm text-muted" style="margin-bottom: 12px;">Public page: <a href="<?= url($venue['public_path']) ?>"><?= e($venue['public_path']) ?></a></p>
                    <?php endif; ?>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; border-top: 1px solid var(--color-border); padding-top: 14px;">
                        <a href="<?= url('/owner/venues/' . $venue['id']) ?>" class="btn btn-sm btn-outline flex-1">Overview</a>
                        <?php if ($venue['can_edit']): ?>
                            <a href="<?= url('/owner/venues/' . $venue['id'] . '/edit') ?>" class="btn btn-sm btn-outline flex-1"><?= $venue['status'] === 'rejected' ? 'Edit and Resubmit' : 'Edit Info' ?></a>
                        <?php endif; ?>
                        <?php if ($venue['can_add_court']): ?>
                            <a href="<?= url('/owner/courts/create?venue=' . $venue['id']) ?>" class="btn btn-sm btn-primary flex-1">Add Court</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
