<?php
/**
 * Venue card for listings.
 * $venue: id, slug, name, city, address, sports (names), court_count, min_rate (null when no courts yet),
 *         avg_rating (null when no reviews), review_count, has_flash, first_court_id (null when no courts yet)
 */
?>
<div class="card card-hover venue-card">
    <div class="venue-card-img-wrapper">
        <img src="<?= asset('img/venue-placeholder.svg') ?>" alt="<?= e($venue['name']) ?>" class="venue-card-img">
        <?php if (!empty($venue['has_flash'])): ?>
            <div class="venue-badge-overlay">
                <span class="badge badge-flash">Flash Deals</span>
            </div>
        <?php endif; ?>
        <div class="venue-price-badge"><?= $venue['min_rate'] === null ? 'No courts yet' : 'From ' . e(lkr($venue['min_rate'])) . ' / hr' ?></div>
    </div>
    <div class="card-body flex flex-col flex-1">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; gap: 8px;">
            <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                <?php foreach ($venue['sports'] as $sport): ?>
                    <span class="badge badge-sport"><?= e($sport) ?></span>
                <?php endforeach; ?>
            </div>
            <span style="font-size: 13px; font-weight: 700; color: #d97706; white-space: nowrap;">
                <?= $venue['avg_rating'] !== null ? e(number_format((float) $venue['avg_rating'], 1)) . ' (' . e($venue['review_count']) . ')' : 'No reviews yet' ?>
            </span>
        </div>
        <h3 style="font-size: 17px; margin-bottom: 4px; font-weight: 700;">
            <a href="<?= url('/venue/' . $venue['slug']) ?>" style="color: var(--color-navy); text-decoration: none;"><?= e($venue['name']) ?></a>
        </h3>
        <p class="text-sm" style="margin-bottom: 16px; color: var(--color-text-muted);">
            <?= e($venue['address']) ?> &middot; <?= (int) $venue['court_count'] === 0 ? 'Courts coming soon' : e($venue['court_count']) . ((int) $venue['court_count'] === 1 ? ' Court' : ' Courts') ?>
        </p>
        <div style="margin-top: auto; display: flex; gap: 8px;">
            <a href="<?= url('/venue/' . $venue['slug']) ?>" class="btn btn-outline btn-sm flex-1">View Venue</a>
            <?php if ($venue['first_court_id'] !== null): ?>
                <a href="<?= url('/courts/' . (int) $venue['first_court_id']) ?>" class="btn btn-primary btn-sm flex-1">Check Slots</a>
            <?php endif; ?>
        </div>
    </div>
</div>
