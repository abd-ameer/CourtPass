<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Released Bookings</span>
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
                    <strong>4. Unsold Slots:</strong> If no other customer books the slot before session start time, no refund is issued. You can take back a release at any time before someone books the slot.
                </div>
            </div>
        </div>
    </div>
</div>

<p class="text-sm" style="color: var(--color-text-muted); margin-bottom: var(--space-6);">
    To release a booking, open it from <a href="<?= url('/customer/bookings') ?>">My Bookings</a> and choose <strong>Release Slot for Resale</strong>.
</p>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 16px; margin-bottom: 0;">Released Bookings</h3>
            <span class="text-xs text-muted">Slots are sold to other customers at standard court pricing through normal booking</span>
        </div>
        
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
                    <td><strong>#5</strong></td>
                    <td>#1</td>
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
                        <?= status_badge('released') ?>
                    </td>
                    <td style="text-align: right;">
                        <form method="POST" action="<?= url('/customer/bookings/5/take-back') ?>" class="inline-form">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-secondary">Take Back</button>
                        </form>
                    </td>
                </tr>

                <!-- Past Resold & 90% Refunded -->
                <tr>
                    <td><strong>#2</strong></td>
                    <td>#1</td>
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
                        <?= status_badge('resold') ?>
                    </td>
                    <td style="text-align: right;">
                        <span class="text-xs text-muted">Refunded to PayHere</span>
                    </td>
                </tr>

                <!-- Past Expired Unsold -->
                <tr>
                    <td><strong>#1</strong></td>
                    <td>#1</td>
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
                        <?= status_badge('completed_unattended') ?>
                    </td>
                    <td style="text-align: right;">
                        <span class="text-xs text-muted">No Refund Issued</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

