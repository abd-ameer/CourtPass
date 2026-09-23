<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions') ?>">Sessions</a>
 <span class="breadcrumb-separator">/</span>
 <span>#CS-201</span>
 </div>
 <h1 class="page-title">Badminton Fundamentals</h1>
 <div class="page-subtitle">Session #CS-201 · Public coaching session at CR&FC Badminton Complex</div>
 </div>
 <div style="display: flex; gap: 10px;">
 <a href="<?= url('/coach/edit-session') ?>?id=CS-201" class="btn btn-outline">️ Edit Session</a>
 <a href="<?= url('/coach/attendance') ?>?id=CS-201" class="btn btn-primary"> Mark Attendance</a>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Left: Session Info -->
 <div style="grid-column: span 2;">

 <!-- Session Overview Card -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Session Overview</h3>
 <span class="badge badge-confirmed">Upcoming</span>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Venue</div>
 <div style="font-weight: 600;">CR&FC Badminton Complex</div>
 <div class="text-xs text-muted">Longdon Place, Colombo 07</div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Court</div>
 <div style="font-weight: 600;">Court 2 (Wooden)</div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Date & Time</div>
 <div style="font-weight: 600;">September 22, 2026</div>
 <div class="text-xs text-muted">05:00 PM - 06:00 PM (1 Hour)</div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Sport & Level</div>
 <div style="font-weight: 600;"> Badminton — Intermediate</div>
 </div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 4px;">Description</div>
 <p style="font-size: 14px; line-height: 1.7; color: var(--color-text-body);">
 Learn the essential badminton fundamentals including proper grip techniques, forehand and backhand drives, 
 basic footwork patterns, and serving rules. This session is designed for intermediate players looking to 
 refine their foundations. Bring your own racket and wear non-marking shoes.
 </p>
 </div>
 </div>
 </div>

 <!-- Registered Students -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Registered Students (6 / 8)</h3>
 <a href="<?= url('/coach/session-registrations') ?>?id=CS-201" style="font-size: 12px; font-weight: 600;">Full List &rarr;</a>
 </div>
 <div class="card-body" style="padding: 0;">
 <?php
 $students = [
 ['initials' => 'NK', 'name' => 'Nuwan Karunanayake', 'time' => '2 hrs ago', 'bg' => '#dbeafe', 'color' => '#1d4ed8'],
 ['initials' => 'RP', 'name' => 'Ruwan Perera', 'time' => 'Yesterday', 'bg' => '#d1fae5', 'color' => '#065f46'],
 ['initials' => 'KD', 'name' => 'Kasun De Silva', 'time' => '2 days ago', 'bg' => '#fef3c7', 'color' => '#92400e'],
 ['initials' => 'MA', 'name' => 'Malini Amarasinghe', 'time' => '3 days ago', 'bg' => '#fce7f3', 'color' => '#be185d'],
 ['initials' => 'TF', 'name' => 'Tharindu Fernando', 'time' => '4 days ago', 'bg' => '#ede9fe', 'color' => '#6d28d9'],
 ['initials' => 'DS', 'name' => 'Dhanushi Senanayake', 'time' => '5 days ago', 'bg' => '#fef3c7', 'color' => '#92400e'],
 ];
 foreach ($students as $i => $s):
 $border = ($i < count($students) - 1) ? 'border-bottom: 1px solid var(--color-border);' : '';
 ?>
 <div style="padding: 12px 16px; display: flex; align-items: center; gap: 12px; <?php echo $border; ?>">
 <div style="width: 36px; height: 36px; background: <?php echo $s['bg']; ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: <?php echo $s['color']; ?>; font-size: 13px;"><?php echo $s['initials']; ?></div>
 <div style="flex: 1;">
 <div style="font-weight: 600; font-size: 13px;"><?php echo $s['name']; ?></div>
 <div class="text-xs text-muted">Registered <?php echo $s['time']; ?></div>
 </div>
 <span class="badge badge-paid">Paid LKR 1,500</span>
 </div>
 <?php endforeach; ?>
 </div>
 </div>

 </div>

 <!-- Right: Summary & Actions -->
 <div>
 <!-- Financial Summary -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header" style="background: #f0fdf4;">
 <h3 style="font-size: 15px; margin-bottom: 0; color: #166534;"> Revenue Summary</h3>
 </div>
 <div class="card-body">
 <div style="font-size: 13px;">
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Fee / Person</span>
 <strong>LKR 1,500</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Registrations</span>
 <strong>6 / 8</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Gross Revenue</span>
 <strong>LKR 9,000</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Platform Fee (10%)</span>
 <strong style="color: var(--color-danger);">- LKR 900</strong>
 </div>
 </div>
 <div style="margin-top: var(--space-3); padding-top: var(--space-3); border-top: 2px solid var(--color-primary);">
 <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700;">
 <span>Your Earnings</span>
 <span style="color: var(--color-primary);">LKR 8,100</span>
 </div>
 </div>
 </div>
 </div>

 <!-- Session Type -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Visibility</h3>
 </div>
 <div class="card-body">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
 <span class="badge badge-confirmed" style="font-size: 13px;"> Public Session</span>
 </div>
 <div class="text-xs text-muted">
 This session is visible on the public coaching directory. Any logged-in customer can register.
 </div>
 </div>
 </div>

 <!-- Quick Actions -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Quick Actions</h3>
 </div>
 <div class="card-body" style="display: flex; flex-direction: column; gap: 8px;">
 <a href="<?= url('/coach/attendance') ?>?id=CS-201" class="btn btn-primary" style="text-align: center;">
 Mark Attendance
 </a>
 <a href="<?= url('/coach/edit-session') ?>?id=CS-201" class="btn btn-outline" style="text-align: center;">
 ️ Edit Session
 </a>
 <a href="<?= url('/coach/session-registrations') ?>?id=CS-201" class="btn btn-outline" style="text-align: center;">
 View All Registrations
 </a>
 <a href="<?= url('/coach/cancel-session') ?>?id=CS-201" class="btn btn-secondary" style="text-align: center; color: var(--color-danger);">
 Cancel Session
 </a>
 </div>
 </div>

 </div>
