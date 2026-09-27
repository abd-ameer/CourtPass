<?php
/** @var string $name @var array $upcoming @var array $released @var array $profile */
$tier = $profile['reliability_tier'];
$score = $profile['reliability_score'] === null ? null : round((float) $profile['reliability_score']);
$tierText = [
    'standard'   => ['Standard Tier', 'Cash on Arrival available', 'badge-confirmed', 'You can pay online or in cash at the venue.'],
    'restricted' => ['Restricted Tier', 'Online payment only', 'badge-warning', 'Your score is below 70%, so bookings are paid online.'],
    'new_member' => ['New Member', 'Online payment only', 'badge-warning', 'Cash on Arrival opens after 5 completed bookings with a score of 70% or more.'],
][$tier];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Customer Portal</span>
            <span class="breadcrumb-separator">/</span>
            <span>Dashboard</span>
        </div>
        <h1 class="page-title">Welcome back, <?= e($name) ?></h1>
        <div class="page-subtitle">Your upcoming bookings, released slots and reliability tier.</div>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?= url('/venues') ?>" class="btn btn-primary">
            + Book a Court
        </a>
        <a href="<?= url('/customer/releases') ?>" class="btn btn-outline">
            Released Bookings
        </a>
    </div>
</div>

<!-- Reliability tier -->
<div class="card" style="margin-bottom: var(--space-6); background: var(--color-primary-light); border: 1px solid var(--color-primary-border); padding: var(--space-5);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 50%; background: var(--color-navy); color: white; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800;">
                <?= $score === null ? 'New' : e($score) . '%' ?>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 0; color: var(--color-navy);"><?= e($tierText[0]) ?></h3>
                    <span class="badge <?= e($tierText[2]) ?>"><?= e($tierText[1]) ?></span>
                </div>
                <div class="text-xs" style="color: var(--color-text-main); margin-top: 3px;">
                    <?= (int) $profile['completed_count'] ?> completed · <?= (int) $profile['no_show_count'] ?> no-shows. <?= e($tierText[3]) ?>
                </div>
            </div>
        </div>
        <a href="<?= url('/customer/reliability') ?>" class="btn btn-sm btn-outline" style="background: var(--color-white);">
            View Score Breakdown &rarr;
        </a>
    </div>
</div>

<!-- KPI Stat Cards -->
<div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">

    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        </div>
        <div>
            <div class="stat-value"><?= count($upcoming) ?></div>
            <div class="stat-label">Upcoming Bookings</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $profile['completed_count'] ?></div>
            <div class="stat-label">Completed Bookings</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div>
            <div class="stat-value"><?= (int) $profile['no_show_count'] ?></div>
            <div class="stat-label">No-Shows</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
        </div>
        <div>
            <div class="stat-value"><?= count($released) ?></div>
            <div class="stat-label">Released for Resale</div>
        </div>
    </div>

</div>

<!-- Upcoming bookings -->
<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <div>
            <h3 class="card-title">Upcoming Bookings</h3>
            <div class="card-subtitle">Give your booking number at the venue desk on arrival. The venue marks your check-in.</div>
        </div>
        <a href="<?= url('/customer/bookings') ?>" class="btn btn-sm btn-outline">View All Bookings</a>
    </div>

    <?php if ($upcoming === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No upcoming bookings</div>
            <div class="empty-state-desc">Pick a venue and a free one-hour slot to book a court.</div>
            <a href="<?= url('/venues') ?>" class="btn btn-primary" style="margin-top: 12px;">Browse Venues</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Venue & Court</th>
                        <th>Sport</th>
                        <th>Date & Time</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upcoming as $b): ?>
                        <tr>
                            <td><strong style="font-family: monospace; color: var(--color-navy);">#<?= (int) $b['id'] ?></strong></td>
                            <td>
                                <div style="font-weight: 700; color: var(--color-text-title);"><?= e($b['venue_name']) ?></div>
                                <div class="text-xs" style="color: var(--color-text-muted);"><?= e($b['court_name']) ?></div>
                            </td>
                            <td><span class="badge badge-primary"><?= e($b['sport_name']) ?></span></td>
                            <td>
                                <div style="font-weight: 600;"><?= e(format_datetime($b['slot_date'], false)) ?></div>
                                <div class="text-xs" style="color: var(--color-text-muted);"><?= e($b['start']) ?> - <?= e($b['end']) ?></div>
                            </td>
                            <td><?= e($b['payment_method'] === 'online' ? 'Online' : 'Cash on Arrival') ?></td>
                            <td><?= status_badge($b['status']) ?></td>
                            <td><a href="<?= url('/customer/bookings/' . $b['id']) ?>" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-2 gap-6">

    <!-- Released slots -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 0;">Slot Resale & Refunds</h3>
            <span class="badge badge-confirmed">90% Refund Policy</span>
        </div>
        <div class="card-body">
            <p class="text-xs" style="color: var(--color-text-muted); margin-bottom: 12px;">
                Can't make it? Release an online-paid booking back to the normal booking grid. If another customer books the slot, you get a 90% refund (10% resale fee).
            </p>
            <?php if ($released === []): ?>
                <p class="text-sm" style="margin-bottom: 0;">You have no released bookings.</p>
            <?php endif; ?>
            <?php foreach ($released as $b): ?>
                <div style="padding: 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md); margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--color-border); gap: 10px;">
                    <div>
                        <div style="font-size: 13px; font-weight: 700; color: var(--color-navy);"><?= e($b['venue_name']) ?> · <?= e($b['court_name']) ?></div>
                        <div class="text-xs" style="color: var(--color-text-muted);"><?= e(format_datetime($b['slot_date'], false)) ?> · <?= e($b['start']) ?> - <?= e($b['end']) ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 12px; font-weight: 700; color: var(--color-warning);">Open for rebooking</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">Paid <?= e(lkr($b['amount'])) ?> · 90% back if rebooked</div>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="<?= url('/customer/releases') ?>" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 10px;">
                Released Bookings &rarr;
            </a>
        </div>
    </div>

    <!-- Quick links -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 0;">Quick Discovery</h3>
            <a href="<?= url('/venues') ?>" class="text-xs" style="font-weight: 600; color: var(--color-primary-hover);">View All Venues &rarr;</a>
        </div>
        <div class="card-body">
            <p class="text-xs" style="color: var(--color-text-muted); margin-bottom: 12px;">
                Find approved venues with live slot availability, or join a one-hour session with a coach.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?= url('/venues') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Browse Venues</a>
                <a href="<?= url('/coaching') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Coaching Sessions</a>
                <a href="<?= url('/customer/reviews') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">My Reviews</a>
            </div>
        </div>
    </div>

</div>
