<?php
$court_id = $court_id ?? $_GET['court'] ?? 'c1';
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/customer/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span>Court Slot Availability</span>
 </div>
 <h1 class="page-title">Turf Court 1 (Floodlit) — Live Schedule</h1>
 <div class="page-subtitle">Colombo Futsal Club · Click an available slot to reserve.</div>
 </div>

 <div style="text-align: right;">
 <div class="text-xs" style="color: var(--color-text-muted);">Standard Hourly Rate</div>
 <div style="font-size: 20px; font-weight: 900; color: var(--color-primary-active);">LKR 5,000 / hr</div>
 </div>
 </div>

 <!-- Court Switcher Tabs -->
 <div class="court-tabs-nav">
 <button class="court-tab-btn active" data-court-id="c1">Turf Court 1 (Floodlit)</button>
 <button class="court-tab-btn" data-court-id="c2">Turf Court 2 (Indoor)</button>
 <button class="court-tab-btn" data-court-id="c3">Wooden Badminton Court A</button>
 </div>

 <!-- Availability Engine Container -->
 <div class="availability-container">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 8px;">
 <h3 style="font-size: 16px; margin-bottom: 0;">Choose Date & 1-Hour Slot</h3>
 <span class="text-xs" style="color: var(--color-text-muted);">Asia/Colombo (UTC+5:30)</span>
 </div>

 <!-- Date Tabs Bar -->
 <div class="date-selector-bar" id="dateSelectorBar"></div>

 <!-- Slot Grid -->
 <div class="slots-grid" id="slotsGrid"></div>

 <!-- Availability Legend -->
 <div class="availability-legend">
 <div class="legend-item">
 <div class="legend-swatch available"></div>
 <span>Available</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch selected"></div>
 <span>Selected</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch booked"></div>
 <span>Booked</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch coaching"></div>
 <span>Coaching Clinic</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch blocked"></div>
 <span>Blocked / Maintenance</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch flash"></div>
 <span> Flash Deal (30% OFF)</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch unavailable"></div>
 <span>Unavailable / Past</span>
 </div>
 </div>
 </div>

 <!-- Selected Slot Summary Bar -->
 <div id="slotSelectionSummary" class="card" style="display: none; padding: 18px 24px; border-radius: var(--radius-xl); box-shadow: var(--shadow-elevation); background: var(--color-white); border: 2px solid var(--color-primary); margin-top: var(--space-6); align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
 <div>
 <div class="text-xs" style="color: var(--color-text-muted); text-transform: uppercase; font-weight: 700;">Selected Reservation</div>
 <div style="font-size: 16px; font-weight: 800; color: var(--color-text-title);" id="selectedSlotTimeDisplay">
 Sept 24, 2026 | 08:00 PM - 09:00 PM
 </div>
 <div class="text-xs" style="color: var(--color-text-muted); margin-top: 2px;">
 Turf Court 1 · Colombo Futsal Club · Conflict checks passed
 </div>
 </div>

 <div style="display: flex; align-items: center; gap: 24px;">
 <div style="text-align: right;">
 <div class="text-xs" style="color: var(--color-text-muted);">Slot Fee</div>
 <div style="font-size: 22px; font-weight: 900; color: var(--color-primary-active);" id="selectedSlotPriceDisplay">
 LKR 5,000
 </div>
 </div>

 <button type="button" class="btn btn-primary btn-lg" id="proceedToCheckoutBtn">
 Proceed to Checkout &rarr;
 </button>
 </div>
 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassAvailability.init({
 courtId: '<?php echo htmlspecialchars($court_id); ?>',
 hourlyRate: 5000,
 isGuest: false // Logged in as customer
 });
});
</script>
