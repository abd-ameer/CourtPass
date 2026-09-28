<?php
/** @var string $coachName @var array $upcoming upcoming sessions, soonest first @var int $completedCount @var array $venues approved venues with their courts @var bool $hasVenues
 * @var array $stats from CoachSessionService::coachStats() @var array $profile @var array $reviews latest three */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <span>Dashboard</span>
 </div>
 <h1 class="page-title">Welcome back, <?= e($coachName) ?></h1>
 <div class="page-subtitle">Your upcoming sessions, registrations and approved venues.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <a href="<?= url('/coach/sessions/create') ?>" class="btn btn-primary">
 + New Session
 </a>
 <a href="<?= url('/coach/sessions?status=completed') ?>" class="btn btn-outline">
 Attendance Desk
 </a>
 </div>
 </div>

 <!-- Coach Stat Cards -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 
 <div class="stat-card">
 <div class="stat-icon green">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
 </div>
 <div>
 <div class="stat-value"><?= count($upcoming) ?></div>
 <div class="stat-label">Upcoming Sessions</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon blue">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
 </div>
 <div>
 <div class="stat-value"><?= array_sum(array_column($upcoming, 'live_count')) ?></div>
 <div class="stat-label">Students in Upcoming Sessions</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon amber">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
 </div>
 <div>
 <div class="stat-value"><?= (int) $completedCount ?></div>
 <div class="stat-label">Completed Sessions</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
 </div>
 <div>
 <div class="stat-value"><?= count($venues) ?></div>
 <div class="stat-label">Approved Venues</div>
 </div>
 </div>

 </div>

 <!-- Earnings, students and rating -->
 <div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
 <div class="stat-card">
 <div>
 <div class="stat-value"><?= e(lkr($stats['revenue_month'])) ?></div>
 <div class="stat-label">Revenue in <?= e(date('F')) ?></div>
 <div class="text-xs text-muted">All time: <?= e(lkr($stats['revenue_total'])) ?></div>
 </div>
 </div>
 <div class="stat-card">
 <div>
 <div class="stat-value"><?= (int) $stats['students'] ?></div>
 <div class="stat-label">Unique Students</div>
 </div>
 </div>
 <div class="stat-card">
 <div>
 <div class="stat-value"><?= (int) $stats['regulars'] ?></div>
 <div class="stat-label">Regulars (3+ sessions)</div>
 </div>
 </div>
 <div class="stat-card">
 <div>
 <div class="stat-value"><?= $profile['avg_rating'] === null ? '-' : e(number_format($profile['avg_rating'], 1)) . ' ★' ?></div>
 <div class="stat-label">Average Rating (<?= (int) $profile['review_count'] ?> <?= $profile['review_count'] === 1 ? 'review' : 'reviews' ?>)</div>
 </div>
 </div>
 </div>

<!-- Upcoming Sessions -->
<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <h3 style="font-size: 15px; margin-bottom: 0;">Upcoming Sessions</h3>
        <a href="<?= url('/coach/sessions?status=upcoming') ?>" style="font-size: 12px; font-weight: 600;">All upcoming &rarr;</a>
    </div>
    <?php if ($upcoming === []): ?>
        <div class="empty-state">
            <?php if ($hasVenues): ?>
                <div class="empty-state-title">No upcoming sessions</div>
                <div class="empty-state-desc">Create a one-hour session on a court at one of your approved venues.</div>
                <a href="<?= url('/coach/sessions/create') ?>" class="btn btn-primary" style="margin-top: 12px;">Create a Session</a>
            <?php else: ?>
                <div class="empty-state-title">No approved venues yet</div>
                <div class="empty-state-desc">You can create sessions once a venue owner approves your request.</div>
                <a href="<?= url('/coach/venues') ?>" class="btn btn-primary" style="margin-top: 12px;">Go to Venue Approvals</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Session</th>
                        <th>Venue &amp; Court</th>
                        <th>Date &amp; Time</th>
                        <th>Registrations</th>
                        <th>Type</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($upcoming, 0, 5) as $s): ?>
                        <tr>
                            <td>
                                <strong><?= e($s['title']) ?></strong>
                                <div class="text-xs text-muted">#<?= (int) $s['id'] ?> · <?= e($s['fee_label']) ?></div>
                            </td>
                            <td>
                                <strong><?= e($s['venue_name']) ?></strong>
                                <div class="text-xs text-muted"><?= e($s['court_name']) ?></div>
                            </td>
                            <td>
                                <strong><?= e(format_datetime($s['session_date'], false)) ?></strong>
                                <div class="text-xs text-muted"><?= e($s['start']) ?> to <?= e($s['end']) ?></div>
                            </td>
                            <td><strong><?= (int) $s['registration_count'] ?> / <?= (int) $s['capacity'] ?></strong></td>
                            <td><span class="badge <?= $s['visibility'] === 'private' ? 'badge-warning' : 'badge-confirmed' ?>"><?= $s['visibility'] === 'private' ? 'Private' : 'Public' ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= url('/coach/sessions/' . $s['id']) ?>" class="btn btn-sm btn-outline">View</a>
                                <a href="<?= url('/coach/sessions/' . $s['id'] . '/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-2 gap-6">

    <!-- Approved venues -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">My Approved Venues</h3>
            <a href="<?= url('/coach/venues') ?>" style="font-size: 12px; font-weight: 600;">Venue Approvals &rarr;</a>
        </div>
        <div class="card-body">
            <?php if ($venues === []): ?>
                <p class="text-sm" style="margin-bottom: 0;">No venue has approved you yet. Request approval from the venues you want to coach at.</p>
            <?php endif; ?>
            <?php foreach ($venues as $v): ?>
                <?php $next = array_values(array_filter($upcoming, fn (array $s) => (int) $s['venue_id'] === (int) $v['id'])); ?>
                <div style="padding: 10px 0; border-bottom: 1px solid var(--color-border);">
                    <div style="display: flex; justify-content: space-between; gap: 10px;">
                        <strong><?= e($v['name']) ?></strong>
                        <span class="badge badge-approved">Approved</span>
                    </div>
                    <div class="text-xs text-muted">
                        Courts you can use: <?= e(implode(', ', array_map(fn (array $c) => $c['name'] . ' (' . $c['sport'] . ')', $v['courts']))) ?>
                    </div>
                    <div class="text-xs text-muted">
                        Next session: <?= $next === [] ? 'none scheduled' : e(format_datetime($next[0]['starts_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent reviews -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">Recent Reviews</h3>
            <a href="<?= url('/coach/reviews') ?>" style="font-size: 12px; font-weight: 600;">All reviews &rarr;</a>
        </div>
        <div class="card-body">
            <?php if ($reviews === []): ?>
                <p class="text-sm text-muted" style="margin-bottom: 0;">No reviews yet. Students marked Attended can review a session within 7 days.</p>
            <?php endif; ?>
            <?php foreach ($reviews as $r): ?>
                <div style="padding: 8px 0; border-bottom: 1px solid var(--color-border);">
                    <div style="display: flex; justify-content: space-between;"><strong class="text-sm"><?= e($r['reviewer_name']) ?></strong><span style="color: #f59e0b;"><?= str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']) ?></span></div>
                    <div class="text-xs text-muted"><?= e($r['session_title']) ?></div>
                    <p class="text-sm" style="margin: 4px 0 0;"><?= e(mb_strimwidth($r['comment'], 0, 140, '...')) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Quick links -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">Coaching Records</h3>
        </div>
        <div class="card-body">
            <p class="text-xs text-muted" style="margin-bottom: 12px;">Past sessions, attendance, earnings from paid registrations, and reviews from your students.</p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?= url('/coach/sessions?status=completed') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Completed Sessions & Attendance</a>
                <a href="<?= url('/coach/earnings') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Earnings</a>
                <a href="<?= url('/coach/reviews') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Reviews & Replies</a>
                <a href="<?= url('/coach/profile') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">Coach Profile</a>
            </div>
        </div>
    </div>

</div>
