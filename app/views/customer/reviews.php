<?php
/** @var array $reviews @var array $to_review */
$stars = fn (int $n) => str_repeat('&#9733;', $n) . '<span style="color: #d1d5db;">' . str_repeat('&#9733;', 5 - $n) . '</span>';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>My Reviews</span>
        </div>
        <h1 class="page-title">My Reviews</h1>
        <div class="page-subtitle">Verified reviews of the venues you checked in at and the coaching sessions you attended.</div>
    </div>
</div>

<?php if ($to_review !== []): ?>
    <div class="card" style="margin-bottom: var(--space-6); border-left: 4px solid var(--color-primary);">
        <div class="card-header">
            <h3 style="font-size: 16px; margin-bottom: 0;">Waiting for your review</h3>
            <span class="text-xs text-muted">Within 7 days of the check-in</span>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 12px;">
            <?php foreach ($to_review as $b): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <strong><?= e($b['venue_name']) ?></strong>
                        <div class="text-xs text-muted">
                            Booking #<?= e($b['id']) ?> &middot; <?= e($b['court_name']) ?> &middot; <?= e(format_datetime($b['starts_at'])) ?>
                            &middot; review until <?= e(format_datetime($b['review_until'])) ?>
                        </div>
                    </div>
                    <a href="<?= url('/customer/bookings/' . $b['id'] . '/review') ?>" class="btn btn-sm btn-primary">Write Review</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($reviews === []): ?>
    <div class="card" style="padding: var(--space-8); text-align: center; color: var(--color-text-muted);">
        You have not written any reviews yet. After a venue checks you in, you can review your visit here for 7 days.
    </div>
<?php else: ?>
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <?php foreach ($reviews as $r): ?>
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <?php if ($r['is_venue']): ?>
                            <strong style="font-size: 15px;"><?= e($r['venue_name']) ?></strong>
                            <span class="badge badge-verified">Check-in Verified</span>
                        <?php else: ?>
                            <strong style="font-size: 15px;">Coach <?= e($r['coach_name']) ?></strong>
                            <span class="badge badge-verified">Attended Session Verified</span>
                        <?php endif; ?>
                        <?php if ($r['status'] === 'removed'): ?>
                            <span class="badge badge-danger">Removed</span>
                        <?php endif; ?>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <?php if ($r['can_edit']): ?>
                            <a href="<?= url('/customer/reviews/' . $r['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                        <?php endif; ?>
                        <?php if ($r['can_delete']): ?>
                            <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Delete Review', 'Delete this review? This cannot be undone.', 'Delete', '/customer/reviews/<?= (int) $r['id'] ?>', 'DELETE')">Delete</button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; gap: 8px; flex-wrap: wrap;">
                        <span style="color: #d97706; font-weight: 700; font-size: 14px;">
                            <?= $stars($r['rating']) ?> <?= e($r['rating']) ?>.0<?= $r['is_venue'] ? ' (' . e($r['court_name']) . ', ' . e(format_datetime($r['visit'], false)) . ')' : '' ?>
                        </span>
                        <span class="text-xs text-muted">
                            Submitted <?= e(format_datetime($r['created_at'], false)) ?>
                            <?php if ($r['can_edit']): ?>
                                &middot; editable until <?= e(format_datetime($r['review_until'])) ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 12px; white-space: pre-line;"><?= e($r['comment']) ?></p>

                    <?php if ($r['status'] === 'removed'): ?>
                        <div style="background: var(--color-danger-bg); border-left: 3px solid var(--color-danger); padding: 10px 14px; border-radius: var(--radius-md); font-size: 12px; margin-bottom: 12px;">
                            Removed by the Platform Admin: <?= e($r['removed_reason'] ?? 'Policy violation') ?>. It is no longer shown on the venue page.
                        </div>
                    <?php endif; ?>

                    <?php if ($r['response_text'] !== null): ?>
                        <div style="background: var(--color-bg-subtle); border-left: 3px solid <?= $r['is_venue'] ? 'var(--color-primary)' : '#7c3aed' ?>; padding: 12px 16px; border-radius: var(--radius-md);">
                            <div style="font-size: 12px; font-weight: 700; color: var(--color-text-title); margin-bottom: 2px;">
                                <?= $r['is_venue'] ? 'Response from ' . e($r['venue_name']) : 'Reply from Coach ' . e($r['coach_name']) ?>
                            </div>
                            <div style="font-size: 12px; color: var(--color-text-muted); white-space: pre-line;"><?= e($r['response_text']) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!$r['is_venue']): ?>
                        <div class="text-xs text-muted" style="margin-top: 10px;">Coach reviews are managed with your coaching sessions.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
