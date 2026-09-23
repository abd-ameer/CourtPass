<?php
$list_booking = $list_booking ?? $_GET['list'] ?? '';
$convert_booking = $convert_booking ?? $_GET['convert'] ?? '';
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/customer/resale') ?>">Resale Market</a>
 <span class="breadcrumb-separator">/</span>
 <span>My Listings</span>
 </div>
 <h1 class="page-title">Manage My Resale Listings</h1>
 <div class="page-subtitle">List unwanted bookings at up to 90% price cap or revoke unsold passes.</div>
 </div>

 <a href="<?= url('/customer/resale') ?>" class="btn btn-outline">
 View Public Marketplace &rarr;
 </a>
 </div>

 <!-- Create New Resale Listing Form Card -->
 <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-8); border: 1.5px solid var(--color-primary-border);">
 <h3 style="font-size: 16px; margin-bottom: 12px;">List a Booking for Resale</h3>
 
 <form id="createResaleForm" onsubmit="handleResaleListing(event)">
 <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 16px; align-items: flex-end;">
 
 <div class="form-group" style="margin-bottom: 0;">
 <label class="form-label">Select Eligible Booking <span class="required-star">*</span></label>
 <select class="form-select" id="resaleBookingSelect" onchange="updateBookingData(this)" required>
 <option value="">Choose confirmed booking...</option>
 <option value="BK-9021" data-orig="5000" data-paid="online" <?php if($list_booking==='BK-9021') echo 'selected'; ?>>
 BK-9021: Colombo Futsal Club (Sept 24, 08:00 PM) - Orig LKR 5,000 [Online Paid]
 </option>
 <option value="BK-8942" data-orig="2800" data-paid="cash" <?php if($convert_booking==='BK-8942') echo 'selected'; ?>>
 BK-8942: CR&FC Badminton (Sept 23, 06:00 PM) - Orig LKR 2,800 [Cash on Arrival]
 </option>
 </select>
 </div>

 <div class="form-group" style="margin-bottom: 0;">
 <label class="form-label">
 Resale Price (LKR) <span class="required-star">*</span>
 <span class="badge badge-flash" id="priceCapBadge" style="margin-left: 4px; font-size: 10px;">Max 90%: LKR 4,500</span>
 </label>
 <input type="number" name="resale_price" id="resalePriceInput" class="form-control" value="4200" min="500" max="4500" required data-original-price="5000">
 <span class="form-feedback invalid"></span>
 </div>

 <div>
 <button type="submit" class="btn btn-primary btn-block" id="listResaleBtn" style="height: 42px;">
 Publish Listing
 </button>
 </div>

 </div>

 <!-- Cash-on-Arrival conversion warning (UC-CU-31) -->
 <div id="cashConversionBanner" style="display: <?php echo ($convert_booking==='BK-8942') ? 'block' : 'none'; ?>; margin-top: 14px; padding: 12px 16px; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-md); font-size: 12px; color: #92400e;">
 <strong>Conversion Required (UC-CU-31):</strong> Booking BK-8942 is currently marked as Cash on Arrival. Per platform rules, you must pay the original LKR 2,800 online via PayHere Sandbox before it can be listed for community resale.
 </div>
 </form>
 </div>

 <!-- Active Resale Listings Table -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">My Active Listings (1)</h3>
 <span class="text-xs text-muted">Unsold listings can be revoked before slot time with no penalty</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Listing ID</th>
 <th>Booking Ref</th>
 <th>Venue & Slot</th>
 <th>Original Rate</th>
 <th>Listing Price</th>
 <th>Status</th>
 <th style="text-align: right;">Action</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><strong>RS-401</strong></td>
 <td>BK-7782</td>
 <td>
 <strong>Colombo Futsal Club</strong>
 <div class="text-xs text-muted">Turf Court 2 · Sept 24, 09:00 PM - 10:00 PM</div>
 </td>
 <td>LKR 4,500</td>
 <td>
 <strong style="color: var(--color-primary-active);">LKR 3,800</strong>
 <span class="badge badge-flash" style="font-size: 10px; margin-left: 4px;">16% OFF</span>
 </td>
 <td>
 <span class="badge badge-pending">Active on Market</span>
 </td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="revokeListing('RS-401')">
 Revoke Listing
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 CourtPassApp.setupFormValidation('createResaleForm');
});

function updateBookingData(select) {
 const selectedOpt = select.options[select.selectedIndex];
 const orig = parseFloat(selectedOpt.dataset.orig || 5000);
 const isPaidCash = selectedOpt.dataset.paid === 'cash';
 const maxPrice = Math.floor(orig * 0.90);

 const priceInput = document.getElementById('resalePriceInput');
 const priceCapBadge = document.getElementById('priceCapBadge');
 const cashBanner = document.getElementById('cashConversionBanner');
 const listBtn = document.getElementById('listResaleBtn');

 priceInput.dataset.originalPrice = orig;
 priceInput.max = maxPrice;
 priceInput.value = maxPrice;
 priceCapBadge.textContent = `Max 90%: LKR ${maxPrice.toLocaleString()}`;

 if (isPaidCash) {
 cashBanner.style.display = 'block';
 listBtn.textContent = 'Convert to Online & List →';
 } else {
 cashBanner.style.display = 'none';
 listBtn.textContent = 'Publish Listing →';
 }
}

function handleResaleListing(e) {
 e.preventDefault();
 const select = document.getElementById('resaleBookingSelect');
 const isCash = select.options[select.selectedIndex].dataset.paid === 'cash';

 if (isCash) {
 CourtPassApp.confirmDialog(
 'Convert to Online Payment',
 'This booking must be paid online via PayHere Sandbox before publishing to the marketplace. Proceed with simulated LKR 2,800 payment?',
 'Pay & Publish',
 function() {
 CourtPassApp.showToast('success', 'Conversion & Listing Successful', 'Booking converted to online payment and listed on the Resale Marketplace.');
 setTimeout(() => window.location.reload(), 1200);
 }
 );
 } else {
 CourtPassApp.showToast('success', 'Resale Listing Created', 'Booking listed at 90% capped price. Other customers can now purchase your ticket.');
 setTimeout(() => window.location.reload(), 1200);
 }
}

function revokeListing(listingId) {
 CourtPassApp.confirmDialog(
 'Revoke Resale Listing (UC-CU-32)',
 `Are you sure you want to withdraw ${listingId} from the marketplace? The booking will immediately return to your Confirmed bookings list with no penalty.`,
 'Yes, Revoke Listing',
 function() {
 CourtPassApp.showToast('success', 'Listing Revoked', 'Listing withdrawn from market. The booking has returned to Confirmed status.');
 setTimeout(() => window.location.reload(), 1200);
 }
 );
}
</script>
