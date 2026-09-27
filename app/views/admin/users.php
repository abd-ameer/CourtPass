<?php
/** @var string $role */
// Sample rows matching the seed accounts until the user list is built.
$users = [
    [1, 'System Admin', 'admin', 'admin@courtpass.lk', '0771234567', 'Platform administrator'],
    [2, 'Kamal Perera', 'owner', 'kamal@sportshub.lk', '0772345678', '2 approved venues'],
    [3, 'Nimal Silva', 'owner', 'nimal@courtzone.lk', '0773456789', '1 pending venue'],
    [4, 'Saman Fernando', 'customer', 'saman@gmail.com', '0774567890', '92% Standard'],
    [5, 'Ruwan Jayasinghe', 'customer', 'ruwan@gmail.com', '0775678901', 'New Member'],
    [6, 'Dinesh Kumara', 'customer', 'dinesh@gmail.com', '0776789012', '55% Restricted'],
    [7, 'Ashan Weerasinghe', 'coach', 'ashan@coach.lk', '0777890123', 'Verified coach'],
    [8, 'Dilani Rathnayake', 'coach', 'dilani@coach.lk', '0778901234', 'Not verified yet'],
];
$roleNames = ['admin' => 'Admin', 'owner' => 'Venue Owner', 'customer' => 'Customer', 'coach' => 'Coach'];
$count = fn (string $r) => count(array_filter($users, fn (array $u) => $u[2] === $r));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Users</span>
        </div>
        <h1 class="page-title">User Management</h1>
        <div class="page-subtitle">Customers, venue owners, coaches and platform administrators.</div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <button class="btn btn-sm btn-primary user-filter-btn" onclick="filterUserRole('all', this)">All (<?= count($users) ?>)</button>
        <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('customer', this)">Customers (<?= $count('customer') ?>)</button>
        <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('owner', this)">Venue Owners (<?= $count('owner') ?>)</button>
        <button class="btn btn-sm btn-outline user-filter-btn" onclick="filterUserRole('coach', this)">Coaches (<?= $count('coach') ?>)</button>
    </div>
    <input type="text" class="form-control" placeholder="Search by name, email or phone" onkeyup="filterUserTable(this.value)" style="max-width: 280px;">
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table" id="users-table">
            <thead>
                <tr><th>User</th><th>Role</th><th>Contact</th><th>Standing</th><th style="text-align: right;">Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as [$id, $name, $userRole, $email, $phone, $standing]): ?>
                    <tr data-role="<?= e($userRole) ?>">
                        <td><strong><?= e($name) ?></strong><div class="text-xs text-muted">User #<?= (int) $id ?></div></td>
                        <td><span class="badge badge-secondary"><?= e($roleNames[$userRole]) ?></span></td>
                        <td><div style="font-size: 12px;"><?= e($phone) ?></div><div class="text-xs text-muted"><?= e($email) ?></div></td>
                        <td class="text-sm"><?= e($standing) ?></td>
                        <td style="text-align: right;"><a href="<?= url('/admin/users/' . $id) ?>" class="btn btn-sm btn-outline">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterUserRole(role, btn) {
    document.querySelectorAll('.user-filter-btn').forEach((b) => {
        b.classList.remove('btn-primary');
        b.classList.add('btn-outline');
    });
    btn.classList.remove('btn-outline');
    btn.classList.add('btn-primary');
    document.querySelectorAll('#users-table tbody tr').forEach((row) => {
        row.style.display = role === 'all' || row.dataset.role === role ? '' : 'none';
    });
}

function filterUserTable(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('#users-table tbody tr').forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
