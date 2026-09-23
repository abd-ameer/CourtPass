<?php
$booking_id = $booking_id ?? $_GET['id'] ?? 'BK-9021';
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/customer/bookings') ?>">My Bookings</a>
 <span class="breadcrumb-separator">/</span>
 <span>Reschedule <?php echo htmlspecialchars($booking_id); ?></span>
 </div>
 <h1 class="page-title">Reschedule Your Booking</h1>
 <div class="page-subtitle">Select an alternative 1-hour slot at Colombo Futsal Club.</div>
 </div>
 </div>

 <!-- Current Booking Alert -->
 <div class="card" style="margin-bottom: var(--space-6); background: var(--color-bg-subtle); padding: 16px 20px;">
 <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
 <div>
 <div class="text-xs text-muted uppercase font-bold">Current Reserved Slot</div>
 <div style="font-size: 16px; font-weight: 800; color: var(--color-text-title);">
 Thursday, Sept 24, 2026 · 08:00 PM - 09:00 PM
 </div>
 <div class="text-xs text-muted">Colombo Futsal Club · Turf Court 1</div>
 </div>
 <span class="badge badge-confirmed">Reschedulable without fee (>24h)</span>
 </div>
 </div>

 <!-- Slot Availability Picker -->
 <div class="availability-container">
 <h3 style="font-size: 16px; margin-bottom: 12px;">Pick New Date & Slot</h3>
 <div class="date-selector-bar" id="dateSelectorBar"></div>
 <div class="slots-grid" id="slotsGrid"></div>

 <div class="availability-legend">
 <div class="legend-item"><div class="legend-swatch available"></div><span>Available Slot</span></div>
 <div class="legend-item"><div class="legend-swatch selected"></div><span>New Selection</span></div>
 <div class="legend-item"><div class="legend-swatch booked"></div><span>Unavailable</span></div>
 </div>
 </div>

 <!-- Reschedule Confirmation Action Box -->
 <div class="card" id="rescheduleConfirmBox" style="padding: 20px 24px; margin-top: var(--space-6); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
 <div>
 <div class="text-xs text-muted">Selected Alternative Time</div>
 <div style="font-size: 16px; font-weight: 800; color: var(--color-primary-active);" id="newSelectedSlotText">
 Friday, Sept 25, 2026 · 07:00 PM - 08:00 PM
 </div>
 <div class="text-xs text-muted">Same court rate · Zero reschedule penalty</div>
 </div>

 <button type="button" class="btn btn-primary btn-lg" onclick="executeReschedule()">
 Confirm Reschedule &rarr;
 </button>
 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassAvailability.init({
 courtId: 'c1',
 hourlyRate: 5000,
 isGuest: false
 });
});

function executeReschedule() {
 CourtPassApp.showToast('success', 'Booking Rescheduled!', 'Your reservation has been successfully updated to Sept 25, 07:00 PM.');
 setTimeout(() => {
 window.location.href = '<?= url('/customer/booking-details') ?>?id=BK-9021';
 }, 1200);
}
</script>
