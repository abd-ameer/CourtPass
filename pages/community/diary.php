<?php
/**
 * CourtPass — Sports Diary Page (Customer-facing)
 * All data derived/aggregated from booking + check-in data, not separately entered.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();
requireRole('customer');

$pageTitle = 'My Sports Diary';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div style="margin-bottom: 2rem;">
        <h1>📔 My Sports Diary</h1>
        <p class="text-secondary">Your personal playing statistics and sporting activity history</p>
    </div>

    <!-- Aggregated Summary Cards -->
    <div class="grid grid--4 mb-8">
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--primary">⚽</div>
            <div class="stat-card__value" id="totalSessions">0</div>
            <div class="stat-card__label">Total Sessions Played</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--accent">🏢</div>
            <div class="stat-card__value" id="venuesVisited">0</div>
            <div class="stat-card__label">Unique Venues Visited</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--warning">🔥</div>
            <div class="stat-card__value" id="mostActiveMonth" style="font-size: 1.25rem;">-</div>
            <div class="stat-card__label">Most Active Month</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--danger">📅</div>
            <div class="stat-card__value" id="memberSince" style="font-size: 1.1rem;">-</div>
            <div class="stat-card__label">Member Since</div>
        </div>
    </div>

    <!-- Sport-Type Breakdown -->
    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1.5rem;">Sport Type Breakdown</h3>
        <div id="breakdownContainer">
            <div class="spinner" style="margin: 2rem auto;"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const data = await fetchApi('<?= BASE_URL ?>/api/community/diary');
        const d = data.diary;

        document.getElementById('totalSessions').textContent = d.total_sessions_played;
        document.getElementById('venuesVisited').textContent = d.venues_visited;
        document.getElementById('mostActiveMonth').textContent = d.most_active_month;
        document.getElementById('memberSince').textContent = d.member_since;

        const container = document.getElementById('breakdownContainer');
        if (!d.sport_breakdown || d.sport_breakdown.length === 0) {
            container.innerHTML = '<p class="text-muted">No completed sports sessions yet. Book a court to start your diary!</p>';
        } else {
            let html = '<div class="grid grid--3">';
            d.sport_breakdown.forEach(item => {
                html += `
                    <div class="stat-card" style="background: var(--neutral-50);">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">${sportIcon(item.sport_type)}</div>
                        <div class="stat-card__value">${item.session_count}</div>
                        <div class="stat-card__label">${sportName(item.sport_type)} Sessions</div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }

    } catch (err) {
        showToast(err.message, 'error');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
