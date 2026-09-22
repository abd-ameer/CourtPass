<?php
/**
 * CourtPass — Public Venues Browse Page
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

$selectedSport = getVal('sport');
$selectedCity = getVal('city');

$pageTitle = 'Browse Venues';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-header__title">🏢 Sports Venues in Sri Lanka</h1>
        <p style="opacity: 0.8;">Discover top indoor sports facilities and book court slots online.</p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <!-- Filters Bar -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
        <form id="filterForm" class="flex flex-wrap items-center gap-4">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label" for="search">Search Venue Name</label>
                <input type="text" id="search" name="search" class="form-input" placeholder="e.g. Colombo Sports Hub...">
            </div>

            <div style="width: 180px;">
                <label class="form-label" for="sport">Sport Type</label>
                <select name="sport" id="sportSelect" class="form-select">
                    <option value="">All Sports</option>
                    <option value="futsal" <?= $selectedSport === 'futsal' ? 'selected' : '' ?>>⚽ Futsal</option>
                    <option value="badminton" <?= $selectedSport === 'badminton' ? 'selected' : '' ?>>🏸 Badminton</option>
                    <option value="pickleball" <?= $selectedSport === 'pickleball' ? 'selected' : '' ?>>🏓 Pickleball</option>
                    <option value="squash" <?= $selectedSport === 'squash' ? 'selected' : '' ?>>🎾 Squash</option>
                    <option value="billiards" <?= $selectedSport === 'billiards' ? 'selected' : '' ?>>🎱 Billiards</option>
                    <option value="carrom" <?= $selectedSport === 'carrom' ? 'selected' : '' ?>>🎯 Carrom</option>
                    <option value="table_tennis" <?= $selectedSport === 'table_tennis' ? 'selected' : '' ?>>🏓 Table Tennis</option>
                </select>
            </div>

            <div style="width: 180px;">
                <label class="form-label" for="city">City</label>
                <select name="city" id="citySelect" class="form-select">
                    <option value="">All Cities</option>
                    <option value="Colombo" <?= $selectedCity === 'Colombo' ? 'selected' : '' ?>>Colombo</option>
                    <option value="Kandy" <?= $selectedCity === 'Kandy' ? 'selected' : '' ?>>Kandy</option>
                    <option value="Galle" <?= $selectedCity === 'Galle' ? 'selected' : '' ?>>Galle</option>
                </select>
            </div>

            <div style="align-self: flex-end;">
                <button type="submit" class="btn btn--primary">Filter</button>
            </div>
        </form>
    </div>

    <!-- Venues Grid -->
    <div id="venuesGrid">
        <div class="spinner" style="margin: 3rem auto;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('venuesGrid');
    const form = document.getElementById('filterForm');

    async function loadVenues() {
        grid.innerHTML = '<div class="spinner" style="margin: 3rem auto;"></div>';

        const params = new URLSearchParams(new FormData(form)).toString();

        try {
            const data = await fetchApi(`<?= BASE_URL ?>/api/venues/list?${params}`);
            
            if (!data.venues || data.venues.length === 0) {
                grid.innerHTML = `
                    <div class="card empty-state">
                        <div class="empty-state__icon">🏢</div>
                        <h3 class="empty-state__title">No approved venues found</h3>
                        <p class="empty-state__text">Try adjusting your filters or search terms.</p>
                    </div>
                `;
                return;
            }

            let html = '<div class="grid grid--3">';
            data.venues.forEach(v => {
                html += `
                    <a href="<?= BASE_URL ?>/venue?id=${v.id}" class="card" style="text-decoration:none;color:inherit;">
                        <div class="card__image card__image--placeholder">🏟️</div>
                        <div class="card__body">
                            <h3 class="card__title">${escapeHtml(v.name)}</h3>
                            <p class="card__subtitle">📍 ${escapeHtml(v.city)} · ${v.court_count} courts</p>
                            <p class="text-sm text-secondary mb-3">${escapeHtml(v.description)}</p>
                            <div class="card__meta">
                                <span>${v.avg_rating ? `⭐ ${v.avg_rating} (${v.review_count})` : 'No reviews'}</span>
                                <span>👤 ${escapeHtml(v.owner_name)}</span>
                            </div>
                        </div>
                    </a>
                `;
            });
            html += '</div>';

            grid.innerHTML = html;

        } catch (err) {
            grid.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
        }
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        loadVenues();
    });

    loadVenues();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
