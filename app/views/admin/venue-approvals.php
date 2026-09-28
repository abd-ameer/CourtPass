<?php
/** @var array $venues pending @var array $all @var string $filter @var array $counts */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Venue Approvals</span>
        </div>
        <h1 class="page-title">Venue Approvals</h1>
        <div class="page-subtitle">Review newly registered venues and approve them for public listing, or reject them with a reason. Every venue on the platform is listed below.</div>
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
                            <?= e($venue['address'] . (stripos($venue['address'], $venue['city']) === false ? ', ' . $venue['city'] : '')) ?> · Submitted <?= e(format_datetime($venue['created_at'])) ?>
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

<?php $labels = ['' => 'All', 'listed' => 'Listed', 'off' => 'Switched Off', 'pending' => 'Pending', 'rejected' => 'Rejected', 'deactivated' => 'Deactivated by Admin']; ?>
<div class="card" style="margin-top: var(--space-6);">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <h3 style="font-size: 16px; margin-bottom: 0;">All Venues</h3>
        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
            <?php foreach ($labels as $key => $label): ?>
                <a href="<?= url('/admin/venues' . ($key === '' ? '' : '?status=' . $key)) ?>" class="btn btn-sm <?= $filter === $key ? 'btn-primary' : 'btn-outline' ?>"><?= e($label) ?> (<?= (int) $counts[$key] ?>)</a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if ($all === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No venues in this group</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Venue</th><th>Owner</th><th>Sports</th><th>Courts</th><th>Status</th><th style="text-align: right;">Action</th></tr></thead>
                <tbody>
                <?php foreach ($all as $v): ?>
                    <tr>
                        <td><strong><?= e($v['name']) ?></strong><div class="text-xs text-muted"><?= e($v['city']) ?> · Registered <?= e(format_datetime($v['created_at'], false)) ?></div></td>
                        <td><?= e($v['owner_name']) ?><div class="text-xs text-muted"><?= e($v['owner_email']) ?></div></td>
                        <td class="text-sm"><?= e(implode(', ', array_column($v['sports'], 'name'))) ?></td>
                        <td><?= (int) $v['court_count'] ?></td>
                        <td><?= $v['status'] === 'approved' && !$v['is_active'] ? '<span class="badge badge-inactive">Switched Off</span>' : status_badge($v['status']) ?></td>
                        <td style="text-align: right;"><a href="<?= url('/admin/venues/' . $v['id']) ?>" class="btn btn-sm btn-outline">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
