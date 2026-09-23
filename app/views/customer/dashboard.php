<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Customer Portal</span>
            <span class="breadcrumb-separator">/</span>
            <span>Dashboard</span>
        </div>
        <h1 class="page-title">Welcome back, Kasun</h1>
        <div class="page-subtitle">Your upcoming match schedule, venue bookings, and reliability tier overview.</div>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?= url('/customer/venues') ?>" class="btn btn-primary">
            + Book a Court
        </a>
        <a href="<?= url('/customer/my-resales') ?>" class="btn btn-outline">
            My Resale Requests
        </a>
    </div>
</div>

<!-- Reliability Tier Privilege Alert Banner -->
<div class="card" style="margin-bottom: var(--space-6); background: var(--color-primary-light); border: 1px solid var(--color-primary-border); padding: var(--space-5);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 46px; height: 46px; border-radius: 50%; background: var(--color-navy); color: white; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800;">
                88%
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 0; color: var(--color-navy);">Standard Tier Member</h3>
                    <span class="badge badge-confirmed">Cash on Arrival Unlocked</span>
                </div>
                <div class="text-xs" style="color: var(--color-text-main); margin-top: 3px;">
                    14 completed games · 0 no-shows. You are eligible to reserve slots with Pay-at-Venue privilege.
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
            <div class="stat-value">2</div>
            <div class="stat-label">Upcoming Bookings</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div>
            <div class="stat-value">1</div>
            <div class="stat-label">Coach Session</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
            <div class="stat-value">14</div>
            <div class="stat-label">Completed Matches</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
        </div>
        <div>
            <div class="stat-value">1</div>
            <div class="stat-label">Resale Requests</div>
        </div>
    </div>

</div>

<!-- Upcoming Bookings Table Card -->
<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <div>
            <h3 class="card-title">My Active Bookings & Passes</h3>
            <div class="card-subtitle">Show your digital pass or QR at the venue check-in desk on arrival.</div>
        </div>
        <a href="<?= url('/customer/bookings') ?>" class="btn btn-sm btn-outline">View All Bookings</a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Booking Ref</th>
                    <th>Venue & Court</th>
                    <th>Sport</th>
                    <th>Date & Time</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1: Upcoming Confirmed -->
                <tr>
                    <td>
                        <strong style="font-family: monospace; color: var(--color-navy);">#CP-BK-9021</strong>
                        <div class="text-xs" style="color: var(--color-text-subtle);">Booked online</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--color-text-title);">Colombo Futsal Club</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">Turf Court 1 (Floodlit)</div>
                    </td>
                    <td>
                        <span class="badge badge-confirmed">Futsal</span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">Today, 24 Sep 2026</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">08:00 PM - 09:00 PM (1 hr)</div>
                    </td>
                    <td>
                        <span class="badge badge-paid">Paid (PayHere)</span>
                    </td>
                    <td>
                        <span class="badge badge-confirmed">Confirmed</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="<?= url('/customer/booking-details') ?>?id=BK-9021" class="btn btn-sm btn-primary">Pass & QR</a>
                            <a href="<?= url('/customer/reschedule-booking') ?>?id=BK-9021" class="btn btn-sm btn-outline" title="Reschedule">Reschedule</a>
                        </div>
                    </td>
                </tr>

                <!-- Row 2: Coaching Registration -->
                <tr>
                    <td>
                        <strong style="font-family: monospace; color: var(--color-navy);">#CP-CS-4102</strong>
                        <div class="text-xs" style="color: var(--color-text-subtle);">Coaching Session</div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--color-text-title);">CR&FC Badminton Complex</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">Coach Dilshan Perera · Court 2</div>
                    </td>
                    <td>
                        <span class="badge badge-primary">Badminton</span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">Tomorrow, 25 Sep 2026</div>
                        <div class="text-xs" style="color: var(--color-text-muted);">06:00 PM - 07:00 PM (1 hr)</div>
                    </td>
                    <td>
                        <span class="badge badge-paid">Paid (PayHere)</span>
                    </td>
                    <td>
                        <span class="badge badge-confirmed">Enrolled</span>
                    </td>
                    <td>
                        <a href="<?= url('/customer/session-details') ?>?id=ses-1" class="btn btn-sm btn-outline">Session Info</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Resale Management & Quick Actions -->
<div class="grid grid-cols-2 gap-6">
    
    <!-- Resale Status Summary Card -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 0;">Slot Resale & Refunds</h3>
            <span class="badge badge-confirmed">90% Refund Policy</span>
        </div>
        <div class="card-body">
            <p class="text-xs" style="color: var(--color-text-muted); margin-bottom: 12px;">
                Can't attend a game? Release your slot back to the standard booking calendar. When another player purchases it, you receive a 90% refund (10% resale processing fee).
            </p>
            <div style="padding: 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md); margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--color-border);">
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--color-navy);">Colombo Futsal Club · Turf 2</div>
                    <div class="text-xs" style="color: var(--color-text-muted);">Sept 24 · 09:00 PM - 10:00 PM</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--color-warning);">Live in Booking Pool</div>
                    <div class="text-xs" style="color: var(--color-text-muted);">90% Refund: LKR 4,050</div>
                </div>
            </div>

            <a href="<?= url('/customer/my-resales') ?>" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 10px;">
                Manage Resale Requests &rarr;
            </a>
        </div>
    </div>

    <!-- Discover Coaches & Venues -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 0;">Quick Discovery</h3>
            <a href="<?= url('/customer/venues') ?>" class="text-xs" style="font-weight: 600; color: var(--color-primary-hover);">View All Venues &rarr;</a>
        </div>
        <div class="card-body">
            <p class="text-xs" style="color: var(--color-text-muted); margin-bottom: 12px;">
                Explore verified sports facilities and certified coaching academies in Colombo.
            </p>

            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?= url('/customer/venues') ?>?sport=futsal" class="btn btn-sm btn-outline" style="justify-content: flex-start;">
                    Futsal Courts in Colombo (8 Venues)
                </a>
                <a href="<?= url('/customer/venues') ?>?sport=badminton" class="btn btn-sm btn-outline" style="justify-content: flex-start;">
                    Badminton Courts (12 Venues)
                </a>
                <a href="<?= url('/customer/coaching') ?>" class="btn btn-sm btn-outline" style="justify-content: flex-start;">
                    Certified Coaching Sessions & Clinics
                </a>
            </div>
        </div>
    </div>

</div>
