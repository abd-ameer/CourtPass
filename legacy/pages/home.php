<?php
/**
 * CourtPass — Home Page
 * Public landing page with hero, featured venues, sport filters, and flash deals.
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

// Fetch approved venues for display
$venues = dbFetchAll(
    "SELECT v.*, u.name as owner_name,
            (SELECT COUNT(*) FROM courts c WHERE c.venue_id = v.id AND c.status = 'active') as court_count,
            (SELECT ROUND(AVG(r.rating), 1) FROM reviews r WHERE r.venue_id = v.id AND r.status = 'active') as avg_rating,
            (SELECT COUNT(*) FROM reviews r WHERE r.venue_id = v.id AND r.status = 'active') as review_count
     FROM venues v
     JOIN users u ON v.owner_id = u.id
     WHERE v.status = 'approved'
     ORDER BY v.created_at DESC
     LIMIT 6"
);

// Fetch active flash deals (not expired)
$flashDeals = dbFetchAll(
    "SELECT fs.*, c.name as court_name, c.sport_type, v.name as venue_name, v.city
     FROM flash_slots fs
     JOIN courts c ON fs.court_id = c.id
     JOIN venues v ON fs.venue_id = v.id
     WHERE fs.status = 'active' 
       AND CONCAT(fs.slot_date, ' ', fs.slot_start) > NOW()
     ORDER BY fs.slot_date ASC, fs.slot_start ASC
     LIMIT 4"
);

// Get platform stats
$stats = dbFetchOne(
    "SELECT 
        (SELECT COUNT(*) FROM venues WHERE status = 'approved') as venue_count,
        (SELECT COUNT(*) FROM courts WHERE status = 'active') as court_count,
        (SELECT COUNT(*) FROM users WHERE role = 'customer' AND status = 'active') as player_count,
        (SELECT COUNT(*) FROM bookings WHERE status = 'completed') as booking_count"
);

$pageTitle = 'Book Indoor Sports Courts in Sri Lanka';
include __DIR__ . '/layouts/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero__content">
            <div class="hero__eyebrow">
                🏆 Sri Lanka's #1 Court Booking Platform
            </div>
            <h1 class="hero__title">
                Book Your <span>Perfect Court</span> in Seconds
            </h1>
            <p class="hero__subtitle">
                Discover and book indoor sports venues across Sri Lanka. 
                Futsal, badminton, pickleball, squash, billiards, carrom, and table tennis — 
                all in one platform with instant confirmation.
            </p>
            <div class="hero__actions">
                <a href="<?= BASE_URL ?>/venues" class="btn btn--primary btn--lg">
                    🔍 Browse Venues
                </a>
                <?php if (!isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/register" class="btn btn--outline-light btn--lg">
                        Create Account
                    </a>
                <?php endif; ?>
            </div>
            <div class="hero__stats">
                <div class="hero__stat">
                    <div class="hero__stat-value"><?= $stats['venue_count'] ?? 0 ?>+</div>
                    <div class="hero__stat-label">Venues</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-value"><?= $stats['court_count'] ?? 0 ?>+</div>
                    <div class="hero__stat-label">Courts</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-value"><?= $stats['player_count'] ?? 0 ?>+</div>
                    <div class="hero__stat-label">Players</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-value"><?= $stats['booking_count'] ?? 0 ?>+</div>
                    <div class="hero__stat-label">Bookings</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sport Filters -->
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Choose Your Sport</span>
            <h2 class="section__title">7 Sports, One Platform</h2>
            <p class="section__subtitle">From futsal pitches to carrom lounges, find the perfect court for your game.</p>
        </div>
        
        <div class="grid grid--4" style="gap: 1.5rem;">
            <?php
            $sports = [
                ['type' => 'futsal', 'icon' => '⚽', 'name' => 'Futsal', 'desc' => 'Indoor football action'],
                ['type' => 'badminton', 'icon' => '🏸', 'name' => 'Badminton', 'desc' => 'Smash & rally'],
                ['type' => 'pickleball', 'icon' => '🏓', 'name' => 'Pickleball', 'desc' => 'Fast-growing sport'],
                ['type' => 'squash', 'icon' => '🎾', 'name' => 'Squash', 'desc' => 'High-intensity rallies'],
                ['type' => 'billiards', 'icon' => '🎱', 'name' => 'Billiards', 'desc' => 'Precision & strategy'],
                ['type' => 'carrom', 'icon' => '🎯', 'name' => 'Carrom', 'desc' => 'Classic board sport'],
                ['type' => 'table_tennis', 'icon' => '🏓', 'name' => 'Table Tennis', 'desc' => 'Quick reflexes'],
            ];
            foreach ($sports as $i => $sport): ?>
                <a href="<?= BASE_URL ?>/venues?sport=<?= $sport['type'] ?>" 
                   class="stat-card" 
                   style="text-decoration:none; animation: fadeInUp 0.4s ease-out <?= $i * 0.05 ?>s both;">
                    <div class="stat-card__icon stat-card__icon--primary" style="font-size: 2rem; width: 56px; height: 56px;">
                        <?= $sport['icon'] ?>
                    </div>
                    <div class="stat-card__value" style="font-size: 1.25rem;"><?= $sport['name'] ?></div>
                    <div class="stat-card__label"><?= $sport['desc'] ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Venues -->
<?php if (!empty($venues)): ?>
<section class="section" style="background: var(--neutral-50);">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Featured Venues</span>
            <h2 class="section__title">Top-Rated Sports Facilities</h2>
            <p class="section__subtitle">Discover the best indoor sports venues across Sri Lanka.</p>
        </div>
        
        <div class="grid grid--3">
            <?php foreach ($venues as $venue): ?>
                <a href="<?= BASE_URL ?>/venue?id=<?= $venue['id'] ?>" class="card" style="text-decoration:none;color:inherit;">
                    <div class="card__image card__image--placeholder">
                        🏟️
                    </div>
                    <div class="card__body">
                        <h3 class="card__title"><?= sanitize($venue['name']) ?></h3>
                        <p class="card__subtitle">
                            📍 <?= sanitize($venue['city']) ?> · 
                            <?= $venue['court_count'] ?> court<?= $venue['court_count'] != 1 ? 's' : '' ?>
                        </p>
                        <p class="text-sm text-secondary" style="margin-bottom: var(--space-3); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= sanitize($venue['description']) ?>
                        </p>
                        <div class="card__meta">
                            <?php if ($venue['avg_rating']): ?>
                                <span class="card__meta-item">
                                    ⭐ <?= $venue['avg_rating'] ?> (<?= $venue['review_count'] ?>)
                                </span>
                            <?php else: ?>
                                <span class="card__meta-item text-muted">No reviews yet</span>
                            <?php endif; ?>
                            <span class="card__meta-item">
                                👤 <?= sanitize($venue['owner_name']) ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= BASE_URL ?>/venues" class="btn btn--outline btn--lg">
                View All Venues →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Flash Deals -->
<?php if (!empty($flashDeals)): ?>
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">⚡ Available Now</span>
            <h2 class="section__title">Flash Deals</h2>
            <p class="section__subtitle">Grab discounted slots before they're gone!</p>
        </div>
        
        <div class="grid grid--4">
            <?php foreach ($flashDeals as $deal): ?>
                <div class="card">
                    <div class="flash-banner" style="margin: var(--space-4) var(--space-4) 0;">
                        ⚡ Flash Deal — 
                        <?= round((1 - $deal['discounted_price'] / $deal['original_price']) * 100) ?>% OFF
                    </div>
                    <div class="card__body">
                        <h4 class="card__title"><?= sanitize($deal['court_name']) ?></h4>
                        <p class="card__subtitle">
                            <?= sportIcon($deal['sport_type']) ?> <?= sportName($deal['sport_type']) ?> · 
                            <?= sanitize($deal['venue_name']) ?>
                        </p>
                        <p class="text-sm">
                            📅 <?= formatDate($deal['slot_date']) ?><br>
                            🕐 <?= formatTime($deal['slot_start']) ?> – <?= formatTime($deal['slot_end']) ?>
                        </p>
                        <p class="text-sm mt-2">
                            📍 <?= sanitize($deal['city']) ?>
                        </p>
                    </div>
                    <div class="card__footer">
                        <div class="card__price">
                            <?= formatCurrency($deal['discounted_price']) ?>
                            <small><del><?= formatCurrency($deal['original_price']) ?></del></small>
                        </div>
                        <?php if (isLoggedIn()): ?>
                            <a href="<?= BASE_URL ?>/book?court=<?= $deal['court_id'] ?>&date=<?= $deal['slot_date'] ?>&flash=<?= $deal['id'] ?>" 
                               class="btn btn--primary btn--sm">Book Now</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/login" class="btn btn--primary btn--sm">Book Now</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="<?= BASE_URL ?>/flash-deals" class="btn btn--outline btn--lg">
                View All Flash Deals →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How It Works -->
<section class="section" style="background: var(--gradient-dark); color: white;">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow" style="color: var(--primary-400);">How It Works</span>
            <h2 class="section__title" style="color: white;">Book a Court in 3 Steps</h2>
        </div>
        
        <div class="grid grid--3">
            <div style="text-align:center; padding: var(--space-6);">
                <div style="width: 72px; height: 72px; margin: 0 auto var(--space-5); background: rgba(16, 185, 129, 0.15); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    🔍
                </div>
                <h3 style="color: white; margin-bottom: var(--space-3);">1. Find a Venue</h3>
                <p style="opacity: 0.7;">Browse venues by location, sport type, or availability. Check ratings and reviews from real players.</p>
            </div>
            <div style="text-align:center; padding: var(--space-6);">
                <div style="width: 72px; height: 72px; margin: 0 auto var(--space-5); background: rgba(59, 130, 246, 0.15); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    📅
                </div>
                <h3 style="color: white; margin-bottom: var(--space-3);">2. Pick a Slot</h3>
                <p style="opacity: 0.7;">Choose your preferred date and time. See real-time availability and grab flash deals for discounted rates.</p>
            </div>
            <div style="text-align:center; padding: var(--space-6);">
                <div style="width: 72px; height: 72px; margin: 0 auto var(--space-5); background: rgba(251, 191, 36, 0.15); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    ✅
                </div>
                <h3 style="color: white; margin-bottom: var(--space-3);">3. Confirm & Play</h3>
                <p style="opacity: 0.7;">Pay securely online or choose cash on arrival (for trusted members). Get instant confirmation and enjoy your game!</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container" style="text-align: center;">
        <h2 style="margin-bottom: var(--space-4);">Ready to Play?</h2>
        <p class="text-lg text-secondary" style="margin-bottom: var(--space-8); max-width: 500px; margin-left: auto; margin-right: auto;">
            Join thousands of players and venue owners on CourtPass. 
            Your next game is just a click away.
        </p>
        <div style="display: flex; gap: var(--space-4); justify-content: center; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/register" class="btn btn--primary btn--lg">
                Sign Up Free →
            </a>
            <a href="<?= BASE_URL ?>/register/owner" class="btn btn--outline btn--lg">
                Register Your Venue
            </a>
            <a href="<?= BASE_URL ?>/register/coach" class="btn btn--accent btn--lg">
                Become a Coach
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/layouts/footer.php'; ?>
