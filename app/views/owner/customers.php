<?php
// Sample rows matching the seed customers of the demo owner until customer intelligence is built.
$customers = [
    ['name' => 'Saman Fernando', 'email' => 'saman@gmail.com', 'phone' => '0774567890', 'score' => 92, 'tier' => 'standard', 'bookings' => 10, 'completed' => 7, 'no_shows' => 0, 'spend' => 21000],
    ['name' => 'Dinesh Kumara', 'email' => 'dinesh@gmail.com', 'phone' => '0776789012', 'score' => 55, 'tier' => 'restricted', 'bookings' => 10, 'completed' => 6, 'no_shows' => 2, 'spend' => 16300],
    ['name' => 'Ruwan Jayasinghe', 'email' => 'ruwan@gmail.com', 'phone' => '0775678901', 'score' => null, 'tier' => 'new_member', 'bookings' => 2, 'completed' => 1, 'no_shows' => 0, 'spend' => 5000],
];
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Customer Intelligence</span>
        </div>
        <h1 class="page-title">Customer Intelligence</h1>
        <div class="page-subtitle">Booking history, reliability and spend of the customers who booked at your venues.</div>
    </div>
</div>

<div style="display: flex; justify-content: flex-end; margin-bottom: var(--space-4);">
    <input type="text" class="form-control" placeholder="Search by name, phone or email" onkeyup="filterCustomerTable(this.value)" style="max-width: 280px;">
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table" id="customers-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Reliability</th>
                    <th>Bookings Here</th>
                    <th>Completed</th>
                    <th>No-Shows</th>
                    <th>Total Spend</th>
                    <th>Cash on Arrival</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><strong><?= e($c['name']) ?></strong></td>
                        <td>
                            <div style="font-size: 12px;"><?= e($c['phone']) ?></div>
                            <div class="text-xs text-muted"><?= e($c['email']) ?></div>
                        </td>
                        <td><span class="tier-badge tier-<?= e($c['tier']) ?>"><?= $c['score'] === null ? '' : e($c['score']) . '% ' ?><?= e(status_label($c['tier'])) ?></span></td>
                        <td><?= (int) $c['bookings'] ?></td>
                        <td><?= (int) $c['completed'] ?></td>
                        <td><span style="font-weight: 700; color: <?= $c['no_shows'] > 0 ? 'var(--color-danger)' : 'var(--color-success)' ?>;"><?= (int) $c['no_shows'] ?></span></td>
                        <td><strong><?= e(lkr($c['spend'])) ?></strong></td>
                        <td><?= $c['tier'] === 'standard' ? '<span class="badge badge-confirmed">Eligible</span>' : '<span class="badge badge-warning">Online only</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterCustomerTable(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('#customers-table tbody tr').forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
