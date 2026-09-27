<?php
// Sample figures matching the seed bookings of the demo owner (last 30 days) until revenue analytics is built.
$online = 9000;
$cash = 33300;
$byCourt = [
    ['Futsal Court A', 'Colombo Sports Hub', 'Futsal', 2, 10000],
    ['Futsal Court B', 'Colombo Sports Hub', 'Futsal', 2, 10000],
    ['Badminton Court 1', 'Colombo Sports Hub', 'Badminton', 4, 8000],
    ['Squash Court 1', 'Kandy Court Zone', 'Squash', 2, 6000],
    ['Badminton Court 2', 'Colombo Sports Hub', 'Badminton', 2, 4000],
    ['Table Tennis Room', 'Colombo Sports Hub', 'Table Tennis', 2, 2000],
    ['Billiards Table 1', 'Kandy Court Zone', 'Billiards', 1, 1500],
    ['Carrom Lounge', 'Kandy Court Zone', 'Carrom', 1, 800],
];
$bySport = [];
foreach ($byCourt as [, , $sport, $n, $amount]) {
    $bySport[$sport] = ($bySport[$sport] ?? 0) + $amount;
}
arsort($bySport);
$total = $online + $cash;
$bookings = array_sum(array_column($byCourt, 3));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Revenue Analytics</span>
        </div>
        <h1 class="page-title">Revenue Analytics</h1>
        <div class="page-subtitle">Court booking revenue for the last 30 days, split by online payment and Cash on Arrival. Coaching fees are not included.</div>
    </div>
</div>

<div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
    <div class="stat-card">
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
        <div>
            <div class="stat-value"><?= e(lkr($total)) ?></div>
            <div class="stat-label">Total Revenue (30 Days)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
        <div>
            <div class="stat-value"><?= e(lkr($online)) ?></div>
            <div class="stat-label">Online Payments (<?= e(round($online / $total * 100)) ?>%)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></div>
        <div>
            <div class="stat-value"><?= e(lkr($cash)) ?></div>
            <div class="stat-label">Cash on Arrival (<?= e(round($cash / $total * 100)) ?>%)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
        <div>
            <div class="stat-value"><?= (int) $bookings ?></div>
            <div class="stat-label">Paid or Played Bookings</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue by Court</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr><th>Court</th><th>Venue</th><th>Bookings</th><th>Revenue</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($byCourt as [$court, $venue, $sport, $n, $amount]): ?>
                        <tr>
                            <td><strong><?= e($court) ?></strong><div class="text-xs text-muted"><?= e($sport) ?></div></td>
                            <td><?= e($venue) ?></td>
                            <td><?= (int) $n ?></td>
                            <td><strong><?= e(lkr($amount)) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue by Sport</h3>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 12px;">
            <?php foreach ($bySport as $sport => $amount): ?>
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                        <strong><?= e($sport) ?></strong>
                        <span><?= e(lkr($amount)) ?> (<?= e(round($amount / $total * 100)) ?>%)</span>
                    </div>
                    <div style="height: 8px; background: var(--color-bg-subtle); border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: <?= e(round($amount / $total * 100)) ?>%; background: var(--color-primary);"></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <p class="text-xs text-muted" style="margin: 8px 0 0;">Online revenue counts verified PayHere payments. Cash on Arrival counts bookings the venue checked in.</p>
        </div>
    </div>
</div>
