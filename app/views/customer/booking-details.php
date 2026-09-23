<?php
$booking_id = $booking_id ?? $_GET['id'] ?? 'BK-9021';
$is_cash = $is_cash ?? (str_contains($booking_id, '78') || isset($_GET['cash']));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/customer/bookings') ?>">My Bookings</a>
            <span class="breadcrumb-separator">/</span>
            <span><?php echo htmlspecialchars($booking_id); ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <h1 class="page-title">Digital Court Pass</h1>
            <span class="badge badge-confirmed">Confirmed</span>
            <?php if ($is_cash): ?>
                <span class="badge badge-unpaid">Cash on Arrival</span>
            <?php else: ?>
                <span class="badge badge-paid">Online Paid (PayHere)</span>
            <?php endif; ?>
        </div>
        <div class="page-subtitle">Pass Code: <strong><?php echo $is_cash ? 'CP-65201' : 'CP-78190'; ?></strong> · Booking ID: <?php echo htmlspecialchars($booking_id); ?></div>
    </div>

    <div style="display: flex; gap: 8px;">
        <a href="<?= url('/customer/reschedule-booking') ?>?id=<?php echo htmlspecialchars($booking_id); ?>" class="btn btn-outline">
            Reschedule Slot
        </a>
        <a href="<?= url('/customer/cancel-booking') ?>?id=<?php echo htmlspecialchars($booking_id); ?>" class="btn btn-secondary" style="color: var(--color-danger);">
            Cancel / Resell
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
    
    <!-- Left: Digital Ticket Pass -->
    <div>
        <div class="card" style="border: 2px solid var(--color-primary-border); overflow: hidden; margin-bottom: var(--space-6);">
            
            <div style="background: var(--color-primary); color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span class="badge" style="background: rgba(255,255,255,0.25); color: white; margin-bottom: 6px;">Official Digital Pass</span>
                    <h2 style="color: white; font-size: 22px; margin-bottom: 0;">
                        <?php echo $is_cash ? 'CR&FC Badminton Complex' : 'Colombo Futsal Club'; ?>
                    </h2>
                    <div style="font-size: 13px; opacity: 0.9;">
                        <?php echo $is_cash ? 'Court 1 - Yonex Synthetic Rubber Mat' : 'Turf Court 1 (Floodlit 5v5)'; ?>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; text-transform: uppercase; opacity: 0.85;">Pass Status</div>
                    <div style="font-size: 16px; font-weight: 800;">READY TO PLAY</div>
                </div>
            </div>

            <div class="card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; margin-bottom: 24px; border-bottom: 1px dashed var(--color-border); padding-bottom: 20px;">
                    <div>
                        <div class="text-xs text-muted">Reservation Date</div>
                        <div style="font-weight: 700; font-size: 15px;"><?php echo $is_cash ? 'Sept 23, 2026' : 'Sept 24, 2026'; ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Slot Time</div>
                        <div style="font-weight: 700; font-size: 15px; color: var(--color-primary-active);">
                            <?php echo $is_cash ? '06:00 PM - 07:00 PM' : '08:00 PM - 09:00 PM'; ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Total Amount</div>
                        <div style="font-weight: 700; font-size: 15px;"><?php echo $is_cash ? 'LKR 2,800' : 'LKR 5,000'; ?></div>
                    </div>
                    <div>
                        <div class="text-xs text-muted">Customer Name</div>
                        <div style="font-weight: 700; font-size: 15px;">Kasun Jayawardena</div>
                    </div>
                </div>

                <!-- QR Code Check-in Section -->
                <div style="display: flex; align-items: center; gap: 24px; background: var(--color-bg-subtle); padding: 16px 20px; border-radius: var(--radius-xl);">
                    <!-- Simulated QR Code SVG -->
                    <div style="width: 110px; height: 110px; background: white; border: 1px solid var(--color-border); border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 6px; box-shadow: var(--shadow-sm); flex-shrink: 0;">
                        <svg viewBox="0 0 100 100" width="100%" height="100%">
                            <rect width="100" height="100" fill="white" />
                            <!-- QR Marker Top Left -->
                            <rect x="5" y="5" width="30" height="30" fill="black" />
                            <rect x="10" y="10" width="20" height="20" fill="white" />
                            <rect x="15" y="15" width="10" height="10" fill="black" />
                            <!-- QR Marker Top Right -->
                            <rect x="65" y="5" width="30" height="30" fill="black" />
                            <rect x="70" y="10" width="20" height="20" fill="white" />
                            <rect x="75" y="15" width="10" height="10" fill="black" />
                            <!-- QR Marker Bottom Left -->
                            <rect x="5" y="65" width="30" height="30" fill="black" />
                            <rect x="10" y="70" width="20" height="20" fill="white" />
                            <rect x="15" y="75" width="10" height="10" fill="black" />
                            <!-- Random QR Dots -->
                            <rect x="45" y="10" width="10" height="10" fill="black" />
                            <rect x="40" y="25" width="15" height="10" fill="black" />
                            <rect x="15" y="45" width="10" height="10" fill="black" />
                            <rect x="40" y="45" width="20" height="10" fill="black" />
                            <rect x="65" y="45" width="10" height="10" fill="black" />
                            <rect x="45" y="65" width="10" height="15" fill="black" />
                            <rect x="65" y="65" width="25" height="10" fill="black" />
                            <rect x="75" y="80" width="15" height="10" fill="black" />
                        </svg>
                    </div>

                    <div>
                        <h4 style="margin-bottom: 4px;">Venue Check-in Instructions</h4>
                        <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 8px;">
                            Present this QR code or provide booking code <strong><?php echo $is_cash ? 'CP-65201' : 'CP-78190'; ?></strong> at the front desk when arriving at the facility.
                        </p>
                        <div class="text-xs" style="color: #166534; font-weight: 600;">
                            Check-in physically verifies your match, records playtime into your Match History, and boosts your reliability rating.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Right: Actions & Policies -->
    <div>
        <!-- Resale Safeguard Card -->
        <div class="card" style="margin-bottom: var(--space-6);">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Resale Safeguard</h3>
                <span class="badge badge-confirmed">90% Refund</span>
            </div>
            <div class="card-body">
                <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 12px; line-height: 1.5;">
                    Unable to attend? Release your slot back into the standard booking calendar. If another customer books it before session start time, you receive a <strong>90% refund</strong> (10% resale processing fee).
                </p>
                <?php if ($is_cash): ?>
                    <div style="padding: 10px; background: #fef3c7; border-radius: var(--radius-md); font-size: 12px; color: #92400e; margin-bottom: 12px;">
                        <strong>Cash-on-Arrival Conversion Required:</strong> You must convert this booking to online payment before releasing it for resale.
                    </div>
                    <a href="<?= url('/customer/my-resales') ?>?convert=BK-8942" class="btn btn-outline-primary btn-block btn-sm">
                        Convert to Online &amp; Release
                    </a>
                <?php else: ?>
                    <a href="<?= url('/customer/my-resales') ?>?list=BK-9021" class="btn btn-primary btn-block btn-sm">
                        Release Slot for Resale (90% Refund) &rarr;
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cancellation Policy Card -->
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; margin-bottom: 0;">Tiered Cancellation Rules</h3>
            </div>
            <div class="card-body" style="font-size: 12px; display: flex; flex-direction: column; gap: 8px;">
                <div style="padding: 8px; background: #ecfdf5; border-radius: var(--radius-md); border-left: 3px solid var(--color-primary);">
                    <strong>&gt; 48 Hours Before:</strong> 100% Full Simulated Refund.
                </div>
                <div style="padding: 8px; background: #fffbeb; border-radius: var(--radius-md); border-left: 3px solid #f59e0b;">
                    <strong>12 to 48 Hours Before:</strong> 50% Instant Refund or Release for Resale (90% if rebooked).
                </div>
                <div style="padding: 8px; background: #fef2f2; border-radius: var(--radius-md); border-left: 3px solid #ef4444;">
                    <strong>&lt; 12 Hours Before:</strong> Resale release only (no direct refund).
                </div>
            </div>
        </div>

    </div>

</div>
