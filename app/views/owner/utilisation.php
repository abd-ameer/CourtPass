<?php
// Sample figures matching the seed of the demo owner (last 30 days and the next 6) until utilisation analytics is built.
$hours = range(6, 22);
$bookings = [10 => 2, 15 => 1, 16 => 1, 17 => 3, 18 => 4, 19 => 5, 20 => 2];
$coaching = [8 => 3];
$blocks = [18 => 1];
$series = ['labels' => [], 'bookings' => [], 'coaching' => [], 'blocks' => []];
foreach ($hours as $h) {
    $series['labels'][] = sprintf('%02d:00', $h);
    $series['bookings'][] = $bookings[$h] ?? 0;
    $series['coaching'][] = $coaching[$h] ?? 0;
    $series['blocks'][] = $blocks[$h] ?? 0;
}
$courtHours = [
    ['Futsal Court A', 'Colombo Sports Hub', 5], ['Badminton Court 1', 'Colombo Sports Hub', 5], ['Badminton Court 2', 'Colombo Sports Hub', 4],
    ['Futsal Court B', 'Colombo Sports Hub', 2], ['Table Tennis Room', 'Colombo Sports Hub', 2], ['Squash Court 1', 'Kandy Court Zone', 2],
    ['Billiards Table 1', 'Kandy Court Zone', 1], ['Carrom Lounge', 'Kandy Court Zone', 1],
];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Court Utilisation</span>
        </div>
        <h1 class="page-title">Court Utilisation & Heatmap</h1>
        <div class="page-subtitle">Used court hours by hour of the day across your venues, over the last 30 days and the next 6.</div>
    </div>
    <a href="<?= url('/owner/flash-slots') ?>" class="btn btn-primary">
        Create a Flash Deal for a Quiet Hour
    </a>
</div>

<div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
    <div class="stat-card">
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
        <div>
            <div class="stat-value"><?= array_sum($bookings) ?> hrs</div>
            <div class="stat-label">Customer Bookings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
        <div>
            <div class="stat-value"><?= array_sum($coaching) ?> hrs</div>
            <div class="stat-label">Coaching Sessions</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg></div>
        <div>
            <div class="stat-value"><?= array_sum($blocks) ?> hr</div>
            <div class="stat-label">Owner Blocks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
        <div>
            <div class="stat-value">19:00</div>
            <div class="stat-label">Busiest Booking Hour</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <div>
            <h3 class="card-title">Used Hours by Hour of the Day</h3>
            <div class="card-subtitle">Customer bookings, coaching sessions and owner blocks, all courts combined.</div>
        </div>
    </div>
    <div class="card-body">
        <div style="height: 320px; position: relative;">
            <canvas id="ownerUtilisationChart"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Used Hours by Court</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr><th>Court</th><th>Venue</th><th>Used Hours</th></tr>
            </thead>
            <tbody>
                <?php foreach ($courtHours as [$court, $venue, $n]): ?>
                    <tr><td><strong><?= e($court) ?></strong></td><td><?= e($venue) ?></td><td><?= (int) $n ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?= asset('vendor/chartjs/chart.umd.min.js') ?>"></script>
<script src="<?= asset('js/charts.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof CourtPassCharts !== 'undefined') {
        CourtPassCharts.initUtilisationChart('ownerUtilisationChart', <?= json_encode($series) ?>);
    }
});
</script>
