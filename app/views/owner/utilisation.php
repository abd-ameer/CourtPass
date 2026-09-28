<?php
/** @var bool $sample true for the seeded demo owner, whose hours below match database/seed.sql */
// Sample used hours matching the seed of the demo owner (last 30 days and the next 6) until utilisation analytics is built.
// Each row: day offset from today, start hour, court, venue, kind (booking, coaching or block).
$A = 'Colombo Sports Hub';
$K = 'Kandy Court Zone';
$used = [
    [-27, 18, 'Badminton Court 2', $A, 'booking'], [-25, 15, 'Table Tennis Room', $A, 'booking'], [-24, 16, 'Table Tennis Room', $A, 'booking'],
    [-22, 10, 'Squash Court 1', $K, 'booking'], [-21, 17, 'Badminton Court 1', $A, 'booking'], [-20, 19, 'Futsal Court A', $A, 'booking'],
    [-19, 20, 'Futsal Court B', $A, 'booking'], [-18, 19, 'Futsal Court A', $A, 'booking'], [-16, 18, 'Carrom Lounge', $K, 'booking'],
    [-15, 18, 'Badminton Court 2', $A, 'booking'], [-14, 10, 'Squash Court 1', $K, 'booking'], [-13, 17, 'Billiards Table 1', $K, 'booking'],
    [-12, 18, 'Badminton Court 1', $A, 'booking'], [-11, 19, 'Futsal Court B', $A, 'booking'], [-3, 17, 'Badminton Court 1', $A, 'booking'],
    [-2, 19, 'Futsal Court A', $A, 'booking'], [1, 18, 'Futsal Court B', $A, 'booking'], [3, 19, 'Futsal Court A', $A, 'booking'],
    [4, 10, 'Squash Court 1', $K, 'booking'],
    [-2, 8, 'Badminton Court 2', $A, 'coaching'], [5, 8, 'Badminton Court 2', $A, 'coaching'], [6, 8, 'Badminton Court 1', $A, 'coaching'],
    [2, 18, 'Futsal Court A', $A, 'block'],
];
$hours = range(6, 22);
$days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];
$byHour = ['booking' => [], 'coaching' => [], 'block' => []];
$heat = [];
$courtHours = [];
foreach ($used as [$offset, $hour, $court, $venue, $kind]) {
    $byHour[$kind][$hour] = ($byHour[$kind][$hour] ?? 0) + 1;
    $day = (int) date('N', strtotime(relative_date($offset)));
    $heat[$day][$hour] = ($heat[$day][$hour] ?? 0) + 1;
    $courtHours[$court . '|' . $venue] = ($courtHours[$court . '|' . $venue] ?? 0) + 1;
}
arsort($courtHours);
$bookings = $byHour['booking'];
$coaching = $byHour['coaching'];
$blocks = $byHour['block'];
$busiest = array_keys($bookings, max($bookings));
$heatMax = max(array_map('max', $heat));
$series = ['labels' => [], 'bookings' => [], 'coaching' => [], 'blocks' => []];
foreach ($hours as $h) {
    $series['labels'][] = sprintf('%02d:00', $h);
    $series['bookings'][] = $bookings[$h] ?? 0;
    $series['coaching'][] = $coaching[$h] ?? 0;
    $series['blocks'][] = $blocks[$h] ?? 0;
}
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

<?php if (!$sample): ?>
<div class="card">
    <div class="empty-state">
        <div class="empty-state-title">No court usage yet</div>
        <div class="empty-state-desc">Once your courts take bookings, coaching sessions or blocks, the heatmap shows your busy and quiet hours.</div>
    </div>
</div>
<?php return; endif; ?>
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
            <div class="stat-value"><?= e(implode(' / ', array_map(fn (int $h) => sprintf('%02d:00', $h), $busiest))) ?></div>
            <div class="stat-label">Busiest Booking Hour</div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <div>
            <h3 class="card-title">Weekly Heatmap</h3>
            <div class="card-subtitle">Used hours by day of the week and hour. Darker cells are busier; pale cells are quiet hours to fill with a flash deal.</div>
        </div>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table style="border-collapse: separate; border-spacing: 3px; font-size: 11px; min-width: 720px;">
            <thead>
                <tr>
                    <th></th>
                    <?php foreach ($hours as $h): ?><th style="font-weight: 600; color: var(--color-text-muted); text-align: center;"><?= sprintf('%02d', $h) ?></th><?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($days as $d => $label): ?>
                    <tr>
                        <th style="text-align: left; padding-right: 6px; color: var(--color-text-muted); font-weight: 600;"><?= e($label) ?></th>
                        <?php foreach ($hours as $h): ?>
                            <?php $n = $heat[$d][$h] ?? 0; $alpha = $n === 0 ? 0.06 : 0.2 + 0.8 * $n / $heatMax; ?>
                            <td title="<?= e($label . ' ' . sprintf('%02d:00', $h) . ': ' . $n . ' used ' . ($n === 1 ? 'hour' : 'hours')) ?>" style="width: 34px; height: 26px; border-radius: 4px; text-align: center; background: rgba(9, 32, 63, <?= $alpha ?>); color: <?= $n > 0 && $alpha > 0.5 ? '#fff' : 'var(--color-text-main)' ?>;"><?= $n > 0 ? $n : '' ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
                <?php foreach ($courtHours as $key => $n): ?>
                    <?php [$court, $venue] = explode('|', $key); ?>
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
