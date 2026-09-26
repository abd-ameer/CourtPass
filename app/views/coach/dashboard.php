<?php
/** @var string $coachName @var array $upcoming upcoming sessions, soonest first @var bool $hasVenues */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <span>Dashboard</span>
 </div>
 <h1 class="page-title">Welcome back, <?= e($coachName) ?></h1>
 <div class="page-subtitle">Track your coaching sessions, earnings, and student engagement at a glance.</div>
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
 <div class="stat-value">42</div>
 <div class="stat-label">Total Registered Students</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon amber">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 </div>
 <div>
 <div class="stat-value">LKR 68,500</div>
 <div class="stat-label">September Earnings</div>
 </div>
 </div>

 <div class="stat-card">
 <div class="stat-icon purple">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
 </div>
 <div>
 <div class="stat-value">4.7 </div>
 <div class="stat-label">Average Rating (18 Reviews)</div>
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

 <!-- Recent Student Registrations + Earnings Chart -->
 <div class="grid grid-cols-2 gap-6" style="margin-bottom: var(--space-6);">
 
 <!-- Recent Registrations -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Recent Student Registrations</h3>
 <a href="<?= url('/coach/sessions') ?>" style="font-size: 12px; font-weight: 600;">All Sessions &rarr;</a>
 </div>
 <div class="card-body" style="padding: 0;">
 <div style="padding: 12px 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--color-border);">
 <div style="width: 36px; height: 36px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1d4ed8; font-size: 14px;">NK</div>
 <div style="flex: 1;">
 <div style="font-weight: 600; font-size: 13px;">Nuwan Karunanayake</div>
 <div class="text-xs text-muted">Registered for "Badminton Fundamentals" · 2 hrs ago</div>
 </div>
 <span class="badge badge-paid">Paid LKR 1,500</span>
 </div>
 <div style="padding: 12px 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--color-border);">
 <div style="width: 36px; height: 36px; background: #fce7f3; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #be185d; font-size: 14px;">SJ</div>
 <div style="flex: 1;">
 <div style="font-weight: 600; font-size: 13px;">Samanthi Jayasuriya</div>
 <div class="text-xs text-muted">Registered for "Advanced Doubles Strategy" · 5 hrs ago</div>
 </div>
 <span class="badge badge-paid">Paid LKR 2,000</span>
 </div>
 <div style="padding: 12px 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--color-border);">
 <div style="width: 36px; height: 36px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #065f46; font-size: 14px;">RP</div>
 <div style="flex: 1;">
 <div style="font-weight: 600; font-size: 13px;">Ruwan Perera</div>
 <div class="text-xs text-muted">Registered for "Badminton Fundamentals" · Yesterday</div>
 </div>
 <span class="badge badge-paid">Paid LKR 1,500</span>
 </div>
 <div style="padding: 12px 16px; display: flex; align-items: center; gap: 12px;">
 <div style="width: 36px; height: 36px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #92400e; font-size: 14px;">KD</div>
 <div style="flex: 1;">
 <div style="font-weight: 600; font-size: 13px;">Kasun De Silva</div>
 <div class="text-xs text-muted">Registered for "Footwork Drills" · 2 days ago</div>
 </div>
 <span class="badge badge-paid">Paid LKR 1,500</span>
 </div>
 </div>
 </div>

 <!-- Earnings Chart -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Monthly Earnings Breakdown</h3>
 <a href="<?= url('/coach/earnings') ?>" style="font-size: 12px; font-weight: 600;">Full Report &rarr;</a>
 </div>
 <div class="card-body">
 <div class="table-responsive"><table class="data-table"><thead><tr><th>Month</th><th>Net Earnings (paid registrations minus refunds)</th></tr></thead><tbody><tr><td>May</td><td>LKR 42,000</td></tr><tr><td>Jun</td><td>LKR 56,000</td></tr><tr><td>Jul</td><td>LKR 78,000</td></tr><tr><td>Aug</td><td>LKR 92,000</td></tr><tr><td>Sep</td><td>LKR 87,500</td></tr></tbody></table></div>
 <div class="text-xs text-muted" style="text-align: center; margin-top: 8px;">
 Coaching session fees collected via PayHere, minus refunds.
 </div>
 </div>
 </div>

 </div>

 <!-- Venue Approval Status -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">My Approved Venues</h3>
 <a href="<?= url('/coach/venues') ?>" style="font-size: 12px; font-weight: 600;">Manage Venues &rarr;</a>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Venue</th>
 <th>Sport</th>
 <th>Approval Status</th>
 <th>Sessions Conducted</th>
 <th>Next Session</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Longdon Place, Colombo 07</div>
 </td>
 <td><span class="badge badge-sport" style="background:#e6f8f0; color:#00b562;"> Badminton</span></td>
 <td><span class="badge badge-confirmed">Approved</span></td>
 <td><strong>12</strong></td>
 <td>Sept 22, 5:00 PM</td>
 </tr>
 <tr>
 <td>
 <strong>Royal College Sports Complex</strong>
 <div class="text-xs text-muted">Reid Avenue, Colombo 07</div>
 </td>
 <td><span class="badge badge-sport" style="background:#e6f8f0; color:#00b562;"> Badminton</span></td>
 <td><span class="badge badge-confirmed">Approved</span></td>
 <td><strong>5</strong></td>
 <td>Sept 22, 7:00 PM</td>
 </tr>
 <tr>
 <td>
 <strong>SpinMaster Academy</strong>
 <div class="text-xs text-muted">Rajagiriya</div>
 </td>
 <td><span class="badge badge-sport" style="background:#fef3c7; color:#92400e;"> Table Tennis</span></td>
 <td><span class="badge badge-pending">Pending Approval</span></td>
 <td><strong>0</strong></td>
 <td>—</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 
 


