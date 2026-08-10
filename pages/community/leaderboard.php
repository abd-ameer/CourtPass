<?php
/**
 * CourtPass — Most Active This Month Leaderboard Page
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';

$pageTitle = 'Most Active Players Leaderboard';
include __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="page-header__title">🔥 Most Active This Month</h1>
        <p style="opacity: 0.8;">Per-venue leaderboard of top 5 customers by verified attendance this month.</p>
    </div>
</div>

<div class="container" style="padding-bottom: 4rem;">
    <div id="leaderboardContainer">
        <div class="spinner" style="margin: 3rem auto;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('leaderboardContainer');
    try {
        const data = await fetchApi('<?= BASE_URL ?>/api/community/leaderboard');
        
        if (!data.leaderboard || data.leaderboard.length === 0) {
            container.innerHTML = '<div class="card empty-state"><p>No attendance records for this month yet.</p></div>';
            return;
        }

        let html = '<div class="table-wrapper"><table class="table"><thead><tr><th>Rank</th><th>Player</th><th>Venue</th><th>Sessions Completed This Month</th></tr></thead><tbody>';
        data.leaderboard.forEach((item, idx) => {
            const medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
            html += `
                <tr>
                    <td><span style="font-size: 1.25rem;">${medals[idx] || '#'+(idx+1)}</span></td>
                    <td><strong>${escapeHtml(item.customer_name)}</strong></td>
                    <td>${escapeHtml(item.venue_name)}</td>
                    <td><span class="badge badge--success">${item.completed_count} sessions</span></td>
                </tr>
            `;
        });
        html += '</tbody></table></div>';

        container.innerHTML = html;
    } catch (err) {
        container.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
