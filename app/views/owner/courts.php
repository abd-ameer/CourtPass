<?php
/** @var int $venueId venue to preselect when adding a court @var array $venues the owner's venues, each with its courts */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Court Management</span>
        </div>
        <h1 class="page-title">Courts</h1>
        <div class="page-subtitle">Every court at your venues with its sport, hourly rate and state.</div>
    </div>
    <a href="<?= url('/owner/courts/create' . ($venueId ? '?venue=' . $venueId : '')) ?>" class="btn btn-primary">
        + Add New Court
    </a>
</div>

<?php if ($venues === []): ?>
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-title">No venues yet</div>
            <div class="empty-state-desc">Register a venue first. Courts can be added once the Platform Admin approves it.</div>
            <a href="<?= url('/owner/venues/create') ?>" class="btn btn-primary" style="margin-top: 12px;">+ Register a Venue</a>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($venues as $v): ?>
    <div class="card" style="margin-bottom: var(--space-6);">
        <div class="card-header">
            <div>
                <h3 style="font-size: 15px; margin-bottom: 0;"><a href="<?= url('/owner/venues/' . $v['id']) ?>"><?= e($v['name']) ?></a></h3>
                <span class="text-xs text-muted"><?= e($v['city']) ?> · <?= count($v['courts']) ?> <?= count($v['courts']) === 1 ? 'court' : 'courts' ?></span>
            </div>
            <?= status_badge($v['status']) ?>
        </div>
        <?php if ($v['courts'] === []): ?>
            <div class="card-body text-sm">
                <?= $v['status'] === 'approved' ? 'No courts yet.' : 'Courts can be added after the venue is approved.' ?>
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
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($v['courts'] as $c): ?>
                            <tr>
                                <td><strong><?= e($c['name']) ?></strong></td>
                                <td><span class="badge badge-confirmed"><?= e($c['sport_name']) ?></span></td>
                                <td><strong><?= e(lkr($c['hourly_rate'])) ?></strong></td>
                                <td><span class="badge <?= $c['is_active'] ? 'badge-active' : 'badge-inactive' ?>"><?= $c['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                                <td style="text-align: right;">
                                    <a href="<?= url('/owner/courts/' . $c['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                                    <a href="<?= url('/owner/courts/' . $c['id'] . '/hours') ?>" class="btn btn-sm btn-outline">Hours</a>
                                    <a href="<?= url('/owner/slots?court=' . $c['id']) ?>" class="btn btn-sm btn-primary">Slots</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
