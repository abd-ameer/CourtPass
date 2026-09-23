<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Notifications</span>
 </div>
 <h1 class="page-title">Notifications & Activity Alerts</h1>
 <div class="page-subtitle">In-app updates regarding bookings, resale sales, coaching, and reviews.</div>
 </div>

 <button class="btn btn-outline btn-sm" onclick="CourtPassApp.showToast('success', 'Marked as Read', 'All notifications cleared.');">
 Mark All as Read
 </button>
 </div>

 <!-- Notifications List -->
 <div class="card">
 <div style="display: flex; flex-direction: column;">
 
 <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; gap: 14px; background: #f0fdf4;">
 <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--color-primary-light); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
 
 </div>
 <div style="flex: 1;">
 <div style="display: flex; justify-content: space-between; align-items: center;">
 <strong style="color: var(--color-text-title); font-size: 14px;">Booking Confirmed (CP-78190)</strong>
 <span class="text-xs text-muted">2 hours ago</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;">
 Your online reservation for Turf Court 1 at Colombo Futsal Club on Sept 24, 08:00 PM is confirmed. Pass ready.
 </p>
 <a href="<?= url('/customer/booking-details') ?>?id=BK-9021" style="font-size: 12px; font-weight: 700;">View Pass & QR &rarr;</a>
 </div>
 </div>

 <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: 50%; background: #fff7ed; color: #ea580c; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
 
 </div>
 <div style="flex: 1;">
 <div style="display: flex; justify-content: space-between; align-items: center;">
 <strong style="color: var(--color-text-title); font-size: 14px;">Flash Deal Available: 30% OFF</strong>
 <span class="text-xs text-muted">5 hours ago</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;">
 Colombo Futsal Club just marked Turf Court 1 at 10:00 PM tonight as a Flash Deal for LKR 3,500.
 </p>
 <a href="<?= url('/customer/court-availability') ?>?court=c1" style="font-size: 12px; font-weight: 700;">Claim Flash Slot &rarr;</a>
 </div>
 </div>

 <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: 50%; background: #f5f3ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
 
 </div>
 <div style="flex: 1;">
 <div style="display: flex; justify-content: space-between; align-items: center;">
 <strong style="color: var(--color-text-title); font-size: 14px;">New Masterclass Published</strong>
 <span class="text-xs text-muted">Yesterday</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;">
 Coach Dilshan Perera added "Badminton Jump Smash & Forecourt Mastery" at CR&FC Badminton Complex.
 </p>
 <a href="<?= url('/customer/session-details') ?>?id=session-101" style="font-size: 12px; font-weight: 700; color: #7c3aed;">View Clinic Details &rarr;</a>
 </div>
 </div>

 <div style="padding: 16px 20px; display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
 
 </div>
 <div style="flex: 1;">
 <div style="display: flex; justify-content: space-between; align-items: center;">
 <strong style="color: var(--color-text-title); font-size: 14px;">Owner Responded to Your Review</strong>
 <span class="text-xs text-muted">2 days ago</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;">
 Nuwan Senanayake from Colombo Futsal Club replied: "Thank you Kasun! We strive to maintain FIFA standards..."
 </p>
 <a href="<?= url('/customer/reviews') ?>" style="font-size: 12px; font-weight: 700;">View Review & Response &rarr;</a>
 </div>
 </div>

 </div>
