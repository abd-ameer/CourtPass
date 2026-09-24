<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Help Centre</span>
        </div>
        <h1 style="font-size: var(--font-size-2xl); margin-bottom: 6px;">CourtPass Support &amp; Help Centre</h1>
        <p class="text-sm" style="color: var(--color-text-muted);">
            Frequently asked questions, booking policies, tier calculations, and direct contact details.
        </p>
    </div>
</div>

<section style="padding: var(--space-10) 0;">
    <div class="container" style="max-width: 800px;">
        <div class="card" style="margin-bottom: var(--space-6);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-navy);">How do court bookings and slot locks work?</h3>
            <p class="text-sm" style="color: var(--color-text-main); line-height: 1.6;">
                When you click to reserve an available 1-hour slot, CourtPass creates a real-time lock to prevent conflicting double-bookings. You have 10 minutes to complete payment via PayHere Sandbox, or choose Cash on Arrival if you are in the Standard reliability tier.
            </p>
        </div>

        <div class="card" style="margin-bottom: var(--space-6);" id="reliability">
<h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-navy);">What is the reliability trust system?</h3>
            <p class="text-sm" style="color: var(--color-text-muted); line-height: 1.7; margin-bottom: 12px;">
                Your reliability score is based on your court booking history: completed, checked-in bookings raise it, and no-shows lower it. It is recalculated when you next log in or try to book. Coaching sessions do not affect it.
            </p>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr><th>Tier</th><th>Score</th><th>Payment options</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><?= status_badge('new_member') ?></td><td>Fewer than 5 completed bookings</td><td>Online payment only</td></tr>
                        <tr><td><?= status_badge('restricted') ?></td><td>Below 70%</td><td>Online payment only</td></tr>
                        <tr><td><?= status_badge('standard') ?></td><td>70% and above</td><td>Online payment or cash on arrival</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="margin-bottom: var(--space-6);" id="resale">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-navy);">How does the Slot Resale &amp; 90% Refund process work?</h3>
            <p class="text-sm" style="color: var(--color-text-main); line-height: 1.6; margin-bottom: 10px;">
                There is no separate marketplace for purchasing resold booking slots. When you choose to resell an unwanted booking slot:
            </p>
            <ul style="padding-left: 20px; font-size: 14px; color: var(--color-text-main); line-height: 1.7; margin-bottom: 12px;">
                <li><strong>Standard Booking Availability:</strong> The slot immediately re-enters the standard booking pool and appears on the venue's regular availability calendar for any customer to purchase at standard rates.</li>
                <li><strong>90% Refund upon Successful Rebooking:</strong> You will receive a <strong>90% refund</strong> of your original payment only after another customer successfully completes a booking for that released slot.</li>
                <li><strong>10% Resale Processing Fee:</strong> The remaining 10% is retained by CourtPass as a platform processing fee.</li>
                <li><strong>Unsold Slots:</strong> If no other customer purchases the slot before the session starts, no refund will be issued.</li>
                <li><strong>Revocation:</strong> You can revoke your resale request and keep your booking at any time as long as no other customer has purchased it yet.</li>
            </ul>
        </div>

        <div class="card" style="background: var(--color-primary-light); border: 1px solid var(--color-primary-border);">
            <h3 style="font-size: 17px; margin-bottom: 6px; color: var(--color-navy);">Need direct assistance?</h3>
            <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 16px;">
                Our platform operations team is available daily from 08:00 AM to 10:00 PM (Asia/Colombo).
            </p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="mailto:support@courtpass.lk" class="btn btn-primary btn-sm">Email support@courtpass.lk</a>
                <a href="<?= url('/help#reliability') ?>" class="btn btn-outline btn-sm">View Tier Policy</a>
            </div>
        </div>
    </div>
</section>
