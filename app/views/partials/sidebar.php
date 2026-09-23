<?php
$active_role = $active_role ?? Auth::role() ?? 'customer';
$currentPath = Request::current()?->path() ?? '';
?>
<aside class="dashboard-sidebar">
    <div class="sidebar-brand">
        <a href="<?= url('/') ?>" style="display: flex; align-items: center; text-decoration: none;">
            <img src="<?= asset('images/courtpasslogo.png') ?>" alt="<?= e(APP_NAME) ?>" class="brand-logo-img" style="height: 38px; max-width: 160px; object-fit: contain;">
        </a>
    </div>

    <div class="sidebar-nav">

    <?php if ($active_role === 'customer'): ?>
        <div class="sidebar-section-title">Overview</div>
        <a href="<?= url('/customer/dashboard') ?>" class="sidebar-link <?= is_active_route('/customer/dashboard', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>

        <div class="sidebar-section-title">Venues &amp; Bookings</div>
        <a href="<?= url('/customer/venues') ?>" class="sidebar-link <?= is_active_route('/customer/venues', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Browse Venues
        </a>
        <a href="<?= url('/customer/bookings') ?>" class="sidebar-link <?= is_active_route('/customer/bookings', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            My Bookings
            <span class="badge badge-confirmed">2</span>
        </a>
        <a href="<?= url('/customer/resale') ?>" class="sidebar-link <?= is_active_route('/customer/resale', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
            Resale Market
        </a>
        <a href="<?= url('/customer/my-resales') ?>" class="sidebar-link <?= is_active_route('/customer/my-resales', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            My Resale Listings
        </a>

        <div class="sidebar-section-title">Coaching</div>
        <a href="<?= url('/customer/coaching') ?>" class="sidebar-link <?= is_active_route('/customer/coaching', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Join Sessions
        </a>
        <a href="<?= url('/customer/my-sessions') ?>" class="sidebar-link <?= is_active_route('/customer/my-sessions', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            My Registrations
        </a>

        <div class="sidebar-section-title">Community &amp; Profile</div>
        <a href="<?= url('/customer/reliability') ?>" class="sidebar-link <?= is_active_route('/customer/reliability', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            Reliability Tier
            <span class="badge badge-confirmed">88%</span>
        </a>
        <a href="<?= url('/customer/reviews') ?>" class="sidebar-link <?= is_active_route('/customer/reviews', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            My Reviews
        </a>
        <a href="<?= url('/customer/notifications') ?>" class="sidebar-link <?= is_active_route('/customer/notifications', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            Notifications
        </a>
        <a href="<?= url('/customer/profile') ?>" class="sidebar-link <?= is_active_route('/customer/profile', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Profile &amp; Settings
        </a>

    <?php elseif ($active_role === 'owner'): ?>
        <div class="sidebar-section-title">Overview</div>
        <a href="<?= url('/owner/dashboard') ?>" class="sidebar-link <?= is_active_route('/owner/dashboard', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>

        <div class="sidebar-section-title">Venue &amp; Courts</div>
        <a href="<?= url('/owner/venues') ?>" class="sidebar-link <?= is_active_route('/owner/venues', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
            My Venues
        </a>
        <a href="<?= url('/owner/courts') ?>" class="sidebar-link <?= is_active_route('/owner/courts', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            Court Management
        </a>
        <a href="<?= url('/owner/operating-hours') ?>" class="sidebar-link <?= is_active_route('/owner/operating-hours', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            Operating Hours
        </a>
        <a href="<?= url('/owner/slot-management') ?>" class="sidebar-link <?= is_active_route('/owner/slot-management', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Slot Grid &amp; Blocks
        </a>

        <div class="sidebar-section-title">Operations</div>
        <a href="<?= url('/owner/bookings') ?>" class="sidebar-link <?= is_active_route('/owner/bookings', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            Bookings Table
            <span class="badge badge-pending">2 Pending</span>
        </a>
        <a href="<?= url('/owner/check-in') ?>" class="sidebar-link <?= is_active_route('/owner/check-in', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            Customer Check-in
        </a>
        <a href="<?= url('/owner/flash-slots') ?>" class="sidebar-link <?= is_active_route('/owner/flash-slots', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            Flash Deals
        </a>
        <a href="<?= url('/owner/coach-requests') ?>" class="sidebar-link <?= is_active_route('/owner/coach-requests', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            Coach Approvals
            <span class="badge badge-warning">1</span>
        </a>
        <a href="<?= url('/owner/announcements') ?>" class="sidebar-link <?= is_active_route('/owner/announcements', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"></path><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
            Announcements
        </a>

        <div class="sidebar-section-title">Intelligence &amp; Analytics</div>
        <a href="<?= url('/owner/revenue') ?>" class="sidebar-link <?= is_active_route('/owner/revenue', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Revenue Analytics
        </a>
        <a href="<?= url('/owner/utilisation') ?>" class="sidebar-link <?= is_active_route('/owner/utilisation', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            Court Utilisation
        </a>
        <a href="<?= url('/owner/customers') ?>" class="sidebar-link <?= is_active_route('/owner/customers', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
            Customer Intelligence
        </a>
        <a href="<?= url('/owner/profile') ?>" class="sidebar-link <?= is_active_route('/owner/profile', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Venue Settings
        </a>

    <?php elseif ($active_role === 'coach'): ?>
        <div class="sidebar-section-title">Overview</div>
        <a href="<?= url('/coach/dashboard') ?>" class="sidebar-link <?= is_active_route('/coach/dashboard', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>

        <div class="sidebar-section-title">Coaching Sessions</div>
        <a href="<?= url('/coach/sessions') ?>" class="sidebar-link <?= is_active_route('/coach/sessions', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            My Sessions
            <span class="badge badge-confirmed">3</span>
        </a>
        <a href="<?= url('/coach/create-session') ?>" class="sidebar-link <?= is_active_route('/coach/create-session', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            Create Session
        </a>
        <a href="<?= url('/coach/attendance') ?>" class="sidebar-link <?= is_active_route('/coach/attendance', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            Attendance Desk
        </a>

        <div class="sidebar-section-title">Profile &amp; Venues</div>
        <a href="<?= url('/coach/venues') ?>" class="sidebar-link <?= is_active_route('/coach/venues', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Approved Venues
        </a>
        <a href="<?= url('/coach/earnings') ?>" class="sidebar-link <?= is_active_route('/coach/earnings', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Earnings
        </a>
        <a href="<?= url('/coach/reviews') ?>" class="sidebar-link <?= is_active_route('/coach/reviews', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            Reviews &amp; Replies
        </a>
        <a href="<?= url('/coach/profile') ?>" class="sidebar-link <?= is_active_route('/coach/profile', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Profile &amp; Certs
        </a>

    <?php elseif ($active_role === 'admin'): ?>
        <div class="sidebar-section-title">Administration</div>
        <a href="<?= url('/admin/dashboard') ?>" class="sidebar-link <?= is_active_route('/admin/dashboard', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Admin Dashboard
        </a>
        <a href="<?= url('/admin/venue-approvals') ?>" class="sidebar-link <?= is_active_route('/admin/venue-approvals', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            Pending Venues
            <span class="badge badge-warning">2</span>
        </a>
        <a href="<?= url('/admin/coach-verifications') ?>" class="sidebar-link <?= is_active_route('/admin/coach-verifications', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            Coach Verification
            <span class="badge badge-warning">3</span>
        </a>
        <a href="<?= url('/admin/users') ?>" class="sidebar-link <?= is_active_route('/admin/users', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            User Management
        </a>

        <div class="sidebar-section-title">Disputes &amp; Moderation</div>
        <a href="<?= url('/admin/disputes-no-show') ?>" class="sidebar-link <?= is_active_route('/admin/disputes-no-show', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            No-Show Disputes
            <span class="badge badge-danger">1</span>
        </a>
        <a href="<?= url('/admin/disputes-resale') ?>" class="sidebar-link <?= is_active_route('/admin/disputes-resale', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
            Resale Disputes
        </a>
        <a href="<?= url('/admin/review-moderation') ?>" class="sidebar-link <?= is_active_route('/admin/review-moderation', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            Review Moderation
        </a>
        <a href="<?= url('/admin/announcement-moderation') ?>" class="sidebar-link <?= is_active_route('/admin/announcement-moderation', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5L6 9H2v6h4l5 4V5z"></path><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
            Announcements
        </a>
        <a href="<?= url('/admin/settings') ?>" class="sidebar-link <?= is_active_route('/admin/settings', $currentPath) ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Platform Settings
        </a>
    <?php endif; ?>

    </div>

    <div class="sidebar-footer">
        <div style="font-size: 11px; color: var(--color-text-subtle); display: flex; align-items: center; justify-content: space-between;">
            <span>CourtPass v2.0</span>
            <span>UCSC Group 40</span>
        </div>
    </div>
</aside>
