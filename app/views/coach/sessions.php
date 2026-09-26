<?php
/** @var array $sessions @var array $counts @var array $venues @var string $status @var int $venueId */
$tabs = ['' => 'All', 'upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
$filterUrl = function (string $tab) use ($venueId): string {
    $query = array_filter(['status' => $tab, 'venue' => $venueId > 0 ? $venueId : '']);
    return url('/coach/sessions' . ($query === [] ? '' : '?' . http_build_query($query)));
};
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Sessions</span>
        </div>
        <h1 class="page-title">My Coaching Sessions</h1>
        <div class="page-subtitle">Your upcoming, completed and cancelled sessions across your approved venues.</div>
    </div>
    <a href="<?= url('/coach/sessions/create') ?>" class="btn btn-primary">+ Create New Session</a>
</div>

<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-4);">
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <?php foreach ($tabs as $key => $label): ?>
            <a href="<?= $filterUrl($key) ?>" class="btn btn-sm <?= $status === $key ? 'btn-primary' : 'btn-outline' ?>"><?= e($label) ?> (<?= (int) $counts[$key] ?>)</a>
        <?php endforeach; ?>
        <?php if ($venues !== []): ?>
            <form method="GET" action="<?= url('/coach/sessions') ?>" style="margin-left: auto;">
                <?php if ($status !== ''): ?>
                    <input type="hidden" name="status" value="<?= e($status) ?>">
                <?php endif; ?>
                <select name="venue" class="form-select" style="max-width: 240px;" onchange="this.form.submit()">
                    <option value="">All Venues</option>
                    <?php foreach ($venues as $id => $name): ?>
                        <option value="<?= (int) $id ?>" <?= $venueId === (int) $id ? 'selected' : '' ?>><?= e($name) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <?php if ($sessions === []): ?>
        <div class="empty-state">
            <div class="empty-state-title"><?= $counts[''] === 0 ? 'No sessions yet' : 'No sessions match this filter' ?></div>
            <div class="empty-state-desc">Create a one-hour session on a court at a venue that has approved you.</div>
            <a href="<?= url('/coach/sessions/create') ?>" class="btn btn-primary" style="margin-top: 12px;">Create a Session</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Venue &amp; Court</th>
                        <th>Date &amp; Time</th>
                        <th>Registrations</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td>
                                <strong><?= e($s['title']) ?></strong>
                                <div class="text-xs text-muted">#<?= (int) $s['id'] ?> · <?= e($s['fee_label']) ?><?= $s['fee'] > 0 ? ' per person' : '' ?></div>
                            </td>
                            <td>
                                <strong><?= e($s['venue_name']) ?></strong>
                                <div class="text-xs text-muted"><?= e($s['court_name']) ?> · <?= e($s['sport_name']) ?></div>
                            </td>
                            <td>
                                <strong><?= e(format_datetime($s['session_date'], false)) ?></strong>
                                <div class="text-xs text-muted"><?= e($s['start']) ?> to <?= e($s['end']) ?></div>
                            </td>
                            <td>
                                <strong><?= (int) $s['registration_count'] ?> / <?= (int) $s['capacity'] ?></strong>
                                <?php if ($s['group'] === 'upcoming'): ?>
                                    <div class="text-xs text-muted"><?= $s['spots_left'] > 0 ? (int) $s['spots_left'] . ' places left' : 'Full' ?></div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?= $s['visibility'] === 'private' ? 'badge-warning' : 'badge-confirmed' ?>"><?= $s['visibility'] === 'private' ? 'Private' : 'Public' ?></span></td>
                            <td><?= status_badge($s['status']) ?></td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="<?= url('/coach/sessions/' . $s['id']) ?>" class="btn btn-sm btn-outline">View</a>
                                    <?php if ($s['can_edit']): ?>
                                        <a href="<?= url('/coach/sessions/' . $s['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                                    <?php endif; ?>
                                    <?php if ($s['can_cancel']): ?>
                                        <a href="<?= url('/coach/sessions/' . $s['id'] . '/cancel') ?>" class="btn btn-sm btn-secondary" style="color: var(--color-danger);">Cancel</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
