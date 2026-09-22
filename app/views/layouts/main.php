<?php /** Main layout (shared UI layout is owned by Member D). */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= e(Request::basePath()) ?>">
    <meta name="csrf-token" content="<?= e(Session::csrfToken()) ?>">
    <title><?= e(($title ?? '') ? $title . ' | ' . APP_NAME : APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body>
    <?php View::partial('header') ?>

    <main>
        <div class="container">
            <?php foreach (Session::takeFlash() as $flash): ?>
                <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endforeach; ?>

            <?= $content ?>
        </div>
    </main>

    <?php View::partial('footer') ?>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
