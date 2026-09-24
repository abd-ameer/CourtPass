<?php
/** @var array $venues @var array $sports @var array $cities @var array $filters */
?>
<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Venues</span>
        </div>
        <h1 style="font-size: var(--font-size-2xl); margin-bottom: 6px;">Browse Sports Venues &amp; Courts</h1>
        <p class="text-sm" style="color: var(--color-text-muted);">
            Approved indoor venues. Check live slot availability and book directly.
        </p>

        <form method="GET" action="<?= url('/venues') ?>" style="margin-top: var(--space-5); display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            <div style="flex: 2; min-width: 240px;">
                <input type="search" name="q" class="form-control" value="<?= e($filters['q']) ?>" placeholder="Search by venue name or city" maxlength="100">
            </div>
            <div style="flex: 1; min-width: 160px;">
                <select name="sport" class="form-select" onchange="this.form.submit()">
                    <option value="">All Sports</option>
                    <?php foreach ($sports as $sport): ?>
                        <option value="<?= e($sport['code']) ?>" <?= $filters['sport'] === $sport['code'] ? 'selected' : '' ?>><?= e($sport['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 160px;">
                <select name="city" class="form-select" onchange="this.form.submit()">
                    <option value="">All Cities</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= e($city) ?>" <?= $filters['city'] === $city ? 'selected' : '' ?>><?= e($city) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<section style="padding: var(--space-8) 0;">
    <div class="container">
        <div style="font-size: 14px; font-weight: 600; color: var(--color-text-title); margin-bottom: var(--space-4);">
            Showing <?= e(count($venues)) ?> venue<?= count($venues) === 1 ? '' : 's' ?>
        </div>

        <?php if ($venues === []): ?>
            <div class="card" style="padding: var(--space-8); text-align: center; color: var(--color-text-muted);">
                No venues match these filters.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-3 gap-6">
                <?php foreach ($venues as $venue): ?>
                    <?php View::partial('venue-card', ['venue' => $venue]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
