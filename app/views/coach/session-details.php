<?php
/** @var array $session */
$s = $session;
$shareUrl = url($s['share_path']);
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions') ?>">Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <span>#<?= (int) $s['id'] ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <h1 class="page-title"><?= e($s['title']) ?></h1>
            <?= status_badge($s['status']) ?>
            <span class="badge <?= $s['visibility'] === 'private' ? 'badge-warning' : 'badge-confirmed' ?>"><?= $s['visibility'] === 'private' ? 'Private' : 'Public' ?></span>
        </div>
        <div class="page-subtitle">Session #<?= (int) $s['id'] ?> at <?= e($s['venue_name']) ?>, <?= e($s['court_name']) ?></div>
    </div>
    <div style="display: flex; gap: 10px;">
        <?php if ($s['can_edit']): ?>
            <a href="<?= url('/coach/sessions/' . $s['id'] . '/edit') ?>" class="btn btn-outline">Edit Session</a>
        <?php endif; ?>
        <?php if ($s['status'] === 'completed'): ?>
            <a href="<?= url('/coach/sessions/' . $s['id'] . '/attendance') ?>" class="btn btn-primary">Mark Attendance</a>
        <?php endif; ?>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <div style="grid-column: span 2;">
        <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Session Overview</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
                    <div>
                        <div class="text-xs text-muted">Venue</div>
                        <div style="font-weight: 600;"><?= e($s['venue_name']) ?></div>
                        <div class="text-xs text-muted"><?= e($s['venue_address']) ?>, <?= e($s['venue_city']) ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Court</div>
                        <div style="font-weight: 600;"><?= e($s['court_name']) ?></div>
                        <div class="text-xs text-muted"><?= e($s['sport_name']) ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Date and Time</div>
                        <div style="font-weight: 600;"><?= e(format_datetime($s['session_date'], false)) ?></div>
                        <div class="text-xs text-muted"><?= e($s['start']) ?> to <?= e($s['end']) ?> (1 hour)</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Fee and Capacity</div>
                        <div style="font-weight: 600;"><?= e($s['fee_label']) ?><?= $s['fee'] > 0 ? ' per person' : '' ?></div>
                        <div class="text-xs text-muted"><?= (int) $s['registration_count'] ?> of <?= (int) $s['capacity'] ?> places taken</div>
                    </div>
                </div>
                <div class="text-xs text-muted" style="margin-bottom: 4px;">Description</div>
                <p style="font-size: 14px; line-height: 1.7; margin-bottom: 0;"><?= nl2br(e($s['description'] ?? '')) ?></p>
            </div>
        </div>

        <?php if ($s['status'] === 'cancelled'): ?>
            <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4); border: 1px solid var(--color-danger-border);">
                <strong>Cancelled <?= e(format_datetime($s['cancelled_at'])) ?>.</strong> Reason: <?= e($s['cancel_reason'] ?? '') ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Registrations (<?= (int) $s['registration_count'] ?> / <?= (int) $s['capacity'] ?>)</h3>
                <a href="<?= url('/coach/sessions/' . $s['id'] . '/registrations') ?>" style="font-size: 12px; font-weight: 600;">Full list &rarr;</a>
            </div>
            <?php if ($s['registrations'] === []): ?>
                <div class="empty-state">
                    <div class="empty-state-desc">No one has registered yet.</div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Registered</th>
                                <th>Paid</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($s['registrations'] as $r): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($r['customer_name']) ?></strong>
                                        <div class="text-xs text-muted"><?= e($r['customer_email']) ?></div>
                                    </td>
                                    <td><?= e(format_datetime($r['created_at'])) ?></td>
                                    <td>
                                        <?= $r['paid_amount'] !== null ? e(lkr($r['paid_amount'])) : '<span class="text-muted">Not paid</span>' ?>
                                        <?php if ($r['refund_amount'] !== null): ?>
                                            <div class="text-xs text-muted">Refunded <?= e(lkr($r['refund_amount'])) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= status_badge($r['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;"><?= $s['visibility'] === 'private' ? 'Private Link' : 'Share Link' ?></h3>
            </div>
            <div class="card-body">
                <p class="text-xs text-muted" style="margin-bottom: 8px;">
                    <?= $s['visibility'] === 'private'
                        ? 'Only people with this link can open and join the session.'
                        : 'This session is listed on the Coaching page. Share this link to send people straight to it.' ?>
                </p>
                <input type="text" id="shareLink" class="form-control" value="<?= e($shareUrl) ?>" readonly>
                <button type="button" class="btn btn-sm btn-outline" style="margin-top: 8px; width: 100%;" onclick="copyShareLink()">Copy Link</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Actions</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 8px;">
                <?php if ($s['can_edit']): ?>
                    <a href="<?= url('/coach/sessions/' . $s['id'] . '/edit') ?>" class="btn btn-outline" style="text-align: center;">Edit Details</a>
                <?php endif; ?>
                <a href="<?= url('/coach/sessions/' . $s['id'] . '/registrations') ?>" class="btn btn-outline" style="text-align: center;">View Registrations</a>
                <?php if ($s['status'] === 'completed'): ?>
                    <a href="<?= url('/coach/sessions/' . $s['id'] . '/attendance') ?>" class="btn btn-primary" style="text-align: center;">Mark Attendance</a>
                <?php endif; ?>
                <?php if ($s['can_cancel']): ?>
                    <a href="<?= url('/coach/sessions/' . $s['id'] . '/cancel') ?>" class="btn btn-secondary" style="text-align: center; color: var(--color-danger);">Cancel Session</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('shareLink').value = new URL(document.getElementById('shareLink').value, window.location.origin).href;

function copyShareLink() {
    const input = document.getElementById('shareLink');
    const link = new URL(input.value, window.location.origin).href;
    (navigator.clipboard ? navigator.clipboard.writeText(link) : Promise.reject())
        .then(() => CourtPassApp.showToast('success', 'Copied', 'The session link is on your clipboard.'))
        .catch(() => { input.value = link; input.select(); });
}
</script>
