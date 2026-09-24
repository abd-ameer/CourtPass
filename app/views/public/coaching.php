<?php
/** @var array $sessions @var array $sports @var array $venues @var array $filters */
?>
<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Coaching Sessions</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: var(--font-size-2xl); margin-bottom: 6px;">Coaching Sessions</h1>
                <p class="text-sm" style="color: var(--color-text-muted);">
                    One-hour public sessions run by venue-approved coaches on real court slots.
                </p>
            </div>
            <?php if (!Auth::check()): ?>
                <a href="<?= url('/register/coach') ?>" class="btn btn-outline">Join as a Coach</a>
            <?php endif; ?>
        </div>

        <form method="GET" action="<?= url('/coaching') ?>" style="margin-top: var(--space-5); display: flex; flex-wrap: wrap; gap: 12px;">
            <div style="flex: 1; min-width: 180px;">
                <select name="sport" class="form-select" onchange="this.form.submit()">
                    <option value="">All Sports</option>
                    <?php foreach ($sports as $sport): ?>
                        <option value="<?= e($sport['code']) ?>" <?= $filters['sport'] === $sport['code'] ? 'selected' : '' ?>><?= e($sport['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 180px;">
                <select name="venue" class="form-select" onchange="this.form.submit()">
                    <option value="">All Venues</option>
                    <?php foreach ($venues as $venue): ?>
                        <option value="<?= e($venue['id']) ?>" <?= $filters['venue'] === (int) $venue['id'] ? 'selected' : '' ?>><?= e($venue['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex: 1; min-width: 180px;">
                <input type="date" name="date" class="form-control" value="<?= e($filters['date']) ?>" onchange="this.form.submit()">
            </div>
        </form>
    </div>
</div>

<section style="padding: var(--space-8) 0;">
    <div class="container">
        <?php if ($sessions === []): ?>
            <div class="card" style="padding: var(--space-8); text-align: center; color: var(--color-text-muted);">
                No upcoming public sessions match these filters.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-3 gap-6">
                <?php foreach ($sessions as $session): ?>
                    <?php View::partial('session-card', ['session' => $session]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
