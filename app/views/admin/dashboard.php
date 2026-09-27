<?php
/** @var array $pendingVenues @var int $listedVenues @var int $listedCourts @var array $reviewCounts flagged, active, removed */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <span>Admin Portal</span>
            <span class="breadcrumb-separator">/</span>
            <span>Dashboard</span>
        </div>
        <h1 class="page-title">Platform Administration</h1>
        <div class="page-subtitle">Venue approvals, review reports, coach verification and dispute queues.</div>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?= url('/admin/venues') ?>" class="btn btn-primary">
            Review Venues (<?= count($pendingVenues) ?>)
        </a>
        <a href="<?= url('/admin/coaches') ?>" class="btn btn-outline">
            Coach Verification
        </a>
    </div>
</div>

<!-- Admin KPI Stats -->
<div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
            <div class="stat-value"><?= count($pendingVenues) ?></div>
            <div class="stat-label">Venues Awaiting Approval</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $listedVenues ?></div>
            <div class="stat-label">Listed Venues (<?= (int) $listedCourts ?> <?= (int) $listedCourts === 1 ? 'Court' : 'Courts' ?>)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $reviewCounts['active'] ?></div>
            <div class="stat-label">Published Venue Reviews</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $reviewCounts['flagged'] ?></div>
            <div class="stat-label">Reviews Reported by Owners</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">

    <!-- Pending venues -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">Pending Venues</h3>
            <span class="badge badge-pending"><?= count($pendingVenues) ?> Pending</span>
        </div>
        <div class="card-body">
            <?php if ($pendingVenues === []): ?>
                <p class="text-sm" style="margin-bottom: 0;">No venues are waiting for approval.</p>
            <?php endif; ?>
            <?php foreach ($pendingVenues as $v): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--color-border);">
                    <div>
                        <strong><?= e($v['name']) ?></strong>
                        <div class="text-xs text-muted"><?= e($v['city']) ?> · <?= e(implode(', ', array_column($v['sports'], 'name'))) ?> · Submitted <?= e(format_datetime($v['created_at'])) ?> by <?= e($v['owner_name']) ?></div>
                    </div>
                    <a href="<?= url('/admin/venues/' . $v['id']) ?>" class="btn btn-sm btn-outline">Review</a>
                </div>
            <?php endforeach; ?>
            <a href="<?= url('/admin/venues') ?>" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 12px;">Venue Approvals &rarr;</a>
        </div>
    </div>

    <!-- Queues -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">Moderation & Disputes</h3>
        </div>
        <div class="card-body">
            <p class="text-xs text-muted" style="margin-bottom: 12px;">Owner reports on reviews, coach verification after offline checks, and customer disputes.</p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?= url('/admin/reviews') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Review Moderation (<?= (int) $reviewCounts['flagged'] ?> reported)</a>
                <a href="<?= url('/admin/coaches') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Coach Verification</a>
                <a href="<?= url('/admin/disputes/no-show') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">No-Show Disputes</a>
                <a href="<?= url('/admin/disputes/resale') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Resale Disputes</a>
                <a href="<?= url('/admin/users') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">User Management</a>
            </div>
        </div>
    </div>

</div>
