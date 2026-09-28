<?php
/** @var array $coaches from CoachService::adminCoaches() */
$row = fn (array $c) => [
    'id' => $c['coach_id'], 'name' => $c['name'], 'email' => $c['email'], 'phone' => $c['phone'],
    'sports' => $c['sport_names'], 'level' => ucfirst($c['experience_level']),
    'certs' => $c['certifications'] ?: 'None listed', 'bio' => $c['bio'] ?? '',
];
$pending = array_map($row, array_values(array_filter($coaches, fn (array $c) => !$c['is_verified'])));
$verified = array_map($row, array_values(array_filter($coaches, fn (array $c) => $c['is_verified'])));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Coach Verification</span>
        </div>
        <h1 class="page-title">Coach Verification</h1>
        <div class="page-subtitle">Set the Verified badge after offline identity and certification checks. No ID numbers or documents are collected.</div>
    </div>
</div>

<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <h3 style="font-size: 15px; margin-bottom: 0;">Awaiting Verification (<?= count($pending) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr><th>Coach</th><th>Sports & Experience</th><th>Certifications (as listed)</th><th style="text-align: right;">Action</th></tr>
            </thead>
            <tbody>
                <?php if ($pending === []): ?>
                    <tr><td colspan="4" class="text-sm text-muted">No coaches are waiting for verification.</td></tr>
                <?php endif; ?>
                <?php foreach ($pending as $c): ?>
                    <tr>
                        <td><strong><?= e($c['name']) ?></strong><div class="text-xs text-muted"><?= e($c['email']) ?> · <?= e($c['phone']) ?></div></td>
                        <td><?= e($c['sports']) ?><div class="text-xs text-muted"><?= e($c['level']) ?> · <?= e($c['bio']) ?></div></td>
                        <td class="text-sm"><?= e($c['certs']) ?></td>
                        <td style="text-align: right;">
                            <button class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Verify Coach', 'Confirm the offline identity checks are complete. No ID documents are stored.', 'Mark Verified', '/admin/coaches/<?= (int) $c['id'] ?>/verify')">Grant Verified Badge</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="font-size: 15px; margin-bottom: 0;">Verified Coaches (<?= count($verified) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr><th>Coach</th><th>Sports & Experience</th><th>Certifications</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php if ($verified === []): ?>
                    <tr><td colspan="4" class="text-sm text-muted">No verified coaches yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($verified as $c): ?>
                    <tr>
                        <td><strong><?= e($c['name']) ?></strong></td>
                        <td><?= e($c['sports']) ?><div class="text-xs text-muted"><?= e($c['level']) ?></div></td>
                        <td class="text-sm"><?= e($c['certs']) ?></td>
                        <td><span class="badge badge-verified">Verified</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
