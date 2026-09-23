<footer style="background: var(--color-white); border-top: 1px solid var(--color-border); margin-top: auto; padding: var(--space-12) 0 var(--space-6);">
    <div class="container">
        <div class="grid grid-cols-4 gap-8" style="margin-bottom: var(--space-8);">

            <!-- Brand & Mission -->
            <div>
                <div style="margin-bottom: var(--space-3);">
                    <img src="<?= asset('images/courtpasslogo.png') ?>" alt="<?= e(APP_NAME) ?>" style="height: 36px; width: auto; object-fit: contain;">
                </div>
                <p style="font-size: var(--font-size-sm); color: var(--color-text-muted); line-height: 1.5;">
                    Sri Lanka's trusted sports venue booking and community ecosystem. Empowering players, venue owners, and certified coaches across Colombo and islandwide.
                </p>
            </div>

            <!-- Sports -->
            <div>
                <h4 style="margin-bottom: var(--space-3); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-title);">Sports Covered</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px; font-size: var(--font-size-sm);">
                    <li><a href="<?= url('/venues?sport=badminton') ?>" style="color: var(--color-text-muted);">Badminton Courts</a></li>
                    <li><a href="<?= url('/venues?sport=futsal') ?>" style="color: var(--color-text-muted);">Futsal Turfs</a></li>
                    <li><a href="<?= url('/venues?sport=pickleball') ?>" style="color: var(--color-text-muted);">Pickleball Arenas</a></li>
                    <li><a href="<?= url('/venues?sport=squash') ?>" style="color: var(--color-text-muted);">Squash Courts</a></li>
                    <li><a href="<?= url('/venues?sport=billiards') ?>" style="color: var(--color-text-muted);">Billiards &amp; Snooker</a></li>
                    <li><a href="<?= url('/venues?sport=table-tennis') ?>" style="color: var(--color-text-muted);">Table Tennis &amp; Carrom</a></li>
                </ul>
            </div>

            <!-- Quick Portals -->
            <div>
                <h4 style="margin-bottom: var(--space-3); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-title);">Platform Portals</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px; font-size: var(--font-size-sm);">
                    <li><a href="<?= url('/customer/dashboard') ?>" style="color: var(--color-text-muted);">Customer Portal</a></li>
                    <li><a href="<?= url('/owner/dashboard') ?>" style="color: var(--color-text-muted);">Venue Owner Dashboard</a></li>
                    <li><a href="<?= url('/coach/dashboard') ?>" style="color: var(--color-text-muted);">Coach Operations &amp; Sessions</a></li>
                    <li><a href="<?= url('/admin/dashboard') ?>" style="color: var(--color-text-muted);">Platform Administration</a></li>
                    <li><a href="<?= url('/help') ?>" style="color: var(--color-text-muted);">Cancellation &amp; Resale Policy</a></li>
                    <li><a href="<?= url('/register-role') ?>" style="color: var(--color-text-muted);">Join as Partner / Coach</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 style="margin-bottom: var(--space-3); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-title);">Contact</h4>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px; font-size: var(--font-size-sm);">
                    <li style="color: var(--color-text-muted);">Colombo, Sri Lanka</li>
                    <li><a href="mailto:support@courtpass.lk" style="color: var(--color-text-muted);">support@courtpass.lk</a></li>
                    <li><a href="<?= url('/help') ?>" style="color: var(--color-text-muted);">Help Centre</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div style="border-top: 1px solid var(--color-border); padding-top: var(--space-4); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; font-size: var(--font-size-xs); color: var(--color-text-muted);">
            <div>
                &copy; <?= date('Y') ?> <?= e(APP_NAME) ?> Sri Lanka. All rights reserved. &nbsp;|&nbsp; <?= e(APP_TIMEZONE) ?> (UTC+5:30)
            </div>
            <div style="display: flex; gap: 16px;">
                <a href="<?= url('/#how-it-works') ?>" style="color: var(--color-text-muted);">Platform Benefits</a>
                <a href="<?= url('/reliability') ?>" style="color: var(--color-text-muted);">Tier Guidelines</a>
                <a href="#" onclick="CourtPassApp.showToast('info', 'PayHere Sandbox', 'Simulated online payment gateway integration enabled.'); return false;" style="color: var(--color-text-muted);">Payment Sandbox</a>
            </div>
        </div>
    </div>
</footer>
