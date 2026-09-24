<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Notifications</span>
 </div>
 <h1 class="page-title">Venue Alerts & Notifications </h1>
 <div class="page-subtitle">Real-time operational alerts for bookings, cancellations, check-ins, and coach requests.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="CourtPass.post('/notifications/read')">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
 Mark All as Read
 </button>
 </div>
 </div>

 <!-- Filters -->
 <div style="display: flex; gap: 8px; margin-bottom: var(--space-6); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-3);">
 <button class="btn btn-sm btn-primary notif-tab active" onclick="filterOwnerNotifs('all', this)">All Alerts (3)</button>
 <button class="btn btn-sm btn-outline notif-tab" onclick="filterOwnerNotifs('booking', this)">Bookings (2)</button>
 <button class="btn btn-sm btn-outline notif-tab" onclick="filterOwnerNotifs('coach', this)">Coach Requests (1)</button>
 </div>

 <!-- Notification List -->
 <div style="display: flex; flex-direction: column; gap: var(--space-3);" id="owner-notifs-container">

 <!-- Notif 1: New Online Booking -->
 <div class="card notif-item unread" data-type="booking" style="background: #ffffff; border-left: 4px solid var(--color-primary-active);">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #e6f8f0; color: var(--color-primary-active); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">New Booking Confirmed: Turf Court 1 (Floodlit)</strong>
 <span class="badge badge-primary">New</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Customer <strong>Kasun Mendis</strong> booked Today at 20:00 - 21:00. Paid LKR 5,000 via PayHere and confirmed automatically.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">10 minutes ago</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/owner/bookings') ?>" class="btn btn-sm btn-primary">View Booking</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 2: Coach Approval Request -->
 <div class="card notif-item unread" data-type="coach" style="background: #ffffff; border-left: 4px solid #f59e0b;">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">Coaching Approval Request: Coach Amanda Wijesinghe</strong>
 <span class="badge badge-warning">Pending Review</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Coach Amanda requested approval to conduct futsal sessions at Colombo Futsal Club.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">1 hour ago</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/owner/coach-requests') ?>" class="btn btn-sm btn-outline">Review Request</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 3: Booking Released for Resale -->
 <div class="card notif-item" data-type="booking">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">Booking Released for Resale: Turf Court 2</strong>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Customer released their booking for 24 Sep (19:00). The slot is back in the booking grid at the normal price.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">18 Sep 2026</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/owner/slots') ?>" class="btn btn-sm btn-outline">View Grid</a>
 </div>
 </div>
 </div>
 </div>

 </div>

 
 


<script>
function filterOwnerNotifs(type, btn) {
 document.querySelectorAll('.notif-tab').forEach(t => {
 t.classList.remove('btn-primary', 'active');
 t.classList.add('btn-outline');
 });
 btn.classList.remove('btn-outline');
 btn.classList.add('btn-primary', 'active');

 const items = document.querySelectorAll('.notif-item');
 items.forEach(item => {
 if (type === 'all' || item.dataset.type === type) {
 item.style.display = '';
 } else {
 item.style.display = 'none';
 }
 });
}
</script>
