<?php
/** @var array $reviews from CoachService::reviews() */
$average = array_sum(array_column($reviews, 'rating')) / max(1, count($reviews));
$stars = fn (int $n) => str_repeat('★', $n) . str_repeat('☆', 5 - $n);
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Reviews</span>
        </div>
        <h1 class="page-title">Reviews & Replies</h1>
        <div class="page-subtitle">Reviews from students marked Attended, with one public reply per review.</div>
    </div>
    <a href="<?= url('/coaches/' . Auth::id()) ?>" target="_blank" class="btn btn-outline">View Public Coach Profile</a>
</div>

<?php if ($reviews === []): ?>
    <div class="card"><div class="empty-state">
        <div class="empty-state-title">No reviews yet</div>
        <div class="empty-state-desc">Students you mark Attended can review the session within 7 days.</div>
    </div></div>
<?php else: ?>
<div class="card" style="margin-bottom: var(--space-6); padding: var(--space-6); display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
    <div style="text-align: center;">
        <div style="font-size: 44px; font-weight: 900; line-height: 1;"><?= e(number_format($average, 1)) ?></div>
        <div style="color: #f59e0b; font-size: 18px; margin: 6px 0;"><?= $stars((int) round($average)) ?></div>
    </div>
    <div class="text-sm">Based on <strong><?= count($reviews) ?> verified student <?= count($reviews) === 1 ? 'review' : 'reviews' ?></strong>.</div>
</div>

<div style="display: flex; flex-direction: column; gap: var(--space-4);">
    <?php foreach ($reviews as $r): ?>
        <div class="card" style="padding: var(--space-5);">
            <div style="display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 6px;">
                <div>
                    <strong><?= e($r['reviewer_name']) ?></strong>
                    <span class="badge badge-verified" style="font-size: 10px; margin-left: 4px;">Attended</span>
                    <div class="text-xs text-muted"><?= e($r['session_title']) ?> · <?= e(format_datetime($r['session_starts_at'])) ?></div>
                </div>
                <div style="color: #f59e0b;"><?= $stars($r['rating']) ?> <span class="text-sm" style="color: var(--color-text-main);"><?= e($r['rating']) ?>.0</span></div>
            </div>
            <p class="text-sm" style="margin-bottom: 12px;"><?= e($r['comment']) ?></p>
            <?php if ($r['response_text'] !== null): ?>
                <div style="padding: 10px 14px; background: var(--color-bg-subtle); border-radius: var(--radius-md);" class="text-sm"><strong>Your reply:</strong> <?= e($r['response_text']) ?></div>
            <?php else: ?>
                <form method="POST" action="<?= url('/coach/reviews/' . $r['id'] . '/response') ?>">
                    <?= csrf_field() ?>
                    <label class="form-label" for="reply<?= (int) $r['id'] ?>">Your public reply</label>
                    <textarea name="response_text" id="reply<?= (int) $r['id'] ?>" class="form-control" rows="2" maxlength="1000" required placeholder="Thank the student or answer their feedback."></textarea>
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-top: 8px;">Post Reply</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
