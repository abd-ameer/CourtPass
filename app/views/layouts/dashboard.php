<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= e(Request::basePath()) ?>">
    <meta name="csrf-token" content="<?= e(Session::csrfToken()) ?>">
    <meta name="description" content="CourtPass - Sri Lanka's premier sports venue booking and management platform.">
    <title><?= e(($title ?? '') ? $title . ' | ' . APP_NAME : APP_NAME . ' | Dashboard') ?></title>
    
    <!-- Google Fonts: Figtree -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

    <!-- Chart.js for Dashboards & Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <!-- Core Scripts -->
    <script src="<?= asset('js/mock-data.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script src="<?= asset('js/availability.js') ?>"></script>
    <script src="<?= asset('js/charts.js') ?>"></script>
</head>
<body>
    <div class="dashboard-layout">
        <?php View::partial('sidebar', ['active_role' => $active_role ?? Auth::role() ?? 'customer']) ?>

        <div class="dashboard-main">
            <?php View::partial('dashboard-header', ['active_role' => $active_role ?? Auth::role() ?? 'customer']) ?>

            <div class="dashboard-content">
                <?php $flashes = Session::takeFlash(); ?>
                <?php if (!empty($flashes)): ?>
                    <div class="flash-container" style="max-width: 100%; padding: 0 0 16px 0;">
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
