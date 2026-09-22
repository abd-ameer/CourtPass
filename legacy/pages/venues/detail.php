<?php
/**
 * CourtPass — Public Venue Detail Page
 * Features courts listing, active announcements, approved coaches, and reviews with single owner response.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

$venueId = (int)getVal('id');
if (!$venueId) {
    header('Location: ' . BASE_URL . '/venues');
    exit;
}

$pageTitle = 'Venue Detail';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div id="venueDetailContainer">
        <div class="spinner" style="margin: 3rem auto;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('venueDetailContainer');
    const venueId = <?= $venueId ?>;

    try {
        const data = await fetchApi(`<?= BASE_URL ?>/api/venues/detail?id=${venueId}`);
        const v = data.venue;

        let html = `
            <!-- Venue Header -->
            <div style="margin-bottom: 2rem;">
                <div class="text-sm text-muted" style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>/venues">Venues</a> / ${escapeHtml(v.name)}</div>
                <h1>${escapeHtml(v.name)}</h1>
                <p class="text-secondary">📍 ${escapeHtml(v.address)}, ${escapeHtml(v.city)} · 📞 ${escapeHtml(v.phone || 'N/A')}</p>
                <p class="mt-2 text-sm">${escapeHtml(v.description)}</p>
            </div>
        `;

        // Active Announcements
        if (data.announcements && data.announcements.length > 0) {
            html += '<div style="margin-bottom: 2rem;"><h3>📢 Announcements</h3>';
            data.announcements.forEach(a => {
                html += `
                    <div class="alert ${a.type === 'promotional' ? 'alert--info' : 'alert--warning'} mt-2">
                        <div>
                            <strong>[${a.type.toUpperCase()}] ${escapeHtml(a.title)}</strong><br>
                            ${escapeHtml(a.content)}
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        }

        // Courts Listing
        html += '<h2 class="mb-4">Available Courts</h2>';
        if (!data.courts || data.courts.length === 0) {
            html += '<div class="card empty-state mb-8"><p>No active courts configured for this venue yet.</p></div>';
        } else {
            html += '<div class="grid grid--3 mb-8">';
            data.courts.forEach(c => {
                html += `
                    <div class="card">
                        <div class="card__body">
                            <span class="badge badge--sport mb-2">${sportIcon(c.sport_type)} ${sportName(c.sport_type)}</span>
                            <h3 class="card__title">${escapeHtml(c.name)}</h3>
                        </div>
                        <div class="card__footer">
                            <div class="card__price">Rs. ${parseFloat(c.hourly_rate).toFixed(2)}<small>/hr</small></div>
                            <a href="<?= BASE_URL ?>/book?court=${c.id}" class="btn btn--primary btn--sm">Select Court Slot</a>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        }

        // Approved Coaches at Venue
        if (data.coaches && data.coaches.length > 0) {
            html += '<h2 class="mb-4">Approved Coaches at Venue</h2><div class="grid grid--3 mb-8">';
            data.coaches.forEach(co => {
                html += `
                    <div class="card">
                        <div class="card__body">
                            <div class="badge badge--success mb-2">Verified Coach</div>
                            <h3 class="card__title">${escapeHtml(co.coach_name)}</h3>
                            <p class="text-xs text-muted mb-2">${escapeHtml(co.specializations || 'Multi-sport coach')}</p>
                            <p class="text-sm">${escapeHtml(co.bio || '')}</p>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
        }

        // Venue Reviews
        html += '<h2 class="mb-4">Verified Player Reviews</h2>';
        if (!data.reviews || data.reviews.length === 0) {
            html += '<div class="card empty-state"><p>No verified player reviews yet.</p></div>';
        } else {
            html += '<div class="grid grid--2">';
            data.reviews.forEach(r => {
                html += `
                    <div class="card" style="padding: 1.5rem;">
                        <div class="flex items-center justify-between mb-2">
                            <strong>${escapeHtml(r.customer_name)}</strong>
                            <div class="stars">${'⭐'.repeat(r.rating)}</div>
                        </div>
                        <p class="text-sm text-secondary">${escapeHtml(r.comment)}</p>
                        ${r.owner_response ? `
                            <div style="background: var(--neutral-50); padding: 0.75rem; border-radius: var(--radius-md); margin-top: 0.75rem; border-left: 3px solid var(--primary-500);">
                                <p class="text-xs font-semibold">Owner Response:</p>
                                <p class="text-xs text-secondary">${escapeHtml(r.owner_response)}</p>
                            </div>
                        ` : ''}
                    </div>
                `;
            });
            html += '</div>';
        }

        container.innerHTML = html;

    } catch (err) {
        container.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
