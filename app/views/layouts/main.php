<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= e(Request::basePath()) ?>">
    <meta name="csrf-token" content="<?= e(Session::csrfToken()) ?>">
    <meta name="description" content="CourtPass - Sri Lanka's premier sports venue booking, coach session and community platform for badminton, futsal, pickleball, squash, billiards, carrom and table tennis.">
    <title><?= e(($title ?? '') ? $title . ' | ' . APP_NAME : APP_NAME . ' | Sports Venue Booking & Community Platform') ?></title>
    
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
<body style="min-height: 100vh; display: flex; flex-direction: column;">
    <?php View::partial('header') ?>

    <?php $flashes = Session::takeFlash(); ?>
    <?php if (!empty($flashes)): ?>
        <div class="flash-container">
            <?php foreach ($flashes as $flash): ?>
                <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <main style="flex: 1 0 auto;">
        <?= $content ?>
    </main>

    <?php View::partial('modals') ?>
    <?php View::partial('footer') ?>
</body>
</html>
