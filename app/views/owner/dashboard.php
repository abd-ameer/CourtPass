<?php
/** @var string $name @var array $venues @var array $pending @var int $confirmed @var array $today waiting, checked_in, missed */
$approved = count(array_filter($venues, fn (array $v) => $v['status'] === 'approved'));
$todayTotal = $today['waiting'] + $today['checked_in'] + $today['missed'];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <span>Owner Portal</span>
            <span class="breadcrumb-separator">/</span>
            <span>Dashboard</span>
        </div>
        <h1 class="page-title">Welcome back, <?= e($name) ?></h1>
        <div class="page-subtitle">Booking requests, today's arrivals and your venues at a glance.</div>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?= url('/owner/check-in') ?>" class="btn btn-primary">
            Customer Check-in
        </a>
        <a href="<?= url('/owner/bookings') ?>" class="btn btn-outline">
            Venue Bookings
        </a>
    </div>
</div>

<!-- Owner Stat Cards -->
<div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div>
            <div class="stat-value"><?= count($pending) ?></div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $confirmed ?></div>
            <div class="stat-label">Upcoming Confirmed</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $today['checked_in'] ?> / <?= (int) $todayTotal ?></div>
            <div class="stat-label">Checked In Today</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $approved ?> / <?= count($venues) ?></div>
            <div class="stat-label">Venues Approved</div>
        </div>
    </div>

</div>

<!-- Pending cash requests -->
<div class="card" style="margin-bottom: var(--space-8);<?= $pending === [] ? '' : ' border: 2px solid #fde68a;' ?>">
    <div class="card-header"<?= $pending === [] ? '' : ' style="background: #fffbeb;"' ?>>
        <h3 style="font-size: 15px; margin-bottom: 0;">Booking Requests Waiting for You (<?= count($pending) ?>)</h3>
        <?php if ($pending !== []): ?>
            <span class="badge badge-pending">Action Required</span>
        <?php endif; ?>
    </div>
    <?php if ($pending === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No requests waiting</div>
            <div class="empty-state-desc">Cash on Arrival requests appear here until you confirm or reject them.</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Customer & Reliability</th>
                        <th>Court & Requested Slot</th>
                        <th>Payment</th>
                        <th>Amount</th>
                        <th style="text-align: right;">Decision</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending as $b): ?>
                        <tr>
                            <td>
                                <strong><?= e($b['customer_name']) ?></strong>
                                <span class="tier-badge tier-<?= e($b['reliability_tier']) ?>" style="font-size: 10px; margin-left: 6px;"><?= $b['reliability_score'] === null ? '' : e(round($b['reliability_score'])) . '% ' ?><?= e(status_label($b['reliability_tier'])) ?></span>
                                <div class="text-xs text-muted"><?= (int) $b['completed_count'] ?> completed · <?= (int) $b['no_show_count'] ?> no-shows</div>
                            </td>
                            <td>
                                <strong><?= e($b['court_name']) ?></strong>
                                <div class="text-xs text-muted"><?= e($b['venue_name']) ?> · <?= e(format_datetime($b['slot_date'], false)) ?> · <?= e($b['start']) ?> - <?= e($b['end']) ?></div>
                            </td>
                            <td><span class="badge badge-unpaid"><?= e(status_label($b['payment_method'])) ?></span></td>
                            <td><strong><?= e(lkr($b['amount'])) ?></strong></td>
                            <td style="text-align: right;">
                                <a href="<?= url('/owner/bookings/' . $b['id']) ?>" class="btn btn-sm btn-primary">Review Booking #<?= (int) $b['id'] ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-2 gap-6">

    <!-- Venues -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">My Venues</h3>
            <a href="<?= url('/owner/venues') ?>" style="font-size: 12px; font-weight: 600;">Manage Venues &rarr;</a>
        </div>
        <div class="card-body">
            <?php if ($venues === []): ?>
                <p class="text-sm" style="margin-bottom: 12px;">You have not registered a venue yet.</p>
                <a href="<?= url('/owner/venues/create') ?>" class="btn btn-primary btn-sm">+ Register a Venue</a>
            <?php endif; ?>
            <?php foreach ($venues as $v): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--color-border);">
                    <div>
                        <a href="<?= url('/owner/venues/' . $v['id']) ?>" style="font-weight: 700;"><?= e($v['name']) ?></a>
                        <div class="text-xs text-muted"><?= e($v['city']) ?> · <?= (int) $v['court_count'] ?> <?= (int) $v['court_count'] === 1 ? 'court' : 'courts' ?></div>
                    </div>
                    <?= status_badge($v['status']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Reports -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">Reports</h3>
        </div>
        <div class="card-body">
            <p class="text-xs text-muted" style="margin-bottom: 12px;">Revenue, court utilisation and customer reliability across your venues.</p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?= url('/owner/revenue') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Revenue</a>
                <a href="<?= url('/owner/utilisation') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Court Utilisation</a>
                <a href="<?= url('/owner/customers') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Customer Intelligence</a>
                <a href="<?= url('/owner/reviews') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Customer Reviews</a>
            </div>
        </div>
    </div>

</div>
