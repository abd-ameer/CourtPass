<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>No-Show Disputes</span>
        </div>
        <h1 class="page-title">No-Show Disputes</h1>
        <div class="page-subtitle">Customers can contest a no-show penalty. Upholding a dispute clears the no-show and recalculates the customer's reliability score.</div>
    </div>
</div>

<?php
// Rows match the open dispute in database/seed.sql until dispute handling is built.
$disputes = [
    ['id' => 1, 'booking_id' => 2, 'customer' => 'Dinesh Kumara', 'email' => 'dinesh@gmail.com', 'tier' => 'restricted', 'score' => '55%',
     'slot' => 'Colombo Sports Hub · Futsal Court A · ' . format_datetime(relative_date(-2, '19:00:00')), 'filed' => relative_date(-1),
     'reason' => 'I arrived at 19:05 and played the full hour. The front desk forgot to mark me as checked in.'],
];
?>
<div class="card">
    <div class="card-header">
        <h3 style="font-size: 16px; margin-bottom: 0;">Open Disputes (<?= count($disputes) ?>)</h3>
        <span class="badge badge-pending">Awaiting Review</span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>Dispute</th><th>Customer</th><th>No-Show Booking</th><th>Customer's Explanation</th><th>Decision</th></tr></thead>
            <tbody>
            <?php foreach ($disputes as $d): ?>
                <tr>
                    <td><strong>#<?= (int) $d['id'] ?></strong><div class="text-xs text-muted">Filed <?= e(format_datetime($d['filed'], false)) ?></div></td>
                    <td><strong><?= e($d['customer']) ?></strong><div class="text-xs text-muted"><?= e($d['email']) ?></div><div style="margin-top: 4px;"><?= status_badge($d['tier']) ?> <span class="text-xs text-muted"><?= e($d['score']) ?></span></div></td>
                    <td>Booking #<?= (int) $d['booking_id'] ?><div class="text-xs text-muted"><?= e($d['slot']) ?></div></td>
                    <td class="text-sm" style="max-width: 320px;"><?= e($d['reason']) ?></td>
                    <td style="white-space: nowrap;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.postWithReason('Uphold Dispute', 'Explain why the no-show is cleared.', '/admin/disputes/no-show/<?= (int) $d['id'] ?>/resolve')">Uphold</button>
                        <button type="button" class="btn btn-sm btn-outline" onclick="CourtPassApp.postWithReason('Dismiss Dispute', 'Explain why the no-show stands.', '/admin/disputes/no-show/<?= (int) $d['id'] ?>/resolve')">Dismiss</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
