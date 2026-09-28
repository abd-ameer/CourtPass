<?php
/** @var array $requests from CoachService::ownerRequests() */
$pending = array_map(fn (array $r) => [
    'id' => $r['id'], 'name' => $r['coach_name'], 'email' => $r['coach_email'], 'phone' => $r['coach_phone'], 'sports' => $r['sport_names'],
    'level' => ucfirst($r['experience_level']), 'certs' => $r['certifications'] ?: 'None listed', 'verified' => $r['is_verified'],
    'venue' => $r['venue_name'], 'requested' => $r['requested_at'],
], $requests['pending']);
$approved = array_map(fn (array $r) => [
    'id' => $r['id'], 'name' => $r['coach_name'], 'certs' => $r['certifications'] ?: 'No certifications listed', 'sports' => $r['sport_names'],
    'verified' => $r['is_verified'], 'venue' => $r['venue_name'], 'upcoming' => $r['upcoming_count'], 'next' => $r['next_session_at'],
], $requests['approved']);
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Coach Management</span>
        </div>
        <h1 class="page-title">Coach Requests</h1>
        <div class="page-subtitle">Coaches ask each venue for approval before they can run paid one-hour sessions on its courts.</div>
    </div>
</div>

<!-- Pending requests -->
<div class="card" style="margin-bottom: var(--space-8); border: 1.5px solid #fde68a;">
    <div class="card-header" style="background: #fffbeb;">
        <h3 style="font-size: 15px; color: #92400e; margin-bottom: 0;">Pending Requests (<?= count($pending) ?>)</h3>
        <span class="badge badge-warning">Review Pending</span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Coach</th>
                    <th>Sports</th>
                    <th>Experience & Certifications</th>
                    <th>Venue</th>
                    <th style="text-align: right;">Decision</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($pending === []): ?>
                    <tr><td colspan="5" class="text-sm text-muted">No coach requests are waiting for you.</td></tr>
                <?php endif; ?>
                <?php foreach ($pending as $c): ?>
                    <tr>
                        <td>
                            <strong><?= e($c['name']) ?></strong>
                            <?php if (!$c['verified']): ?><span class="badge badge-secondary" style="font-size: 10px; margin-left: 4px;">Not verified yet</span><?php endif; ?>
                            <div class="text-xs text-muted"><?= e($c['email']) ?> · <?= e($c['phone']) ?></div>
                        </td>
                        <td><span class="badge badge-confirmed"><?= e($c['sports']) ?></span></td>
                        <td>
                            <strong><?= e($c['level']) ?></strong>
                            <div class="text-xs text-muted"><?= e($c['certs']) ?></div>
                        </td>
                        <td>
                            <strong><?= e($c['venue']) ?></strong>
                            <div class="text-xs text-muted">Requested <?= e(format_datetime($c['requested'], false)) ?></div>
                        </td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Approve Coach', 'Allow this coach to create sessions at your venue?', 'Approve', '/owner/coach-requests/<?= (int) $c['id'] ?>/approve')">
                                Approve Coach
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Decline Coach Request', 'A reason is required and is shown to the coach.', '/owner/coach-requests/<?= (int) $c['id'] ?>/decline')">
                                Decline...
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Approved coaches -->
<div class="card">
    <div class="card-header">
        <h3 style="font-size: 15px; margin-bottom: 0;">Approved Coaches (<?= count($approved) ?>)</h3>
        <span class="text-xs text-muted">Coaches allowed to schedule one-hour sessions</span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Coach</th>
                    <th>Sports</th>
                    <th>Verification</th>
                    <th>Venue</th>
                    <th>Upcoming Sessions</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($approved === []): ?>
                    <tr><td colspan="6" class="text-sm text-muted">No coaches are approved at your venues yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($approved as $c): ?>
                    <tr>
                        <td>
                            <strong><?= e($c['name']) ?></strong>
                            <div class="text-xs text-muted"><?= e($c['certs']) ?></div>
                        </td>
                        <td><?= e($c['sports']) ?></td>
                        <td><?php if ($c['verified']): ?><span class="badge badge-verified">Verified</span><?php else: ?><span class="badge badge-secondary">Not verified yet</span><?php endif; ?></td>
                        <td><?= e($c['venue']) ?></td>
                        <td><strong><?= (int) $c['upcoming'] ?></strong><?php if ($c['next'] !== null): ?> <span class="text-xs text-muted">(next <?= e(format_datetime($c['next'])) ?>)</span><?php endif; ?></td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Revoke Approval', 'The coach keeps existing sessions but cannot create new ones here.', 'Revoke', '/owner/coach-requests/<?= (int) $c['id'] ?>/revoke')">
                                Revoke Approval...
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
