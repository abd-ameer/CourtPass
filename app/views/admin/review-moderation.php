<?php
/** @var array $reviews @var string $status @var array $counts */
$tabs = ['flagged' => 'Reported by owners', 'active' => 'All active', 'removed' => 'Removed'];
$stars = fn (int $n) => str_repeat('&#9733;', $n) . '<span style="color: #d1d5db;">' . str_repeat('&#9733;', 5 - $n) . '</span>';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Review Moderation</span>
        </div>
        <h1 class="page-title">Review Moderation</h1>
        <div class="page-subtitle">Venue reviews. Remove any that break platform policy, or dismiss an owner's report and keep the review.</div>
    </div>
</div>

<div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: var(--space-5);">
    <?php foreach ($tabs as $key => $label): ?>
        <a href="<?= url('/admin/reviews?status=' . $key) ?>" class="btn btn-sm <?= $status === $key ? 'btn-primary' : 'btn-outline' ?>">
            <?= e($label) ?> (<?= e($counts[$key]) ?>)
        </a>
    <?php endforeach; ?>
</div>

<?php if ($reviews === []): ?>
    <div class="card" style="padding: var(--space-8); text-align: center; color: var(--color-text-muted);">
        <?= $status === 'flagged' ? 'No open reports. Owners can report a review from their Reviews page.' : ($status === 'removed' ? 'No removed reviews.' : 'No active venue reviews.') ?>
    </div>
<?php else: ?>
    <div style="display: flex; flex-direction: column; gap: var(--space-4);">
        <?php foreach ($reviews as $r): ?>
            <div class="card" style="border-left: 4px solid <?= $r['flag_id'] !== null ? 'var(--color-warning)' : ($r['status'] === 'removed' ? 'var(--color-danger)' : 'var(--color-border)') ?>;">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3); flex-wrap: wrap; gap: 12px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px; flex-wrap: wrap;">
                                <strong style="font-size: 14px; color: var(--color-text-heading);">Review #<?= e($r['id']) ?> on <?= e($r['venue_name']) ?></strong>
                                <?php if ($r['flag_id'] !== null): ?>
                                    <span class="badge badge-warning">Reported by owner</span>
                                <?php endif; ?>
                                <?php if ($r['status'] === 'removed'): ?>
                                    <span class="badge badge-danger">Removed</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 11px; color: var(--color-text-subtle);">
                                Author: <strong><?= e($r['reviewer_name']) ?></strong> &middot; Booking #<?= e($r['booking_id']) ?>, <?= e($r['court_name']) ?>
                                &middot; Reviewed <?= e(format_datetime($r['created_at'])) ?>
                            </div>
                        </div>

                        <?php if ($r['status'] === 'active'): ?>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <?php if ($r['flag_id'] !== null): ?>
                                    <button type="button" class="btn btn-sm btn-outline" onclick="CourtPassApp.confirmPost('Dismiss Report', 'Keep this review published and close the owner\'s report?', 'Dismiss Report', '/admin/reviews/<?= (int) $r['id'] ?>/dismiss-flag?status=<?= e($status) ?>')">
                                        Dismiss Report
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Remove Review', 'Give the policy reason. It is shown to the reviewer. The review is hidden, not deleted.', '/admin/reviews/<?= (int) $r['id'] ?>/remove?status=<?= e($status) ?>')">
                                    Remove Review
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="background: var(--color-bg); padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); margin-bottom: var(--space-3);">
                        <div style="color: #f59e0b; font-size: 12px; margin-bottom: 4px;"><?= $stars($r['rating']) ?> (<?= e($r['rating']) ?>.0)</div>
                        <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0; line-height: 1.5; white-space: pre-line;"><?= e($r['comment']) ?></p>
                    </div>

                    <?php if ($r['response_text'] !== null): ?>
                        <div style="font-size: 11px; color: var(--color-text-subtle); margin-bottom: 6px;">
                            <strong>Owner response:</strong> <?= e($r['response_text']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($r['flag_id'] !== null): ?>
                        <div style="font-size: 11px; color: var(--color-text-subtle);">
                            <strong>Report from <?= e($r['flagged_by_name']) ?> (<?= e(format_datetime($r['flagged_at'])) ?>):</strong> <?= e($r['flag_reason']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($r['status'] === 'removed'): ?>
                        <div style="font-size: 11px; color: var(--color-text-subtle);">
                            <strong>Removal reason:</strong> <?= e($r['removed_reason'] ?? '') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
