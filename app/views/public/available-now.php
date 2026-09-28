<?php
/** @var array $deals from DiscoveryService::availableNow(), soonest first */
$now = time();
$startsIn = function (string $startsAt) use ($now): string {
    $minutes = max(0, (int) floor((strtotime($startsAt) - $now) / 60));
    if ($minutes < 60) {
        return 'Starts in ' . $minutes . ' min';
    }
    $hours = intdiv($minutes, 60);
    return $hours < 24 ? 'Starts in ' . $hours . ' hr' . ($hours === 1 ? '' : 's') : 'Starts ' . format_datetime($startsAt, false);
};
?>
<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Available Now</span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <h1 style="font-size: var(--font-size-2xl); margin-bottom: 6px;">Available Now</h1>
            <span class="badge badge-flash" style="font-size: 12px;">Flash Deals</span>
        </div>
        <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 0;">
            Last-minute court slots at a discount, posted by the venues. Each deal ends when its slot starts.
        </p>
    </div>
</div>

<section style="padding: var(--space-8) 0;">
    <div class="container">
        <?php if ($deals === []): ?>
            <div class="card">
                <div class="empty-state">
                    <div class="empty-state-title">No flash deals right now</div>
                    <div class="empty-state-desc">Venues post discounted slots here when they have quiet hours. Check back later or browse all venues.</div>
                    <a href="<?= url('/venues') ?>" class="btn btn-primary" style="margin-top: 12px;">Browse Venues</a>
                </div>
            </div>
        <?php else: ?>
            <div style="font-size: 14px; font-weight: 600; color: var(--color-text-title); margin-bottom: var(--space-4);">
                <?= count($deals) ?> <?= count($deals) === 1 ? 'deal' : 'deals' ?> available
            </div>
            <div class="grid grid-cols-3 gap-6">
                <?php foreach ($deals as $f): ?>
                    <div class="card" style="display: flex; flex-direction: column;">
                        <div class="card-body" style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span class="badge badge-confirmed"><?= e($f['sport_name']) ?></span>
                                <span class="badge badge-flash"><?= (int) $f['percent_off'] ?>% OFF</span>
                            </div>
                            <h3 style="font-size: 17px; margin-bottom: 2px;"><?= e($f['court_name']) ?></h3>
                            <a href="<?= url('/venue/' . $f['venue_slug']) ?>" class="text-sm" style="font-weight: 600;"><?= e($f['venue_name']) ?></a>
                            <span class="text-xs text-muted"> · <?= e($f['venue_city']) ?></span>
                            <div style="margin: 14px 0 10px; padding: 10px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);">
                                <div class="text-sm" style="font-weight: 700;"><?= e(format_datetime($f['slot_date'], false)) ?> · <?= e($f['start']) ?> - <?= e($f['end']) ?></div>
                                <div class="text-xs" style="color: #ea580c; font-weight: 600; margin-top: 2px;"><?= e($startsIn($f['starts_at'])) ?></div>
                            </div>
                            <div style="display: flex; align-items: baseline; gap: 8px;">
                                <strong style="font-size: 22px; color: #ea580c;"><?= e(lkr($f['discounted_price'])) ?></strong>
                                <span class="text-sm text-muted" style="text-decoration: line-through;"><?= e(lkr($f['original_price'])) ?></span>
                                <span class="text-xs text-muted">/ 1 hour</span>
                            </div>
                        </div>
                        <div style="padding: 0 var(--space-5) var(--space-5); display: flex; gap: 8px;">
                            <a href="<?= url('/venue/' . $f['venue_slug']) ?>" class="btn btn-outline btn-sm" style="flex: 1;">View Venue</a>
                            <a href="<?= url('/courts/' . $f['court_id']) ?>" class="btn btn-primary btn-sm" style="flex: 1;">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
