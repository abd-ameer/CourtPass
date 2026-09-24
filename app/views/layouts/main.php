<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= e(Request::basePath()) ?>">
    <meta name="csrf-token" content="<?= e(Session::csrfToken()) ?>">
    <title><?= e(($title ?? '') !== '' ? $title . ' | ' . APP_NAME : APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <script src="<?= asset('js/app.js') ?>"></script>
</head>
<body class="layout-main">
    <?php View::partial('header') ?>

    <?php $flashes = Session::takeFlash(); ?>
    <?php if ($flashes !== []): ?>
        <div class="flash-container">
            <?php foreach ($flashes as $flash): ?>
                <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <main class="layout-main-content">
        <?= $content ?>
    </main>

    <?php View::partial('modals') ?>
    <?php View::partial('footer') ?>
</body>
</html>
