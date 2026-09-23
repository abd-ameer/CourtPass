<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
 <div class="container">
 <div class="breadcrumb">
 <a href="<?= url('/') ?>">Home</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/venue-details') ?>?id=venue-1">Colombo Futsal Club</a>
 <span class="breadcrumb-separator">/</span>
 <span>Turf Court 1 (Floodlit)</span>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-top: var(--space-4);">
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
 <h1 style="font-size: var(--font-size-2xl);">Turf Court 1 (Floodlit)</h1>
 <span class="badge badge-confirmed"> Futsal</span>
 <span class="badge badge-active">Available Today</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 0;">
 Colombo Futsal Club · FIFA-grade synthetic turf · Operating Hours: 06:00 AM - 11:00 PM
 </p>
 </div>

 <div style="text-align: right;">
 <div style="font-size: 24px; font-weight: 900; color: var(--color-primary-active);">LKR 5,000</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Standard 1-hour slot rate</div>
 </div>
 </div>
 </div>
</div>

<main style="padding: var(--space-8) 0;">
 <div class="container">
 
 <!-- Court Selection Tabs -->
 <div class="court-tabs-nav" style="margin-bottom: var(--space-4);">
 <button class="court-tab-btn active" data-court-id="c1">Turf Court 1 (Floodlit)</button>
 <button class="court-tab-btn" data-court-id="c2">Turf Court 2 (Indoor)</button>
 <button class="court-tab-btn" data-court-id="c3">Wooden Badminton Court A</button>
 </div>

 <!-- Availability Grid Container -->
 <div class="availability-container">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 8px;">
 <h3 style="font-size: 16px; margin-bottom: 0;">Select Date & One-Hour Slot</h3>
 <span class="text-xs" style="color: var(--color-text-muted);">Fixed 1-hour increments · Asia/Colombo (UTC+5:30)</span>
 </div>

 <!-- Date Selector Bar -->
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
 <span>Coaching Session</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch blocked"></div>
 <span>Owner Blocked</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch flash"></div>
 <span> Flash Deal (Discounted)</span>
 </div>
 <div class="legend-item">
 <div class="legend-swatch unavailable"></div>
 <span>Unavailable / Past</span>
 </div>
 </div>
 </div>

 <!-- Bottom Selected Slot Summary Bar (Floating or Static) -->
 <div id="slotSelectionSummary" class="card" style="display: none; padding: 16px 24px; border-radius: var(--radius-xl); box-shadow: var(--shadow-elevation); background: var(--color-white); border: 2px solid var(--color-primary); margin-top: var(--space-6); align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
 <div>
 <div class="text-xs" style="color: var(--color-text-muted); text-transform: uppercase; font-weight: 700;">Selected Court & Slot</div>
 <div style="font-size: 16px; font-weight: 800; color: var(--color-text-title);" id="selectedSlotTimeDisplay">
 Sept 24, 2026 | 08:00 PM - 09:00 PM
 </div>
 </div>

 <div style="display: flex; align-items: center; gap: 20px;">
 <div style="text-align: right;">
 <div class="text-xs" style="color: var(--color-text-muted);">Payable Amount</div>
 <div style="font-size: 20px; font-weight: 900; color: var(--color-primary-active);" id="selectedSlotPriceDisplay">
 LKR 5,000
 </div>
 </div>

 <button type="button" class="btn btn-primary btn-lg" id="proceedToCheckoutBtn">
 Proceed to Booking &rarr;
 </button>
 </div>
 </div>

 </div>
</main>

<?php $isGuest = !Auth::check(); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
 // Initialize availability engine — guest mode based on PHP session
 CourtPassAvailability.init({
 courtId: '<?= e($_GET['court'] ?? 'c1') ?>',
 hourlyRate: 5000,
 isGuest: <?= $isGuest ? 'true' : 'false' ?>
 });
});
</script>
