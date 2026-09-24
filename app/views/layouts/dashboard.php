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
<body>
    <div class="dashboard-layout">
        <?php View::partial('sidebar') ?>

        <div class="dashboard-main">
            <?php View::partial('dashboard-header') ?>

            <div class="dashboard-content">
                <?php $flashes = Session::takeFlash(); ?>
                <?php if ($flashes !== []): ?>
                    <div class="flash-container flash-container-dashboard">
                        <?php foreach ($flashes as $flash): ?>
                            <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

    <?php View::partial('modals') ?>
</body>
</html>
