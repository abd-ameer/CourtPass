<?php
// Sample rows matching the seed registrations of the demo customer until registration is built.
$rows = [
    ['id' => 3, 'session_id' => 2, 'title' => 'Intermediate Rally Drills', 'sport' => 'Badminton', 'coach' => 'Ashan Weerasinghe', 'court' => 'Badminton Court 2',
     'starts_at' => relative_date(5, '08:00:00'), 'fee' => 1800, 'status' => 'registered'],
    ['id' => 1, 'session_id' => 1, 'title' => 'Beginner Badminton Basics', 'sport' => 'Badminton', 'coach' => 'Ashan Weerasinghe', 'court' => 'Badminton Court 2',
     'starts_at' => relative_date(-2, '08:00:00'), 'fee' => 1500, 'status' => 'attended'],
];
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
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td>
                            <strong>#<?= (int) $r['id'] ?></strong>
                            <div class="text-xs text-muted">Paid online</div>
                        </td>
                        <td>
                            <a href="<?= url('/sessions/' . $r['session_id']) ?>"><strong><?= e($r['title']) ?></strong></a>
                            <div class="text-xs text-muted"><?= e($r['sport']) ?> · 1 hour</div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <strong style="font-size: 13px;"><?= e($r['coach']) ?></strong>
                                <span class="badge badge-verified" style="font-size: 10px; padding: 1px 5px;">Verified</span>
                            </div>
                        </td>
                        <td>
                            <strong>Colombo Sports Hub</strong>
                            <div class="text-xs text-muted"><?= e($r['court']) ?> · <?= e(format_datetime($r['starts_at'])) ?></div>
                        </td>
                        <td><strong><?= e(lkr($r['fee'])) ?></strong></td>
                        <td><?= status_badge($r['status']) ?></td>
                        <td style="text-align: right;">
                            <?php if ($r['status'] === 'registered'): ?>
                                <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Cancel Registration', 'Cancel this registration? The refund depends on how long before the session you cancel.', 'Cancel Registration', '/customer/registrations/<?= (int) $r['id'] ?>/cancel')">
                                    Cancel Registration
                                </button>
                            <?php else: ?>
                                <span class="badge badge-completed" style="margin-right: 6px;">Reviewed</span>
                                <a href="<?= url('/customer/registrations/' . $r['id'] . '/review') ?>" class="btn btn-sm btn-outline">My Review</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
