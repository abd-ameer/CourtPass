<header class="site-header">
    <div class="container">
        <a class="brand" href="<?= url('/') ?>"><?= e(APP_NAME) ?></a>
        <nav>
            <?php if (Auth::check()): ?>
                <a href="<?= url(Auth::homeUrl()) ?>">Dashboard</a>
                <a href="<?= url('/logout') ?>">Log out</a>
            <?php else: ?>
                <a href="<?= url('/login') ?>">Log in</a>
                <a href="<?= url('/register') ?>">Sign up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
