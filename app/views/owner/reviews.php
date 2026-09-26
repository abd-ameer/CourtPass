<?php
/** @var array $reviews @var array $venues @var int|null $venue_id */
$stars = fn (int $n) => str_repeat('&#9733;', $n) . '<span style="color: #d1d5db;">' . str_repeat('&#9733;', 5 - $n) . '</span>';
$query = $venue_id === null ? '' : '?venue=' . $venue_id;
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Reviews</span>
        </div>
        <h1 class="page-title">Customer Reviews</h1>
        <div class="page-subtitle">Verified reviews from checked-in customers. Post one public response per review, or report a review that breaks platform policy.</div>
    </div>
</div>

<?php if ($venues !== []): ?>
    <div class="grid grid-cols-3 gap-6" style="margin-bottom: var(--space-6);">
        <?php foreach ($venues as $v): ?>
            <div class="card" style="padding: 16px 20px;">
                <div class="text-xs text-muted"><?= e($v['name']) ?></div>
                <div style="font-size: 20px; font-weight: 800; color: #d97706;">
                    <?= $v['avg_rating'] === null ? 'No reviews yet' : e(number_format($v['avg_rating'], 1)) . ' / 5' ?>
                </div>
                <div class="text-xs text-muted"><?= e($v['review_count']) ?> active review<?= $v['review_count'] === 1 ? '' : 's' ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="card" style="padding: 16px 20px; margin-bottom: var(--space-6);">
        <form method="GET" action="<?= url('/owner/reviews') ?>" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <select name="venue" class="form-select" style="max-width: 320px;" onchange="this.form.submit()">
                <option value="">All venues</option>
                <?php foreach ($venues as $v): ?>
                    <option value="<?= e($v['id']) ?>" <?= $venue_id === $v['id'] ? 'selected' : '' ?>><?= e($v['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
        </form>
    </div>
<?php endif; ?>

<?php if ($reviews === []): ?>
    <div class="card" style="padding: var(--space-8); text-align: center; color: var(--color-text-muted);">
        No reviews yet. Customers can review a booking for 7 days after you check them in.
    </div>
<?php else: ?>
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <?php foreach ($reviews as $r): ?>
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <strong style="font-size: 15px;"><?= e($r['reviewer_name']) ?></strong>
                        <span class="text-xs text-muted"><?= e($r['venue_name']) ?> &middot; <?= e($r['court_name']) ?> &middot; <?= e(format_datetime($r['visit'], false)) ?></span>
                        <?php if ($r['status'] === 'removed'): ?>
                            <span class="badge badge-danger">Removed by the Platform Admin</span>
                        <?php elseif ($r['flag_id'] !== null): ?>
                            <span class="badge badge-warning">Reported, waiting for the Platform Admin</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($r['can_flag']): ?>
                        <button type="button" class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Report Review', 'Tell the Platform Admin which policy this review breaks. The customer is not told about the report.', '/owner/reviews/<?= (int) $r['id'] ?>/flag<?= e($query) ?>')">
                            Report
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="color: #d97706; font-weight: 700; font-size: 14px;"><?= $stars($r['rating']) ?> <?= e($r['rating']) ?>.0</span>
                        <span class="text-xs text-muted">Reviewed <?= e(format_datetime($r['created_at'], false)) ?></span>
                    </div>
                    <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 12px; white-space: pre-line;"><?= e($r['comment']) ?></p>

                    <?php if ($r['response_text'] !== null): ?>
                        <div style="background: var(--color-bg-subtle); border-left: 3px solid var(--color-primary); padding: 12px 16px; border-radius: var(--radius-md);">
                            <div style="font-size: 12px; font-weight: 700; color: var(--color-text-title); margin-bottom: 2px;">Your response (<?= e(format_datetime($r['responded_at'], false)) ?>)</div>
                            <div style="font-size: 12px; color: var(--color-text-muted); white-space: pre-line;"><?= e($r['response_text']) ?></div>
                        </div>
                    <?php elseif ($r['can_respond']): ?>
                        <form method="POST" action="<?= url('/owner/reviews/' . $r['id'] . '/response' . $query) ?>" style="display: flex; flex-direction: column; gap: 8px;">
                            <?= csrf_field() ?>
                            <label class="form-label" for="response-<?= (int) $r['id'] ?>" style="margin-bottom: 0;">Public response (one per review)</label>
                            <textarea name="response" id="response-<?= (int) $r['id'] ?>" class="form-control" rows="2" maxlength="1000" required></textarea>
                            <div style="display: flex; justify-content: flex-end;">
                                <button type="submit" class="btn btn-sm btn-primary">Post Response</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
