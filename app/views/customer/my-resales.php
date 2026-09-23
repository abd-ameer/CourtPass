<?php
$list_booking = $list_booking ?? $_GET['list'] ?? '';
$convert_booking = $convert_booking ?? $_GET['convert'] ?? '';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>My Resale Requests</span>
        </div>
        <h1 class="page-title">Slot Resale &amp; Refund Management</h1>
        <div class="page-subtitle">Release unwanted booking slots back into the standard booking pool to receive a 90% refund when rebooked.</div>
    </div>

    <a href="<?= url('/customer/bookings') ?>" class="btn btn-outline">
        View All Bookings &rarr;
    </a>
</div>

<!-- Resale Policy & Process Information Banner -->
<div class="card" style="margin-bottom: var(--space-6); background: #f0fdf4; border: 1.5px solid #86efac; padding: var(--space-5);">
    <div style="display: flex; gap: 14px; align-items: flex-start;">
        <div style="width: 36px; height: 36px; border-radius: 50%; background: #16a34a; color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; font-weight: bold;">
            ℹ️
        </div>
        <div>
            <h3 style="font-size: 15px; font-weight: 700; color: #14532d; margin-bottom: 6px;">How the Ticket Resale Process Works</h3>
            <div style="font-size: 13px; color: #166534; line-height: 1.6; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 8px;">
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>1. Re-enters Standard Booking:</strong> Your released slot immediately becomes available for other customers through the regular venue booking flow at standard rates.
                </div>
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>2. 90% Refund on Rebooking:</strong> When another customer successfully purchases the released slot, you will receive a <strong>90% refund</strong> of your original payment.
                </div>
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>3. 10% Platform Fee:</strong> The remaining <strong>10% is retained</strong> by CourtPass as a resale processing fee.
                </div>
                <div style="background: white; padding: 10px 14px; border-radius: var(--radius-md); border: 1px solid #bbf7d0;">
                    <strong>4. Unsold Slots:</strong> If no other customer books the slot before session start time, no refund is issued. You may revoke your resale request at any time before purchase.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Release Booking for Resale Form Card -->
<div class="card" style="padding: var(--space-6); margin-bottom: var(--space-8); border: 1.5px solid var(--color-primary-border);">
    <h3 style="font-size: 16px; margin-bottom: 12px; color: var(--color-navy);">Release a Confirmed Booking for Resale</h3>
    
    <form id="createResaleForm" onsubmit="handleResaleListing(event)">
        <div style="display: grid; grid-template-columns: 2fr 1.5fr auto; gap: 16px; align-items: flex-end;">
            
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Select Eligible Booking <span class="required-star">*</span></label>
                <select class="form-select" id="resaleBookingSelect" onchange="updateBookingData(this)" required>
                    <option value="">Choose confirmed booking to release...</option>
                    <option value="BK-9021" data-orig="5000" data-paid="online" data-venue="Colombo Futsal Club" data-slot="Sept 24, 08:00 PM" <?php if($list_booking==='BK-9021') echo 'selected'; ?>>
                        BK-9021: Colombo Futsal Club (Sept 24, 08:00 PM) - LKR 5,000 [Online Paid]
                    </option>
                    <option value="BK-8942" data-orig="2800" data-paid="cash" data-venue="CR&FC Badminton Complex" data-slot="Sept 23, 06:00 PM" <?php if($convert_booking==='BK-8942') echo 'selected'; ?>>
                        BK-8942: CR&FC Badminton (Sept 23, 06:00 PM) - LKR 2,800 [Cash on Arrival]
                    </option>
                </select>
            </div>

            <!-- Calculated Financial Breakdown -->
            <div style="background: var(--color-bg-subtle); padding: 8px 14px; border-radius: var(--radius-md); border: 1px solid var(--color-border); font-size: 12px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                    <span class="text-muted">Original Amount:</span>
                    <strong id="displayOrigPrice">LKR 5,000</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                    <span style="color: #166534; font-weight: 600;">90% Refund on Sale:</span>
                    <strong style="color: #166534;" id="displayRefundAmount">LKR 4,500</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span class="text-muted">10% Platform Fee:</span>
                    <span class="text-muted" id="displayFeeAmount">LKR 500</span>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-block" id="listResaleBtn" style="height: 42px; white-space: nowrap;">
                    Release Slot to Booking Pool &rarr;
                </button>
            </div>

        </div>

        <!-- Cash-on-Arrival conversion warning (UC-CU-31) -->
        <div id="cashConversionBanner" style="display: <?php echo ($convert_booking==='BK-8942') ? 'block' : 'none'; ?>; margin-top: 14px; padding: 12px 16px; background: #fffbeb; border: 1px solid #fde68a; border-radius: var(--radius-md); font-size: 12px; color: #92400e;">
            <strong>Online Conversion Required:</strong> Booking BK-8942 is currently reserved as Cash on Arrival. To release this slot for resale and qualify for the 90% contingent refund, you must first convert it to online payment via PayHere Sandbox (LKR 2,800).
        </div>
    </form>
</div>

<!-- Resale Requests & Released Slots Table -->
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 16px; margin-bottom: 0;">My Resale Requests &amp; Status</h3>
            <span class="text-xs text-muted">Slots are sold to other customers at standard court pricing through normal booking</span>
        </div>
        <span class="badge badge-confirmed">Escrow Protected</span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Request Ref</th>
                    <th>Booking ID</th>
                    <th>Venue &amp; Slot</th>
                    <th>Original Amount</th>
                    <th>Potential 90% Refund</th>
                    <th>10% Resale Fee</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Active Listing Live in Standard Booking Pool -->
                <tr>
                    <td><strong>RS-401</strong></td>
                    <td>BK-7782</td>
                    <td>
                        <strong>Colombo Futsal Club</strong>
                        <div class="text-xs text-muted">Turf Court 2 · Sept 24, 09:00 PM - 10:00 PM</div>
                    </td>
                    <td>LKR 4,500</td>
                    <td>
                        <strong style="color: #166534;">LKR 4,050</strong>
                        <span class="badge badge-flash" style="font-size: 10px; margin-left: 4px;">90%</span>
                    </td>
                    <td class="text-xs text-muted">LKR 450</td>
                    <td>
                        <span class="badge badge-pending" title="Available for anyone to book on the court page">Live in Standard Booking Pool</span>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="revokeListing('RS-401')">
                            Revoke &amp; Keep Slot
                        </button>
                    </td>
                </tr>

                <!-- Past Resold & 90% Refunded -->
                <tr>
                    <td><strong>RS-390</strong></td>
                    <td>BK-7501</td>
                    <td>
                        <strong>CR&amp;FC Badminton Complex</strong>
                        <div class="text-xs text-muted">Court 1 · Sept 20, 06:00 PM - 07:00 PM</div>
                    </td>
                    <td>LKR 2,800</td>
                    <td>
                        <strong style="color: #166534;">LKR 2,520</strong>
                    </td>
                    <td class="text-xs text-muted">LKR 280</td>
                    <td>
                        <span class="badge badge-confirmed">Resold &amp; 90% Refunded</span>
                    </td>
                    <td style="text-align: right;">
                        <span class="text-xs text-muted">Refunded to PayHere</span>
                    </td>
                </tr>

                <!-- Past Expired Unsold -->
                <tr>
                    <td><strong>RS-382</strong></td>
                    <td>BK-7210</td>
                    <td>
                        <strong>ProPickle Arena Colombo</strong>
                        <div class="text-xs text-muted">Pickleball Court 2 · Sept 18, 05:00 PM - 06:00 PM</div>
                    </td>
                    <td>LKR 3,000</td>
                    <td>
                        <span class="text-muted">LKR 0 (Unsold)</span>
                    </td>
                    <td class="text-xs text-muted">LKR 0</td>
                    <td>
                        <span class="badge badge-secondary" title="Slot was not purchased before match start time">Expired Unsold</span>
                    </td>
                    <td style="text-align: right;">
                        <span class="text-xs text-muted">No Refund Issued</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('resaleBookingSelect');
    if (select.value) {
        updateBookingData(select);
    }
});

function updateBookingData(select) {
    const selectedOpt = select.options[select.selectedIndex];
    if (!selectedOpt || !selectedOpt.value) return;

    const orig = parseFloat(selectedOpt.dataset.orig || 5000);
    const isPaidCash = selectedOpt.dataset.paid === 'cash';
    const refundAmount = Math.round(orig * 0.90);
    const feeAmount = orig - refundAmount;

    document.getElementById('displayOrigPrice').textContent = `LKR ${orig.toLocaleString()}`;
    document.getElementById('displayRefundAmount').textContent = `LKR ${refundAmount.toLocaleString()}`;
    document.getElementById('displayFeeAmount').textContent = `LKR ${feeAmount.toLocaleString()}`;

    const cashBanner = document.getElementById('cashConversionBanner');
    const listBtn = document.getElementById('listResaleBtn');

    if (isPaidCash) {
        cashBanner.style.display = 'block';
        listBtn.textContent = 'Convert to Online & Release →';
    } else {
        cashBanner.style.display = 'none';
        listBtn.textContent = 'Release Slot to Booking Pool →';
    }
}

function handleResaleListing(e) {
    e.preventDefault();
    const select = document.getElementById('resaleBookingSelect');
    if (!select.value) {
        CourtPassApp.showToast('warning', 'Selection Required', 'Please choose a confirmed booking to release for resale.');
        return;
    }

    const selectedOpt = select.options[select.selectedIndex];
    const isCash = selectedOpt.dataset.paid === 'cash';
    const orig = parseFloat(selectedOpt.dataset.orig || 5000);
    const refundAmount = Math.round(orig * 0.90);

    if (isCash) {
        CourtPassApp.confirmDialog(
            'Convert to Online Payment',
            `This booking must be paid online (LKR ${orig.toLocaleString()}) via PayHere Sandbox before it can be released for resale. Proceed?`,
            'Pay & Release for Resale',
            function() {
                CourtPassApp.showToast('success', 'Conversion & Slot Released', `Booking converted to online payment. Slot is now live in the standard booking calendar. You will receive LKR ${refundAmount.toLocaleString()} (90%) once rebooked.`);
                setTimeout(() => window.location.reload(), 1400);
            }
        );
    } else {
        CourtPassApp.confirmDialog(
            'Release Slot for Resale',
            `Releasing this slot makes it immediately available in the standard booking system for all customers. If another player purchases it, you will receive a 90% refund (LKR ${refundAmount.toLocaleString()}). If it remains unsold, no refund will be issued. Proceed?`,
            'Yes, Release Slot',
            function() {
                CourtPassApp.showToast('success', 'Slot Released for Resale', `Slot is now live in the standard booking calendar. You will receive 90% refund (LKR ${refundAmount.toLocaleString()}) upon successful purchase by another customer.`);
                setTimeout(() => window.location.reload(), 1400);
            }
        );
    }
}

function revokeListing(listingId) {
    CourtPassApp.confirmDialog(
        'Revoke Resale Request',
        `Are you sure you want to withdraw ${listingId}? The slot will be removed from standard availability and returned to your confirmed bookings with no penalty.`,
        'Yes, Revoke Request',
        function() {
            CourtPassApp.showToast('success', 'Resale Revoked', 'Slot removed from standard booking pool. Your reservation is fully confirmed.');
            setTimeout(() => window.location.reload(), 1200);
        }
    );
}
</script>
