<?php
$currentUri = Request::current()?->path() ?? '/';
?>
<header style="background: var(--color-white); border-bottom: 1px solid var(--color-border); position: sticky; top: 0; z-index: var(--z-nav); box-shadow: var(--shadow-sm);">
    <div class="container" style="height: var(--header-height); display: flex; align-items: center; justify-content: space-between;">
        
        <!-- Brand Logo -->
        <a href="<?= url('/') ?>" style="display: flex; align-items: center; text-decoration: none;">
            <img src="<?= asset('images/courtpasslogo.png') ?>" alt="<?= e(APP_NAME) ?>" class="brand-logo-img" style="height: 40px; max-width: 170px; object-fit: contain;">
        </a>

        <!-- Navigation Links -->
        <nav style="display: flex; align-items: center; gap: 28px;" class="desktop-nav">
            <a href="<?= url('/venues') ?>" style="font-weight: 600; font-size: 14px; color: <?= str_starts_with($currentUri, '/venues') ? 'var(--color-primary-hover)' : 'var(--color-text-main)' ?>;">
                Browse Venues
            </a>
            <a href="<?= url('/coaching') ?>" style="font-weight: 600; font-size: 14px; color: <?= str_starts_with($currentUri, '/coaching') ? 'var(--color-primary-hover)' : 'var(--color-text-main)' ?>;">
                Coaching Sessions
            </a>
            <a href="<?= url('/#how-it-works') ?>" style="font-weight: 600; font-size: 14px; color: var(--color-text-muted);">
                How It Works
            </a>
        </nav>

        <!-- Header Actions -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <?php if (Auth::check()): ?>
                <a href="<?= url(Auth::homeUrl()) ?>" class="btn btn-sm btn-primary">
                    Dashboard (<?= e(ucfirst(Auth::role() ?? '')) ?>)
                </a>
                <a href="<?= url('/logout') ?>" class="btn btn-sm btn-outline">
                    Log Out
                </a>
            <?php else: ?>
                <a href="<?= url('/login') ?>" class="btn btn-sm btn-outline">
                    Log In
                </a>
                <a href="<?= url('/register') ?>" class="btn btn-sm btn-primary">
                    Sign Up
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>
