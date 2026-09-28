<?php
/** @var array $registrations from CoachSessionService::customerRegistrations(), latest session first */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>My Coaching Registrations</span>
        </div>
        <h1 class="page-title">My Coaching Sessions</h1>
        <div class="page-subtitle">Your session registrations, attendance and coach reviews.</div>
    </div>
    <a href="<?= url('/coaching') ?>" class="btn btn-primary" style="background: #7c3aed; border-color: #7c3aed;">
        + Find More Sessions
    </a>
</div>

<div class="card">
    <?php if ($registrations === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No coaching sessions yet</div>
            <div class="empty-state-desc">Sessions you register for appear here with your attendance and coach reviews.</div>
            <a href="<?= url('/coaching') ?>" class="btn btn-primary" style="margin-top: 12px;">Browse Coaching Sessions</a>
        </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Registration</th>
                    <th>Session & Sport</th>
                    <th>Coach</th>
                    <th>Venue & Date</th>
                    <th>Fee Paid</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $r): ?>
                    <tr>
                        <td>
                            <strong>#<?= (int) $r['id'] ?></strong>
                            <div class="text-xs text-muted"><?= $r['paid_amount'] !== null ? 'Paid online' : ($r['amount'] > 0 ? 'Not paid' : 'Free session') ?></div>
                        </td>
                        <td>
                            <a href="<?= url($r['session_path']) ?>"><strong><?= e($r['title']) ?></strong></a>
                            <div class="text-xs text-muted"><?= e($r['sport_name']) ?> · 1 hour<?= $r['visibility'] === 'private' ? ' · Private' : '' ?></div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <a href="<?= url('/coaches/' . $r['coach_id']) ?>" style="font-size: 13px; font-weight: 700;"><?= e($r['coach_name']) ?></a>
                                <?php if ($r['coach_verified']): ?><span class="badge badge-verified" style="font-size: 10px; padding: 1px 5px;">Verified</span><?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <strong><?= e($r['venue_name']) ?></strong>
                            <div class="text-xs text-muted"><?= e($r['court_name']) ?> · <?= e(format_datetime($r['starts_at'])) ?></div>
                        </td>
                        <td><strong><?= e($r['paid_amount'] !== null ? lkr($r['paid_amount']) : ($r['amount'] > 0 ? lkr($r['amount']) : 'Free')) ?></strong></td>
                        <td><?= status_badge($r['status']) ?></td>
                        <td style="text-align: right;">
                            <?php if ($r['can_cancel']): ?>
                                <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Cancel Registration', 'Cancel this registration? The refund depends on how long before the session you cancel.', 'Cancel Registration', '/customer/registrations/<?= (int) $r['id'] ?>/cancel')">
                                    Cancel Registration
                                </button>
                            <?php elseif ($r['review_id'] !== null): ?>
                                <span class="badge badge-completed" style="margin-right: 6px;">Reviewed</span>
                                <a href="<?= url('/customer/reviews') ?>" class="btn btn-sm btn-outline">My Review</a>
                            <?php elseif ($r['can_review']): ?>
                                <a href="<?= url('/customer/registrations/' . $r['id'] . '/review') ?>" class="btn btn-sm btn-primary">Review Coach</a>
                                <div class="text-xs text-muted" style="margin-top: 4px;">Until <?= e(format_datetime($r['review_until'])) ?></div>
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
