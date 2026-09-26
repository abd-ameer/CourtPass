<?php
/** @var array $venue @var array $courts @var array $announcements @var array $reviews */
?>
<section style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/venues') ?>">Venues</a>
            <span class="breadcrumb-separator">/</span>
            <span><?= e($venue['name']) ?></span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap; margin-top: var(--space-4);">
            <div>
                <h1 style="font-size: var(--font-size-2xl); margin-bottom: 8px;"><?= e($venue['name']) ?></h1>
                <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 8px;">
                    <?= e($venue['address']) ?> &middot; <?= e($venue['contact_phone']) ?>
                </p>
                <div style="display: flex; align-items: center; gap: 12px; font-size: 13px; flex-wrap: wrap;">
                    <span style="font-weight: 700; color: #d97706;">
                        <?= $venue['avg_rating'] !== null ? e(number_format((float) $venue['avg_rating'], 1)) . ' (' . e($venue['review_count']) . ($venue['review_count'] === 1 ? ' review)' : ' reviews)') : 'No reviews yet' ?>
                    </span>
                    <span style="color: var(--color-border-strong);">|</span>
                    <?php foreach ($venue['sports'] as $sport): ?>
                        <span class="badge badge-sport"><?= e($sport) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if ($courts !== []): ?>
                <a href="<?= url('/courts/' . (int) $courts[0]['id']) ?>" class="btn btn-primary">Check Slot Availability</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section style="padding: var(--space-8) 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;" class="venue-detail-layout">
            <div>
                <div class="card" style="margin-bottom: var(--space-6); overflow: hidden;">
                    <img src="<?= asset('img/venue-placeholder.svg') ?>" alt="<?= e($venue['name']) ?>" style="width: 100%; height: 300px; object-fit: cover;">
                    <?php if (($venue['description'] ?? '') !== ''): ?>
                        <div class="card-body">
                            <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 0;"><?= e($venue['description']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($announcements !== []): ?>
                    <div class="card" style="margin-bottom: var(--space-6); border-left: 4px solid var(--color-primary);">
                        <div class="card-header" style="background: var(--color-bg-subtle);">
                            <h3 style="font-size: 15px; margin-bottom: 0;">Announcements</h3>
                        </div>
                        <div class="card-body" style="display: flex; flex-direction: column; gap: 14px;">
                            <?php foreach ($announcements as $a): ?>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <strong style="color: var(--color-text-title);"><?= e($a['title']) ?></strong>
                                        <span class="badge badge-secondary"><?= e(ucfirst($a['type'])) ?></span>
                                    </div>
                                    <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;"><?= e($a['body']) ?></p>
                                    <div class="text-xs" style="color: var(--color-text-muted);">Posted <?= e(format_datetime($a['posted_at'], false)) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card" style="margin-bottom: var(--space-6);">
                    <div class="card-header">
                        <h3 style="font-size: 17px; margin-bottom: 0;">Courts at this Venue</h3>
                        <span class="text-sm" style="color: var(--color-text-muted);"><?= e(count($courts)) ?> court<?= count($courts) === 1 ? '' : 's' ?></span>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <?php if ($courts === []): ?>
                            <p class="text-sm" style="padding: 16px 20px; margin-bottom: 0; color: var(--color-text-muted);">Courts are being set up. Check back soon.</p>
                        <?php endif; ?>
                        <?php foreach ($courts as $court): ?>
                            <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <h4 style="font-size: 16px; margin-bottom: 0;"><?= e($court['name']) ?></h4>
                                        <span class="badge badge-sport"><?= e($court['sport']) ?></span>
                                        <?php if (!empty($court['has_flash'])): ?>
                                            <span class="badge badge-flash">Flash Deal</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="text-align: right;">
                                        <div style="font-size: 17px; font-weight: 800; color: var(--color-text-title);"><?= e(lkr($court['hourly_rate'])) ?></div>
                                        <div class="text-xs" style="color: var(--color-text-muted);">per 1-hour slot</div>
                                    </div>
                                    <a href="<?= url('/courts/' . (int) $court['id']) ?>" class="btn btn-sm btn-primary">Check Availability</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card" id="reviews">
                    <div class="card-header">
                        <h3 style="font-size: 17px; margin-bottom: 0;">Reviews</h3>
                        <span class="badge badge-verified">Check-in verified only</span>
                    </div>
                    <div class="card-body">
                        <?php if ($reviews === []): ?>
                            <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 0;">No reviews yet.</p>
                        <?php else: ?>
                            <?php foreach ($reviews as $i => $review): ?>
                                <div style="<?= $i + 1 < count($reviews) ? 'padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--color-border);' : '' ?>">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                        <strong><?= e($review['author']) ?></strong>
                                        <span style="color: #d97706; font-weight: 700; font-size: 13px;"><?= e(number_format((float) $review['rating'], 1)) ?></span>
                                    </div>
                                    <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 8px;"><?= e($review['comment']) ?></p>
                                    <div class="text-xs" style="color: var(--color-text-muted);">Reviewed <?= e(format_datetime($review['created_at'], false)) ?></div>
                                    <?php if (!empty($review['response'])): ?>
                                        <div style="background: var(--color-bg-subtle); border-left: 3px solid var(--color-primary); padding: 10px 14px; border-radius: var(--radius-md); margin-top: 10px;">
                                            <div style="font-size: 12px; font-weight: 700; color: var(--color-text-title); margin-bottom: 2px;">Owner response</div>
                                            <div style="font-size: 12px; color: var(--color-text-muted);"><?= e($review['response']) ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <div class="card" style="margin-bottom: var(--space-6); background: var(--color-bg-subtle);">
                    <div class="card-body">
                        <h3 style="font-size: 15px; margin-bottom: 10px;">Ready to play?</h3>
                        <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 14px;">
                            <?= $courts === [] ? 'Courts at this venue are being set up.' : 'Pick a court to see its one-hour slots for any day.' ?>
                        </p>
                        <?php if (!Auth::check()): ?>
                            <a href="<?= url('/login') ?>" class="btn btn-primary btn-block btn-sm">Log In to Book</a>
                            <div class="text-xs text-muted" style="margin-top: 8px; text-align: center;">
                                New here? <a href="<?= url('/register/customer') ?>">Create a customer account</a>
                            </div>
                        <?php elseif ($courts !== []): ?>
                            <a href="<?= url('/courts/' . (int) $courts[0]['id']) ?>" class="btn btn-primary btn-block btn-sm">Check Availability</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h3 style="font-size: 15px; margin-bottom: 10px;">Sports here</h3>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <?php foreach ($venue['sports'] as $sport): ?>
                                <span class="badge badge-sport"><?= e($sport) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
