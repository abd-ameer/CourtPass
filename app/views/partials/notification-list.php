<?php
/**
 * In-app notification list for the notifications pages.
 * @var string $home dashboard path for the breadcrumb
 * @var array $items each: title, message, date, link, link_label
 */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url($home) ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Notifications</span>
        </div>
        <h1 class="page-title">Notifications</h1>
        <div class="page-subtitle">In-app updates about your account. CourtPass sends no email or SMS.</div>
    </div>
    <form method="POST" action="<?= url('/notifications/read') ?>" class="inline-form">
        <?= csrf_field() ?>
        <button type="submit" class="btn btn-outline btn-sm">Mark All as Read</button>
    </form>
</div>

<div class="card">
    <?php if ($items === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No notifications yet</div>
            <div class="empty-state-desc">Updates about your account appear here.</div>
        </div>
    <?php endif; ?>
    <?php foreach ($items as $n): ?>
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; gap: 14px;">
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <strong style="color: var(--color-text-title); font-size: 14px;"><?= e($n['title']) ?></strong>
                    <span class="text-xs text-muted"><?= e(format_datetime($n['date'], false)) ?></span>
                </div>
                <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;"><?= e($n['message']) ?></p>
                <a href="<?= url($n['link']) ?>" style="font-size: 12px; font-weight: 700;"><?= e($n['link_label']) ?> &rarr;</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
