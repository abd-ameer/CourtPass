    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div>
                    <div class="footer__brand">🏟️ <span>CourtPass</span></div>
                    <p class="footer__desc">
                        Sri Lanka's premier indoor sports court booking platform. 
                        Connect with venues, book courts, and join the sporting community.
                    </p>
                </div>
                <div>
                    <h4 class="footer__title">Sports</h4>
                    <ul class="footer__links">
                        <li><a href="<?= BASE_URL ?>/venues?sport=futsal">⚽ Futsal</a></li>
                        <li><a href="<?= BASE_URL ?>/venues?sport=badminton">🏸 Badminton</a></li>
                        <li><a href="<?= BASE_URL ?>/venues?sport=squash">🎾 Squash</a></li>
                        <li><a href="<?= BASE_URL ?>/venues?sport=table_tennis">🏓 Table Tennis</a></li>
                        <li><a href="<?= BASE_URL ?>/venues?sport=billiards">🎱 Billiards</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer__title">Platform</h4>
                    <ul class="footer__links">
                        <li><a href="<?= BASE_URL ?>/venues">Browse Venues</a></li>
                        <li><a href="<?= BASE_URL ?>/flash-deals">Flash Deals</a></li>
                        <li><a href="<?= BASE_URL ?>/resale">Resale Market</a></li>
                        <li><a href="<?= BASE_URL ?>/community/leaderboard">Leaderboard</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer__title">Join Us</h4>
                    <ul class="footer__links">
                        <li><a href="<?= BASE_URL ?>/register">Sign Up as Player</a></li>
                        <li><a href="<?= BASE_URL ?>/register/owner">Register Venue</a></li>
                        <li><a href="<?= BASE_URL ?>/register/coach">Become a Coach</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer__bottom">
                <span>&copy; <?= date('Y') ?> CourtPass — UCSC Group 40 Capstone Project</span>
                <span>Built with ❤️ for Sri Lankan sports</span>
            </div>
        </div>
    </footer>

    <!-- Core JS -->
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>
    <?php if (isset($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?= BASE_URL ?>/assets/js/<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
