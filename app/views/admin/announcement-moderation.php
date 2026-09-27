<?php
// Sample rows matching the seed announcements until announcement moderation is built.
$announcements = [
    ['id' => 2, 'venue' => 'Colombo Sports Hub', 'owner' => 'Kamal Perera', 'type' => 'promotional', 'title' => 'Weekday morning discount', 'body' => 'Book before 10 am on weekdays and look out for flash deals.'],
    ['id' => 1, 'venue' => 'Colombo Sports Hub', 'owner' => 'Kamal Perera', 'type' => 'operational', 'title' => 'Court B maintenance', 'body' => 'Futsal Court B will get new turf next week.'],
];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Announcement Moderation</span>
        </div>
        <h1 class="page-title">Announcement Moderation</h1>
        <div class="page-subtitle">Active venue announcements. Removing one hides it from the venue page; it is kept with the reason.</div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr><th>Announcement</th><th>Venue</th><th>Type</th><th style="text-align: right;">Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($announcements as $a): ?>
                    <tr>
                        <td><strong><?= e($a['title']) ?></strong><div class="text-xs text-muted"><?= e($a['body']) ?></div></td>
                        <td><?= e($a['venue']) ?><div class="text-xs text-muted">Posted by <?= e($a['owner']) ?></div></td>
                        <td><span class="badge <?= $a['type'] === 'promotional' ? '' : 'badge-confirmed' ?>"<?= $a['type'] === 'promotional' ? ' style="background: #f5f3ff; color: #7c3aed;"' : '' ?>><?= e(ucfirst($a['type'])) ?></span></td>
                        <td style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-outline" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Remove Announcement', 'A reason is required.', '/admin/announcements/<?= (int) $a['id'] ?>/remove')">Remove...</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
