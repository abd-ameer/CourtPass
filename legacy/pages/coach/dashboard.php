<?php
/**
 * CourtPass — Coach Dashboard Page
 * Built to the coach module spec: Session Management, Earnings Summary, Student Overview, Session History, Review Summary.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();
requireRole('coach');

$userId = currentUserId();
$profile = dbFetchOne("SELECT * FROM coach_profiles WHERE user_id = ?", 'i', [$userId]);

$pageTitle = 'Coach Dashboard';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <!-- Coach Banner / Verification Warning -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>🎯 Coach Dashboard</h1>
            <p class="text-secondary">Manage your training sessions, earnings, and students</p>
        </div>
        <div>
            <?php if (!$profile || $profile['verification_status'] !== 'verified'): ?>
                <span class="badge badge--warning">Verification Pending (Upload NIC Photo)</span>
            <?php else: ?>
                <span class="badge badge--success">Verified Coach</span>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!$profile || !$profile['nic_document_path']): ?>
        <div class="alert alert--warning mb-6">
            <strong>Action Required:</strong> Please upload a photo of your NIC document for admin verification before scheduling sessions.
            <form id="nicUploadForm" style="margin-top: 1rem; display:flex; gap: 1rem; align-items:center;" enctype="multipart/form-data">
                <?= csrfField() ?>
                <input type="file" name="nic_file" accept="image/*" class="form-input" style="max-width: 300px;" required>
                <button type="submit" class="btn btn--primary btn--sm">Upload Photo</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- 2. Earnings & Student Summary Cards -->
    <div class="grid grid--4 mb-8">
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--primary">💵</div>
            <div class="stat-card__value" id="monthRevenue">Rs. 0</div>
            <div class="stat-card__label">This Month's Revenue</div>
            <div class="text-xs text-muted mt-1" id="monthSplit">Group: 0 | Private: 0</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--accent">🏆</div>
            <div class="stat-card__value" id="alltimeRevenue">Rs. 0</div>
            <div class="stat-card__label">All-Time Revenue</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--warning">🎓</div>
            <div class="stat-card__value" id="uniqueStudents">0</div>
            <div class="stat-card__label">Unique Students Coached</div>
            <div class="text-xs text-muted mt-1" id="regularsCount">Regulars (3+ sessions): 0</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--danger">⭐</div>
            <div class="stat-card__value" id="avgRating">0.0</div>
            <div class="stat-card__label">Average Rating</div>
            <div class="text-xs text-muted mt-1" id="tagRatingSplit">Parent: - | Player: -</div>
        </div>
    </div>

    <!-- Actions & Create Session Modal Button -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
        <h2>Upcoming Training Sessions</h2>
        <button class="btn btn--primary" onclick="openModal('createSessionModal')">+ Create Session</button>
    </div>

    <!-- 1. Session Management (Upcoming) -->
    <div id="upcomingSessionsContainer" class="mb-8">
        <div class="spinner" style="margin: 2rem auto;"></div>
    </div>

    <!-- 4. Session History -->
    <h2 style="margin-bottom: 1.5rem;">Session History</h2>
    <p class="text-sm text-muted mb-4">Coach Cancellation Rate: <strong id="cancelRateText">0%</strong></p>
    <div id="sessionHistoryContainer">
        <div class="spinner" style="margin: 2rem auto;"></div>
    </div>
</div>

<!-- Create Session Modal -->
<div class="modal-overlay" id="createSessionModal">
    <div class="modal" style="max-width: 600px;">
        <div class="modal__header">
            <h3 class="modal__title">Create Training Session</h3>
            <button class="modal__close" onclick="closeModal('createSessionModal')">&times;</button>
        </div>
        <div class="modal__body">
            <form id="createSessionForm">
                <?= csrfField() ?>
                <div class="form-group">
                    <label class="form-label">Session Title</label>
                    <input type="text" name="title" class="form-input" placeholder="e.g. Advanced Badminton Footwork Masterclass" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Select Approved Court</label>
                        <select name="court_id" id="courtSelect" class="form-select" required>
                            <option value="">Loading courts...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Visibility</label>
                        <select name="visibility" class="form-select">
                            <option value="public">Public (Listed for all players)</option>
                            <option value="private">Private (Shareable token link only)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Session Date</label>
                        <input type="date" name="session_date" class="form-input" min="<?= today() ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Start Time (1 Hour Session)</label>
                        <input type="time" name="session_start" class="form-input" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Student Capacity</label>
                        <input type="number" min="1" name="capacity" class="form-input" value="6" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price per Student (LKR)</label>
                        <input type="number" step="0.01" name="price_per_student" class="form-input" placeholder="2500.00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" placeholder="Session goals, equipment required, etc."></textarea>
                </div>

                <button type="submit" class="btn btn--primary btn--full">Schedule Session</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // NIC Upload
    const nicForm = document.getElementById('nicUploadForm');
    if (nicForm) {
        nicForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(nicForm);
            try {
                const res = await fetchApi('<?= BASE_URL ?>/api/coach/profile?action=upload_nic', { method: 'POST', body: formData });
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) {
                showToast(err.message, 'error');
            }
        });
    }

    async function loadDashboard() {
        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/coach/dashboard');

            // Populate Stat Cards
            document.getElementById('monthRevenue').textContent = `Rs. ${data.earnings.month_total.toFixed(2)}`;
            document.getElementById('monthSplit').textContent = `Group: Rs. ${data.earnings.month_group.toFixed(0)} | Private: Rs. ${data.earnings.month_private.toFixed(0)}`;
            document.getElementById('alltimeRevenue').textContent = `Rs. ${data.earnings.alltime_total.toFixed(2)}`;
            document.getElementById('uniqueStudents').textContent = data.students.total_unique;
            document.getElementById('regularsCount').textContent = `Regulars (3+ sessions): ${data.students.regulars_3plus}`;

            document.getElementById('avgRating').textContent = data.review_summary.overall_avg.toFixed(1);
            document.getElementById('tagRatingSplit').textContent = `Parent: ${data.review_summary.parent_avg}⭐ | Player: ${data.review_summary.player_avg}⭐`;
            document.getElementById('cancelRateText').textContent = `${data.cancellation_rate}%`;

            // Render Upcoming Sessions
            const upContainer = document.getElementById('upcomingSessionsContainer');
            if (!data.upcoming_sessions || data.upcoming_sessions.length === 0) {
                upContainer.innerHTML = '<div class="card empty-state"><p>No upcoming sessions scheduled.</p></div>';
            } else {
                let html = '<div class="grid grid--3">';
                data.upcoming_sessions.forEach(s => {
                    html += `
                        <div class="card">
                            <div class="card__body">
                                <div class="badge ${s.visibility === 'public' ? 'badge--info' : 'badge--warning'} mb-2">
                                    ${s.visibility.toUpperCase()}
                                </div>
                                <h3 class="card__title">${escapeHtml(s.title)}</h3>
                                <p class="card__subtitle">📍 ${escapeHtml(s.venue_name)} (${escapeHtml(s.court_name)})</p>
                                <p class="text-sm">
                                    📅 ${s.session_date}<br>
                                    🕐 ${s.session_start} – ${s.session_end}<br>
                                    👥 Capacity: <strong>${s.registered_count} / ${s.capacity}</strong>
                                </p>
                                <div class="mt-3">
                                    <input type="text" class="form-input text-xs" value="${s.share_link}" readonly onclick="this.select(); document.execCommand('copy'); showToast('Link copied!', 'info');">
                                </div>
                            </div>
                            <div class="card__footer">
                                <div class="card__price">Rs. ${parseFloat(s.price_per_student).toFixed(2)}</div>
                                <button class="btn btn--danger btn--sm cancel-session-btn" data-id="${s.id}">Cancel</button>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                upContainer.innerHTML = html;

                document.querySelectorAll('.cancel-session-btn').forEach(b => {
                    b.addEventListener('click', async () => {
                        if (confirm('Cancel this session? Full refunds will be issued to all registered students.')) {
                            const formData = new FormData();
                            formData.append('session_id', b.dataset.id);
                            formData.append('csrf_token', getCsrfToken());
                            try {
                                const res = await fetchApi('<?= BASE_URL ?>/api/coach/session-cancel', { method: 'POST', body: formData });
                                showToast(res.message, 'success');
                                setTimeout(() => loadDashboard(), 1000);
                            } catch (err) {
                                showToast(err.message, 'error');
                            }
                        }
                    });
                });
            }

            // Render Past Sessions
            const histContainer = document.getElementById('sessionHistoryContainer');
            if (!data.past_sessions || data.past_sessions.length === 0) {
                histContainer.innerHTML = '<div class="card empty-state"><p>No session history yet.</p></div>';
            } else {
                let html = '<div class="table-wrapper"><table class="table"><thead><tr><th>Title</th><th>Venue</th><th>Date</th><th>Registrations</th><th>Status</th></tr></thead><tbody>';
                data.past_sessions.forEach(s => {
                    html += `
                        <tr>
                            <td><strong>${escapeHtml(s.title)}</strong></td>
                            <td>${escapeHtml(s.venue_name)}</td>
                            <td>${s.session_date} (${s.session_start})</td>
                            <td>${s.registered_count} / ${s.capacity}</td>
                            <td><span class="badge ${s.status === 'cancelled' ? 'badge--danger' : 'badge--success'}">${s.status}</span></td>
                        </tr>
                    `;
                });
                html += '</tbody></table></div>';
                histContainer.innerHTML = html;
            }

        } catch (err) {
            showToast(err.message, 'error');
        }
    }

    // Load available courts for modal dropdown
    async function loadApprovedCourts() {
        const select = document.getElementById('courtSelect');
        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/venues/list');
            let html = '<option value="">-- Select Court --</option>';
            data.venues.forEach(v => {
                html += `<optgroup label="${escapeHtml(v.name)} (${escapeHtml(v.city)})">`;
                // Courts fetch if needed or populate
                html += `</optgroup>`;
            });
            select.innerHTML = html;
        } catch (e) {}
    }

    loadDashboard();

    // Create session submission
    document.getElementById('createSessionForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetchApi('<?= BASE_URL ?>/api/coach/session-create', { method: 'POST', body: formData });
            showToast(res.message, 'success');
            closeModal('createSessionModal');
            loadDashboard();
        } catch (err) {
            showToast(err.message, 'error');
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
