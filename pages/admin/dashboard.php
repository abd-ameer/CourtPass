<?php
/**
 * CourtPass — Platform Admin Panel
 * Venue approvals, user deactivation, no-show disputes, coach identity verification.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

requireLogin();
requireRole('admin');

// Fetch pending venue approvals
$pendingVenues = dbFetchAll(
    "SELECT v.*, u.name as owner_name, u.email as owner_email 
     FROM venues v 
     JOIN users u ON v.owner_id = u.id 
     WHERE v.status = 'pending' 
     ORDER BY v.created_at ASC"
);

// Fetch all venues
$allVenues = dbFetchAll(
    "SELECT v.*, u.name as owner_name 
     FROM venues v 
     JOIN users u ON v.owner_id = u.id 
     ORDER BY v.created_at DESC"
);

// Fetch pending coach verifications
$pendingCoaches = dbFetchAll(
    "SELECT cp.*, u.name as coach_name, u.email as coach_email, u.phone as coach_phone 
     FROM coach_profiles cp 
     JOIN users u ON cp.user_id = u.id 
     WHERE cp.verification_status = 'pending' AND cp.nic_document_path IS NOT NULL"
);

// Fetch no-show bookings for dispute review
$noShowBookings = dbFetchAll(
    "SELECT b.*, u.name as customer_name, c.name as court_name, v.name as venue_name 
     FROM bookings b 
     JOIN users u ON b.customer_id = u.id 
     JOIN courts c ON b.court_id = c.id 
     JOIN venues v ON c.venue_id = v.id 
     WHERE b.status = 'no_show' 
     ORDER BY b.slot_date DESC LIMIT 30"
);

// Fetch users list
$users = dbFetchAll("SELECT id, name, email, phone, role, status, created_at FROM users ORDER BY created_at DESC LIMIT 50");

$pageTitle = 'Admin Panel';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
    <div style="margin-bottom: 2rem;">
        <h1>⚙️ Platform Admin Panel</h1>
        <p class="text-secondary">Manage platform venues, users, coach identity verifications, and disputes</p>
    </div>

    <!-- Admin Tabs -->
    <div class="tabs">
        <button class="tab tab--active" onclick="switchTab('venuesTab')">Venues Management (<?= count($pendingVenues) ?> Pending)</button>
        <button class="tab" onclick="switchTab('coachesTab')">Coach Identity Verification (<?= count($pendingCoaches) ?> Pending)</button>
        <button class="tab" onclick="switchTab('disputesTab')">No-Show Disputes (<?= count($noShowBookings) ?>)</button>
        <button class="tab" onclick="switchTab('usersTab')">User Accounts (<?= count($users) ?>)</button>
    </div>

    <!-- 1. Venues Tab -->
    <div class="tab-content tab-content--active" id="venuesTab">
        <h3 class="mb-4">Pending Venue Approvals</h3>
        <?php if (empty($pendingVenues)): ?>
            <div class="card empty-state mb-8"><p>No pending venue approval requests.</p></div>
        <?php else: ?>
            <div class="grid grid--2 mb-8">
                <?php foreach ($pendingVenues as $pv): ?>
                    <div class="card">
                        <div class="card__body">
                            <span class="badge badge--warning mb-2">Pending Approval</span>
                            <h3 class="card__title"><?= sanitize($pv['name']) ?></h3>
                            <p class="card__subtitle">📍 <?= sanitize($pv['address']) ?>, <?= sanitize($pv['city']) ?></p>
                            <p class="text-sm text-secondary mb-3"><?= sanitize($pv['description']) ?></p>
                            <p class="text-xs text-muted">Submitted by: <?= sanitize($pv['owner_name']) ?> (<?= sanitize($pv['owner_email']) ?>)</p>
                        </div>
                        <div class="card__footer">
                            <button class="btn btn--primary btn--sm admin-venue-action-btn" data-id="<?= $pv['id'] ?>" data-action="approve">Approve</button>
                            <button class="btn btn--danger btn--sm admin-venue-action-btn" data-id="<?= $pv['id'] ?>" data-action="reject">Reject</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3>All Registered Venues</h3>
        <div class="table-wrapper mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Venue Name</th>
                        <th>City</th>
                        <th>Owner</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allVenues as $v): ?>
                        <tr>
                            <td><strong><?= sanitize($v['name']) ?></strong></td>
                            <td><?= sanitize($v['city']) ?></td>
                            <td><?= sanitize($v['owner_name']) ?></td>
                            <td><span class="badge <?= statusBadgeClass($v['status']) ?>"><?= statusLabel($v['status']) ?></span></td>
                            <td>
                                <?php if ($v['status'] !== 'deactivated'): ?>
                                    <button class="btn btn--danger btn--sm admin-venue-action-btn" data-id="<?= $v['id'] ?>" data-action="deactivate">Deactivate Venue</button>
                                <?php else: ?>
                                    <span class="text-xs text-muted">Deactivated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Coach Verification Tab -->
    <div class="tab-content" id="coachesTab">
        <h3 class="mb-4">Pending Coach Identity Verifications</h3>
        <?php if (empty($pendingCoaches)): ?>
            <div class="card empty-state"><p>No pending coach verification photo submissions.</p></div>
        <?php else: ?>
            <div class="grid grid--2">
                <?php foreach ($pendingCoaches as $pc): ?>
                    <div class="card">
                        <div class="card__body">
                            <span class="badge badge--warning mb-2">Pending Identity Verification</span>
                            <h3 class="card__title"><?= sanitize($pc['coach_name']) ?></h3>
                            <p class="text-xs text-muted mb-3"><?= sanitize($pc['coach_email']) ?> · <?= sanitize($pc['coach_phone']) ?></p>
                            <p class="text-sm">Specializations: <?= sanitize($pc['specializations'] ?? 'Not specified') ?></p>
                            <div class="mt-3 p-3" style="background: var(--neutral-50); border-radius: var(--radius-md);">
                                <p class="text-xs font-semibold mb-2">Uploaded NIC Document Photo Artifact:</p>
                                <a href="<?= BASE_URL ?>/<?= sanitize($pc['nic_document_path']) ?>" target="_blank" class="btn btn--outline btn--sm">
                                    📷 View Uploaded Photo Artifact
                                </a>
                            </div>
                        </div>
                        <div class="card__footer">
                            <button class="btn btn--primary btn--sm verify-coach-btn" data-id="<?= $pc['user_id'] ?>" data-status="verified">Verify Identity</button>
                            <button class="btn btn--danger btn--sm verify-coach-btn" data-id="<?= $pc['user_id'] ?>" data-status="rejected">Decline</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. Disputes Tab -->
    <div class="tab-content" id="disputesTab">
        <h3 class="mb-4">No-Show Flagged Bookings (Dispute Review)</h3>
        <p class="text-sm text-muted mb-4">Clearing a no-show flag automatically recalculates the customer's reliability score & tier.</p>
        <?php if (empty($noShowBookings)): ?>
            <div class="card empty-state"><p>No active no-show flags reported.</p></div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Customer</th>
                            <th>Venue & Court</th>
                            <th>Slot Date & Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($noShowBookings as $ns): ?>
                            <tr>
                                <td><strong>#<?= $ns['id'] ?></strong></td>
                                <td><strong><?= sanitize($ns['customer_name']) ?></strong></td>
                                <td><?= sanitize($ns['venue_name']) ?> (<?= sanitize($ns['court_name']) ?>)</td>
                                <td><?= formatDate($ns['slot_date']) ?> (<?= formatTime($ns['slot_start']) ?>)</td>
                                <td>
                                    <button class="btn btn--primary btn--sm clear-noshow-btn" data-id="<?= $ns['id'] ?>">
                                        Clear Dispute & Recalculate Score
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- 4. Users Tab -->
    <div class="tab-content" id="usersTab">
        <h3 class="mb-4">Platform Users Account Management</h3>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= $u['id'] ?></td>
                            <td><strong><?= sanitize($u['name']) ?></strong></td>
                            <td><?= sanitize($u['email']) ?></td>
                            <td><span class="badge badge--info"><?= strtoupper($u['role']) ?></span></td>
                            <td><span class="badge <?= statusBadgeClass($u['status']) ?>"><?= statusLabel($u['status']) ?></span></td>
                            <td>
                                <?php if ($u['id'] !== currentUserId()): ?>
                                    <button class="btn <?= $u['status'] === 'active' ? 'btn--danger' : 'btn--primary' ?> btn--sm toggle-user-btn" 
                                            data-id="<?= $u['id'] ?>" 
                                            data-action="<?= $u['status'] === 'active' ? 'deactivate' : 'activate' ?>">
                                        <?= $u['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-muted">You</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('tab--active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('tab-content--active'));

    event.target.classList.add('tab--active');
    document.getElementById(tabId).classList.add('tab-content--active');
}

document.addEventListener('DOMContentLoaded', () => {
    // Admin Venue Actions
    document.querySelectorAll('.admin-venue-action-btn').forEach(b => {
        b.addEventListener('click', async () => {
            const action = b.dataset.action;
            const id = b.dataset.id;
            let reason = '';

            if (action === 'reject') {
                reason = prompt('Mandatory reason for rejecting this venue:');
                if (!reason) return;
            } else if (action === 'deactivate') {
                if (!confirm('Deactivating a venue will auto-cancel all future bookings at this venue. Continue?')) return;
            }

            const formData = new FormData();
            formData.append('venue_id', id);
            formData.append('action', action);
            if (reason) formData.append('reason', reason);
            formData.append('csrf_token', getCsrfToken());

            try {
                const res = await fetchApi('<?= BASE_URL ?>/api/admin/venues', { method: 'POST', body: formData });
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) { showToast(err.message, 'error'); }
        });
    });

    // Admin Coach Verification
    document.querySelectorAll('.verify-coach-btn').forEach(b => {
        b.addEventListener('click', async () => {
            const status = b.dataset.status;
            const id = b.dataset.id;

            const formData = new FormData();
            formData.append('coach_user_id', id);
            formData.append('status', status);
            formData.append('csrf_token', getCsrfToken());

            try {
                const res = await fetchApi('<?= BASE_URL ?>/api/admin/coach-verify', { method: 'POST', body: formData });
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) { showToast(err.message, 'error'); }
        });
    });

    // Admin Clear Dispute
    document.querySelectorAll('.clear-noshow-btn').forEach(b => {
        b.addEventListener('click', async () => {
            if (confirm('Clear this no-show dispute? The booking will be marked completed and reliability score recalculated.')) {
                const formData = new FormData();
                formData.append('booking_id', b.dataset.id);
                formData.append('action', 'clear_noshow');
                formData.append('csrf_token', getCsrfToken());

                try {
                    const res = await fetchApi('<?= BASE_URL ?>/api/admin/disputes', { method: 'POST', body: formData });
                    showToast(res.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } catch (err) { showToast(err.message, 'error'); }
            }
        });
    });

    // Toggle User Activation
    document.querySelectorAll('.toggle-user-btn').forEach(b => {
        b.addEventListener('click', async () => {
            const action = b.dataset.action;
            const id = b.dataset.id;

            const formData = new FormData();
            formData.append('user_id', id);
            formData.append('action', action);
            formData.append('csrf_token', getCsrfToken());

            try {
                const res = await fetchApi('<?= BASE_URL ?>/api/admin/users', { method: 'POST', body: formData });
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } catch (err) { showToast(err.message, 'error'); }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
