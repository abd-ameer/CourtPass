<?php
/** @var array $sessions from CoachSessionService::ownerSessions(): upcoming first */
$upcoming = array_values(array_filter($sessions, fn (array $s) => $s['group'] === 'upcoming'));
$past = array_values(array_filter($sessions, fn (array $s) => $s['group'] !== 'upcoming'));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Coaching Sessions</span>
        </div>
        <h1 class="page-title">Coaching Sessions</h1>
        <div class="page-subtitle">Sessions that approved coaches run on your courts. Each one holds its court slot, shown as blocked on your slot grid.</div>
    </div>
    <a href="<?= url('/owner/coach-requests') ?>" class="btn btn-outline">Coach Approvals</a>
</div>

<?php foreach (['Upcoming Sessions' => $upcoming, 'Past and Cancelled Sessions' => $past] as $heading => $rows): ?>
    <div class="card" style="margin-bottom: var(--space-6);">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;"><?= e($heading) ?> (<?= count($rows) ?>)</h3>
        </div>
        <?php if ($rows === []): ?>
            <div class="card-body text-sm text-muted"><?= $heading === 'Upcoming Sessions' ? 'No upcoming coaching sessions at your venues.' : 'No past sessions yet.' ?></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead><tr><th>Session</th><th>Coach</th><th>Venue &amp; Court</th><th>Date &amp; Time</th><th>Registrations</th><th>Status</th><th style="text-align: right;">Action</th></tr></thead>
                    <tbody>
                    <?php foreach ($rows as $s): ?>
                        <tr>
                            <td><strong><?= e($s['title']) ?></strong><div class="text-xs text-muted">#<?= (int) $s['id'] ?> · <?= e(ucfirst($s['visibility'])) ?> · <?= e($s['fee_label']) ?></div></td>
                            <td><a href="<?= url('/coaches/' . $s['coach_id']) ?>"><?= e($s['coach_name']) ?></a><?php if ($s['coach_verified']): ?> <span class="badge badge-verified" style="font-size: 10px;">Verified</span><?php endif; ?></td>
                            <td><?= e($s['venue_name']) ?><div class="text-xs text-muted"><?= e($s['court_name']) ?> · <?= e($s['sport']) ?></div></td>
                            <td><?= e(format_datetime($s['starts_at'], false)) ?><div class="text-xs text-muted"><?= e($s['start']) ?> to <?= e($s['end']) ?></div></td>
                            <td><?= (int) $s['live_count'] ?> / <?= (int) $s['capacity'] ?></td>
                            <td><?= status_badge($s['status']) ?></td>
                            <td style="text-align: right;">
                                <?php if ($s['can_cancel']): ?>
                                    <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Cancel Coaching Session', 'A reason is required and is sent to the coach and every registered student.', '/owner/sessions/<?= (int) $s['id'] ?>/cancel')">Cancel...</button>
                                <?php else: ?>
                                    <span class="text-xs text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
