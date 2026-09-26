<?php
/**
 * Public coaching session card.
 * $session: id, title, sport, venue_name, court_name, coach_id, coach_name, coach_verified,
 *           coach_rating (null when no reviews), starts_at, fee, capacity, registered_count, status
 */
$spotsLeft = max(0, (int) $session['capacity'] - (int) $session['registered_count']);
$isFull = $session['status'] === 'full' || $spotsLeft === 0;
?>
<div class="card card-hover" style="border-top: 4px solid var(--color-primary); display: flex; flex-direction: column;">
    <div class="card-body flex flex-col flex-1">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span class="badge badge-sport"><?= e($session['sport']) ?></span>
            <?php if ($isFull): ?>
                <?= status_badge('full') ?>
            <?php else: ?>
                <span class="badge badge-available"><?= e($spotsLeft) ?> of <?= e($session['capacity']) ?> places left</span>
            <?php endif; ?>
        </div>
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">
            <a href="<?= url('/sessions/' . (int) $session['id']) ?>" style="color: var(--color-navy); text-decoration: none;"><?= e($session['title']) ?></a>
        </h3>
        <p class="text-sm" style="margin-bottom: 14px; color: var(--color-text-muted);">
            <?= e($session['venue_name']) ?> &middot; <?= e($session['court_name']) ?>
        </p>

        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding: 10px; background: var(--color-bg-subtle); border-radius: var(--radius-md); border: 1px solid var(--color-border);">
            <div>
                <div style="font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                    <a href="<?= url('/coaches/' . (int) $session['coach_id']) ?>" style="color: var(--color-navy);"><?= e($session['coach_name']) ?></a>
                    <?php if (!empty($session['coach_verified'])): ?>
                        <span class="badge badge-verified" style="font-size: 10px; padding: 1px 6px;">Verified</span>
                    <?php endif; ?>
                </div>
                <div style="font-size: 11px; color: var(--color-text-muted);">
                    <?= $session['coach_rating'] !== null ? 'Rating ' . e(number_format((float) $session['coach_rating'], 1)) : 'No reviews yet' ?>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <div>
                <div style="font-size: 11px; color: var(--color-text-muted);">Date &amp; Time (1 hour)</div>
                <div style="font-size: 13px; font-weight: 700;"><?= e(format_datetime($session['starts_at'])) ?></div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; color: var(--color-text-muted);">Fee</div>
                <div style="font-size: 15px; font-weight: 800; color: var(--color-primary-active);"><?= (float) $session['fee'] > 0 ? e(lkr($session['fee'])) : 'Free' ?></div>
            </div>
        </div>

        <div style="margin-top: 16px;">
            <?php if ($isFull): ?>
                <button type="button" class="btn btn-secondary btn-block" disabled>Session Full</button>
            <?php else: ?>
                <a href="<?= url('/sessions/' . (int) $session['id']) ?>" class="btn btn-primary btn-block">View &amp; Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>
