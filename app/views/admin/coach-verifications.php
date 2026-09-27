<?php
// Sample rows matching the seed coaches until coach verification is built.
$pending = [
    ['id' => 8, 'name' => 'Dilani Rathnayake', 'email' => 'dilani@coach.lk', 'phone' => '0778901234', 'sports' => 'Futsal', 'level' => 'Intermediate',
     'certs' => 'None listed', 'bio' => 'Futsal coach focusing on school teams and beginners.'],
];
$verified = [
    ['id' => 7, 'name' => 'Ashan Weerasinghe', 'sports' => 'Badminton, Pickleball', 'level' => 'Professional', 'certs' => 'BWF Level 1 Coach'],
];
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
