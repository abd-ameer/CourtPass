<?php
/**
 * CourtPass — Owner Dashboard Page
 * Features Chart.js court utilization heatmap & customer intelligence profile review modal.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();
requireRole('owner');

$ownerId = currentUserId();

// Fetch owner's venues
$venues = dbFetchAll("SELECT * FROM venues WHERE owner_id = ?", 'i', [$ownerId]);

// Fetch pending booking requests for owner's venues
$pendingBookings = dbFetchAll(
    "SELECT b.*, c.name as court_name, c.sport_type, v.name as venue_name, v.id as venue_id, u.name as customer_name, u.email as customer_email
     FROM bookings b
     JOIN courts c ON b.court_id = c.id
     JOIN venues v ON c.venue_id = v.id
     JOIN users u ON b.customer_id = u.id
     WHERE v.owner_id = ? AND b.status = 'pending'
     ORDER BY b.created_at ASC",
    'i',
    [$ownerId]
);

// Revenue stats
$revenue = dbFetchOne(
    "SELECT SUM(b.amount) as total_revenue
     FROM bookings b
     JOIN courts c ON b.court_id = c.id
     JOIN venues v ON c.venue_id = v.id
     WHERE v.owner_id = ? AND b.status IN ('confirmed', 'completed')",
    'i',
    [$ownerId]
);

$pageTitle = 'Owner Dashboard';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Include Chart.js (Sanctioned third-party library for owner dashboard charts) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>📊 Venue Owner Dashboard</h1>
            <p class="text-secondary">Overview of your venues, booking requests, and court utilization</p>
        </div>
        <button class="btn btn--primary" onclick="openModal('addVenueModal')">+ Add New Venue</button>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid--3 mb-8">
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--primary">🏢</div>
            <div class="stat-card__value"><?= count($venues) ?></div>
            <div class="stat-card__label">Registered Venues</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--accent">💵</div>
            <div class="stat-card__value">Rs. <?= number_format((float)($revenue['total_revenue'] ?? 0), 2) ?></div>
            <div class="stat-card__label">Total Revenue Earned</div>
        </div>

        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--warning">⏳</div>
            <div class="stat-card__value"><?= count($pendingBookings) ?></div>
            <div class="stat-card__label">Pending Booking Requests</div>
        </div>
    </div>

    <!-- Pending Booking Requests & Customer Intelligence Profile -->
    <h2 style="margin-bottom: 1.5rem;">Pending Booking Requests</h2>
    <?php if (empty($pendingBookings)): ?>
        <div class="card empty-state mb-8"><p>No pending booking requests to review.</p></div>
    <?php else: ?>
        <div class="table-wrapper mb-8">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Customer</th>
                        <th>Venue & Court</th>
                        <th>Date & Time</th>
                        <th>Amount</th>
                        <th>Customer Intelligence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingBookings as $pb): ?>
                        <tr>
                            <td><strong>#<?= $pb['id'] ?></strong></td>
                            <td>
                                <div><strong><?= sanitize($pb['customer_name']) ?></strong></div>
                                <div class="text-xs text-muted"><?= sanitize($pb['customer_email']) ?></div>
                            </td>
                            <td><?= sanitize($pb['venue_name']) ?> (<?= sanitize($pb['court_name']) ?>)</td>
                            <td><?= formatDate($pb['slot_date']) ?> (<?= formatTime($pb['slot_start']) ?>)</td>
                            <td><strong><?= formatCurrency($pb['amount']) ?></strong></td>
                            <td>
                                <button class="btn btn--outline btn--sm view-intel-btn" 
                                        data-customer="<?= $pb['customer_id'] ?>" 
                                        data-venue="<?= $pb['venue_id'] ?>">
                                    🔍 View Intelligence
                                </button>
                            </td>
                            <td>
                                <button class="btn btn--primary btn--sm confirm-booking-btn" data-id="<?= $pb['id'] ?>">Confirm</button>
                                <button class="btn btn--danger btn--sm reject-booking-btn" data-id="<?= $pb['id'] ?>">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Court Utilisation Heatmap (Chart.js) -->
    <div class="card" style="padding: 2rem; margin-bottom: 2rem;">
        <h3>Court Utilisation Heatmap (Peak Hours Analysis)</h3>
        <p class="text-sm text-muted mb-4">Visualizing booking volume by hour of day</p>
        <div style="height: 320px;">
            <canvas id="utilizationChart"></canvas>
        </div>
    </div>
</div>

<!-- Customer Intelligence Modal -->
<div class="modal-overlay" id="intelModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Customer Intelligence Profile</h3>
            <button class="modal__close" onclick="closeModal('intelModal')">&times;</button>
        </div>
        <div class="modal__body" id="intelModalBody">
            <div class="spinner" style="margin: 2rem auto;"></div>
        </div>
    </div>
</div>

<!-- Add Venue Modal -->
<div class="modal-overlay" id="addVenueModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Register New Venue</h3>
            <button class="modal__close" onclick="closeModal('addVenueModal')">&times;</button>
        </div>
        <div class="modal__body">
            <form id="addVenueForm">
                <?= csrfField() ?>
                <div class="form-group">
                    <label class="form-label">Venue Name</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Apex Sports Center" required>
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-input" placeholder="e.g. Colombo" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Full Address</label>
                    <input type="text" name="address" class="form-input" placeholder="Address..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-input" placeholder="077..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea"></textarea>
                </div>
                <button type="submit" class="btn btn--primary btn--full">Submit Venue for Approval</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Add Venue
    document.getElementById('addVenueForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetchApi('<?= BASE_URL ?>/api/venues/create', { method: 'POST', body: formData });
            showToast(res.message, 'success');
            closeModal('addVenueModal');
            setTimeout(() => location.reload(), 1000);
        } catch (err) {
            showToast(err.message, 'error');
        }
    });

    // Customer Intelligence View
    document.querySelectorAll('.view-intel-btn').forEach(b => {
        b.addEventListener('click', async () => {
            const customerId = b.dataset.customer;
            const venueId = b.dataset.venue;
            const body = document.getElementById('intelModalBody');
            openModal('intelModal');
            body.innerHTML = '<div class="spinner" style="margin:2rem auto;"></div>';

            try {
                const data = await fetchApi(`<?= BASE_URL ?>/api/dashboard/customer?customer_id=${customerId}&venue_id=${venueId}`);
                const c = data.customer;
                const r = data.reliability;
                const v = data.venue_history;

                body.innerHTML = `
                    <div style="margin-bottom: 1.5rem;">
                        <h4>${escapeHtml(c.name)}</h4>
                        <p class="text-xs text-muted">${escapeHtml(c.email)} · ${escapeHtml(c.phone || 'No phone')}</p>
                    </div>

                    <div class="alert alert--info mb-4">
                        <strong>Platform Reliability Tier:</strong> ${escapeHtml(r.display_tier)}<br>
                        <strong>Score:</strong> ${r.display_score}<br>
                        <strong>Total Completed Bookings:</strong> ${r.completed_bookings}<br>
                        <strong>Total No-Shows:</strong> ${r.no_shows}
                    </div>

                    <div style="background: var(--neutral-50); padding: 1rem; border-radius: var(--radius-lg);">
                        <h5 style="margin-bottom: 0.5rem;">History at Your Venue</h5>
                        <p class="text-sm">Total Bookings Here: <strong>${v.total_bookings}</strong></p>
                        <p class="text-sm">Completed Here: <strong>${v.completed_count}</strong></p>
                        <p class="text-sm">No-Shows Here: <strong>${v.noshow_count}</strong></p>
                        <p class="text-sm">Last Visit: <strong>${v.last_visit}</strong></p>
                    </div>
                `;
            } catch (err) {
                body.innerHTML = `<div class="alert alert--error">${err.message}</div>`;
            }
        });
    });

    // Confirm / Reject booking
    document.querySelectorAll('.confirm-booking-btn').forEach(b => {
        b.addEventListener('click', async () => {
            const formData = new FormData();
            formData.append('booking_id', b.dataset.id);
            formData.append('action', 'confirm');
            formData.append('csrf_token', getCsrfToken());
            try {
                const res = await fetchApi('<?= BASE_URL ?>/api/bookings/confirm', { method: 'POST', body: formData });
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) { showToast(err.message, 'error'); }
        });
    });

    document.querySelectorAll('.reject-booking-btn').forEach(b => {
        b.addEventListener('click', async () => {
            const reason = prompt('Mandatory reason for rejecting this booking request:');
            if (reason) {
                const formData = new FormData();
                formData.append('booking_id', b.dataset.id);
                formData.append('action', 'reject');
                formData.append('reason', reason);
                formData.append('csrf_token', getCsrfToken());
                try {
                    const res = await fetchApi('<?= BASE_URL ?>/api/bookings/confirm', { method: 'POST', body: formData });
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } catch (err) { showToast(err.message, 'error'); }
            }
        });
    });

    // Chart.js Court Utilisation Chart Initialization
    async function initChart() {
        try {
            const data = await fetchApi('<?= BASE_URL ?>/api/dashboard/heatmap');
            const ctx = document.getElementById('utilizationChart').getContext('2d');
            
            const hours = ['8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM', '8 PM', '9 PM'];
            const bookingCounts = new Array(14).fill(0);

            data.heatmap.forEach(item => {
                const h = parseInt(item.slot_hour);
                if (h >= 8 && h <= 21) {
                    bookingCounts[h - 8] += parseInt(item.booking_count);
                }
            });

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Total Bookings',
                        data: bookingCounts,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        } catch (e) {}
    }

    initChart();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
