<?php
/** @var int $bookingId */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/bookings') ?>">Bookings</a>
 <span class="breadcrumb-separator">/</span>
 <span>#<?= e($bookingId) ?></span>
 </div>
 <div style="display: flex; align-items: center; gap: 10px;">
 <h1 class="page-title">Booking #<?= e($bookingId) ?> Overview</h1>
 <span class="badge badge-confirmed">Confirmed</span>
 <span class="badge badge-paid">Online Paid</span>
 </div>
 <div class="page-subtitle">Pass Code: <strong>#3</strong> · Turf Court 1 · Sept 24, 2026 (08:00 PM - 09:00 PM)</div>
 </div>

 <div style="display: flex; gap: 8px;">
 <a href="<?= url('/owner/check-in?q=3') ?>" class="btn btn-primary">
 Check-in Customer
 </a>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
 
 <!-- Left: Customer Intelligence Profile (UC-VO-17) -->
 <div class="card" style="border: 2px solid var(--color-primary-border);">
 <div class="card-header" style="background: var(--color-primary-light);">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <h3 style="font-size: 15px; color: var(--color-primary-active); margin-bottom: 0;">Customer Intelligence Profile (UC-VO-17)</h3>
 </div>
 <span class="badge tier-standard">Score: 88%</span>
 </div>
 <div class="card-body">
 <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid var(--color-border);">
 <div style="width: 52px; height: 52px; border-radius: 50%; background: var(--color-primary-light); color: var(--color-primary-active); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800;">
 KJ
 </div>
 <div>
 <div style="font-size: 16px; font-weight: 800;">Kasun Jayawardena</div>
 <div class="text-xs text-muted">Phone: +94 77 123 4567 · Email: kasun.j@gmail.com</div>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md);">
 <div class="text-xs text-muted">Venue-Specific History</div>
 <div style="font-size: 18px; font-weight: 800; color: var(--color-text-title);">14 Sessions</div>
 
 </div>
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md);">
 <div class="text-xs text-muted">No-Show Record</div>
 <div style="font-size: 18px; font-weight: 800; color: #166534;">0 (Clean)</div>
 <div class="text-xs text-muted">High Accountability</div>
 </div>
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md);">
 <div class="text-xs text-muted">Last Facility Visit</div>
 <div style="font-size: 15px; font-weight: 700; color: var(--color-text-title);">Sept 14, 2026</div>
 <div class="text-xs text-muted">Turf Court 1 (Checked in)</div>
 </div>
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md);">
 <div class="text-xs text-muted">Payment Tier Standing</div>
 <div style="font-size: 15px; font-weight: 700; color: var(--color-primary);">Standard Tier</div>
 <div class="text-xs text-muted">Cash-on-Arrival Eligible</div>
 </div>
 </div>

 <div style="padding: 10px 14px; background: #ecfdf5; border-radius: var(--radius-md); font-size: 12px; color: #065f46;">
 <strong>Owner Operational Note:</strong> Reliable recurring customer with verified check-in history. Safe for priority slot allocation.
 </div>
 </div>
 </div>

 <!-- Right: Booking Financial & Slot Details -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Reservation & Payment Summary</h3>
 <span class="badge badge-paid">LKR 5,000 Paid</span>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px; margin-bottom: 20px;">
 <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Court Surface:</span>
 <strong>Turf Court 1 (Floodlit Synthetic Grass)</strong>
 </div>
 <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Date & Time:</span>
 <strong>Thursday, Sept 24, 2026 (08:00 PM - 09:00 PM)</strong>
 </div>
 <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">Payment Gateway:</span>
 <strong>PayHere Sandbox Integration</strong>
 </div>
 <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--color-border-subtle); padding-bottom: 8px;">
 <span class="text-muted">PayHere Order Reference:</span>
 <strong style="color: var(--color-primary-active);">#3-PH-PAY</strong>
 </div>
 <div style="display: flex; justify-content: space-between;">
 <span class="text-muted">Net Venue Revenue:</span>
 <strong style="font-size: 16px; color: var(--color-primary-active);">LKR 5,000</strong>
 </div>
 </div>

 <div style="display: flex; gap: 10px;">
 <a href="<?= url('/owner/check-in?q=3') ?>" class="btn btn-primary flex-1">
 Check-in Customer
 </a>
 <button type="button" class="btn btn-secondary flex-1" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Cancel Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/<?= (int) $bookingId ?>/cancel')">
 Cancel Reservation...
 </button>
 </div>
 </div>
 </div>

 </div>

 
 


<script>
function CourtPassApp.postWithReason('Cancel Booking', 'A reason is required and is shown to the customer.', '/owner/bookings/<?= (int) $bookingId ?>/cancel') {
 document.getElementById('reasonModalTitle').textContent = 'Owner Cancel Booking #3';
 document.getElementById('reasonModalSubtitle').textContent = 'State mandatory reason for operational cancellation.';
 document.getElementById('mandatoryReasonSubmitBtn').onclick = function() {
 CourtPassApp.closeModal('mandatoryReasonModal');
 CourtPassApp.showToast('info', 'Booking Cancelled', 'Booking cancelled and simulated full refund issued to customer.');
 setTimeout(() => window.location.href = '<?= url('/owner/bookings') ?>', 1200);
 };
 CourtPassApp.openModal('mandatoryReasonModal');
}
</script>
