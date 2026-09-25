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
            <span><?= e($venue['name']) ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <h1 class="page-title"><?= e($venue['name']) ?></h1>
            <?= status_badge($venue['status']) ?>
            <?php if ($venue['status'] === 'approved' && !$venue['is_active']): ?>
                <span class="badge badge-inactive">Deactivated by you</span>
            <?php endif; ?>
        </div>
        <div class="page-subtitle">
            <?php if ($venue['listed']): ?>
                Public page: <a href="<?= url($venue['public_path']) ?>"><?= e($venue['public_path']) ?></a>
            <?php elseif ($venue['status'] === 'approved'): ?>
                Hidden from public listings until you activate it again.
            <?php elseif ($venue['status'] === 'pending'): ?>
                Waiting for Platform Admin approval. Courts can be added once it is approved.
            <?php elseif ($venue['status'] === 'rejected'): ?>
                Not approved. Edit the venue to submit it again.
            <?php else: ?>
                Deactivated by the platform.
            <?php endif; ?>
        </div>
    </div>
    <div style="display: flex; gap: 8px;">
        <?php if ($venue['can_edit']): ?>
            <a href="<?= url('/owner/venues/' . $venue['id'] . '/edit') ?>" class="btn btn-outline">Edit Venue</a>
        <?php endif; ?>
        <?php if ($venue['can_add_court']): ?>
            <a href="<?= url('/owner/courts/create?venue=' . $venue['id']) ?>" class="btn btn-primary">+ Add Court</a>
        <?php endif; ?>
    </div>
</div>

<?php if ($venue['status'] === 'rejected'): ?>
    <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4); border: 1px solid var(--color-danger-border);">
        <strong>Reason from the Platform Admin:</strong> <?= e($venue['rejection_reason']) ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
    <div>
        <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-6);">
            <h3 style="font-size: 16px; margin-bottom: 12px;">Venue Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13px;">
                <div>
                    <span class="text-muted">Address</span>
                    <div style="font-weight: 600;"><?= e($venue['address']) ?>, <?= e($venue['city']) ?></div>
                </div>
                <div>
                    <span class="text-muted">Contact Phone</span>
                    <div style="font-weight: 600;"><?= e($venue['contact_phone']) ?></div>
                </div>
                <div>
                    <span class="text-muted">Sports</span>
                    <div style="font-weight: 600;"><?= e(implode(', ', array_column($venue['sports'], 'name'))) ?></div>
                </div>
                <div>
                    <span class="text-muted">Registered</span>
                    <div style="font-weight: 600;"><?= e(format_datetime($venue['created_at'], false)) ?></div>
                </div>
            </div>
            <?php if ($venue['description'] !== null && $venue['description'] !== ''): ?>
                <p class="text-sm text-muted" style="margin-top: 14px; margin-bottom: 0;"><?= nl2br(e($venue['description'])) ?></p>
            <?php endif; ?>
        </div>

        <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-header">
                <h3 style="font-size: 16px; margin-bottom: 0;">Courts (<?= count($venue['courts']) ?>)</h3>
                <?php if ($venue['can_add_court']): ?>
                    <a href="<?= url('/owner/courts/create?venue=' . $venue['id']) ?>" style="font-size: 13px; font-weight: 600;">Add Court &rarr;</a>
                <?php endif; ?>
            </div>
            <?php if ($venue['courts'] === []): ?>
                <div class="empty-state">
                    <div class="empty-state-title">No courts yet</div>
                    <div class="empty-state-desc"><?= $venue['can_add_court'] ? 'Add a court with its operating hours to start taking bookings.' : 'Courts can be added once the venue is approved.' ?></div>
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
                            <?php foreach ($venue['courts'] as $court): ?>
                                <tr>
                                    <td><strong><?= e($court['name']) ?></strong></td>
                                    <td><?= e($court['sport_name']) ?></td>
                                    <td><?= e(lkr($court['hourly_rate'])) ?></td>
                                    <td><?= $court['is_active'] ? status_badge('active') : '<span class="badge badge-inactive">Inactive</span>' ?></td>
                                    <td style="text-align: right;">
                                        <a href="<?= url('/owner/courts/' . $court['id'] . '/hours') ?>" class="btn btn-sm btn-outline">Hours</a>
                                        <?php if ($venue['listed'] && $court['is_active']): ?>
                                            <a href="<?= url('/courts/' . $court['id']) ?>" class="btn btn-sm btn-outline">Slot Grid</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6);">
            <h3 style="font-size: 15px; margin-bottom: 12px;">Manage</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?= url('/owner/bookings') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">Bookings</a>
                <a href="<?= url('/owner/slots') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">Block Court Slots</a>
                <a href="<?= url('/owner/flash-slots') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">Create Flash Deal</a>
                <a href="<?= url('/owner/announcements') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">Post Announcement</a>
                <a href="<?= url('/owner/coach-requests') ?>" class="btn btn-outline btn-block" style="justify-content: flex-start;">Review Coach Requests</a>
                <?php if ($venue['can_deactivate']): ?>
                    <div style="border-top: 1px solid var(--color-border); margin: 8px 0;"></div>
                    <a href="<?= url('/owner/venues/' . $venue['id'] . '/deactivate') ?>" class="btn btn-secondary btn-block" style="color: var(--color-danger); justify-content: flex-start;">Deactivate Venue...</a>
                <?php elseif ($venue['can_activate']): ?>
                    <div style="border-top: 1px solid var(--color-border); margin: 8px 0;"></div>
                    <button type="button" class="btn btn-primary btn-block" onclick="CourtPassApp.confirmPost('Activate Venue', 'List this venue publicly and open it for bookings again?', 'Activate', '/owner/venues/<?= (int) $venue['id'] ?>/activate')">Activate Venue</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
