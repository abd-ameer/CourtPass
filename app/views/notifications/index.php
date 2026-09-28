<?php
/** @var array $items from NotificationService::recent() */
$unread = count(array_filter($items, fn (array $n) => !$n['is_read']));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url(Auth::homeUrl()) ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Notifications</span>
        </div>
        <h1 class="page-title">Notifications</h1>
        <div class="page-subtitle">In-app updates about your account<?= $unread > 0 ? ' · ' . $unread . ' unread' : '' ?>. CourtPass sends no email or SMS.</div>
    </div>
    <?php if ($unread > 0): ?>
        <form method="POST" action="<?= url('/notifications/read') ?>" class="inline-form">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline btn-sm">Mark All as Read</button>
        </form>
    <?php endif; ?>
</div>

<div class="card">
    <?php if ($items === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No notifications yet</div>
            <div class="empty-state-desc">Updates about your bookings, venues, sessions and reviews appear here.</div>
        </div>
    <?php endif; ?>
    <?php foreach ($items as $n): ?>
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; gap: 14px;<?= $n['is_read'] ? '' : ' background: rgba(132, 189, 74, 0.06);' ?>">
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <strong style="color: var(--color-text-title); font-size: 14px;"><?= e($n['title']) ?><?php if (!$n['is_read']): ?> <span class="badge badge-confirmed" style="margin-left: 6px;">New</span><?php endif; ?></strong>
                    <span class="text-xs text-muted"><?= e(format_datetime($n['created_at'])) ?></span>
                </div>
                <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;"><?= e($n['message']) ?></p>
                <?php if ($n['link'] !== null && $n['link'] !== ''): ?>
                    <a href="<?= url($n['link']) ?>" style="font-size: 12px; font-weight: 700;">Open &rarr;</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
