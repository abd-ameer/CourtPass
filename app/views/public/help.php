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

<main style="padding: var(--space-10) 0;">
    <div class="container" style="max-width: 800px;">
        <div class="card" style="margin-bottom: var(--space-6);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-navy);">How do court bookings and slot locks work?</h3>
            <p class="text-sm" style="color: var(--color-text-main); line-height: 1.6;">
                When you click to reserve an available 1-hour slot, CourtPass creates a real-time lock to prevent conflicting double-bookings. You have 5 minutes to complete payment via PayHere Sandbox or choose Cash-on-Arrival if your reliability tier qualifies.
            </p>
        </div>

        <div class="card" style="margin-bottom: var(--space-6);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-navy);">What is the Reliability Trust System?</h3>
            <p class="text-sm" style="color: var(--color-text-main); line-height: 1.6;">
                Every registered customer starts in the Standard Tier with a base reliability score (80%). Attending booked games and completing check-ins increases your score and unlocks Cash-on-Arrival privileges. Unreported no-shows deduct 25 points and temporarily restrict booking privileges until resolved.
            </p>
        </div>

        <div class="card" style="margin-bottom: var(--space-6);">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-navy);">How does the Resale Market 90% price cap work?</h3>
            <p class="text-sm" style="color: var(--color-text-main); line-height: 1.6;">
                If you cannot attend a confirmed booking, you may list your slot on the CourtPass Resale Marketplace. To prevent scalping and black-market markup, all resale prices are strictly capped at a maximum of 90% of the original booking fee.
            </p>
        </div>

        <div class="card" style="background: var(--color-primary-light); border: 1px solid var(--color-primary-border);">
            <h3 style="font-size: 17px; margin-bottom: 6px; color: var(--color-navy);">Need direct assistance?</h3>
            <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 16px;">
                Our platform operations team is available daily from 08:00 AM to 10:00 PM (Asia/Colombo).
            </p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="mailto:support@courtpass.lk" class="btn btn-primary btn-sm">Email support@courtpass.lk</a>
                <a href="<?= url('/reliability') ?>" class="btn btn-outline btn-sm">View Tier Policy</a>
            </div>
        </div>
    </div>
</main>
