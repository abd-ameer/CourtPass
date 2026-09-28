<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Resale Disputes</span>
        </div>
        <h1 class="page-title">Resale Disputes</h1>
        <div class="page-subtitle">Disputes about released slots: the 90% refund when a slot is rebooked, or an unsold release.</div>
    </div>
</div>

<?php
// Rows match the open dispute in database/seed.sql until dispute handling is built.
$disputes = [
    ['id' => 2, 'booking_id' => 5, 'customer' => 'Ruwan Jayasinghe', 'email' => 'ruwan@gmail.com', 'tier' => 'new_member', 'score' => '',
     'slot' => 'Colombo Sports Hub · Badminton Court 1 · ' . format_datetime(relative_date(2, '20:00:00')) . ' · Released · Paid LKR 2,000', 'filed' => relative_date(0),
     'reason' => 'I released this slot for resale. Please confirm I still get the 90% refund if another customer books it before it starts.'],
];
?>
<div class="card">
    <div class="card-header">
        <h3 style="font-size: 16px; margin-bottom: 0;">Open Disputes (<?= count($disputes) ?>)</h3>
        <span class="badge badge-pending">Awaiting Review</span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead><tr><th>Dispute</th><th>Customer</th><th>Released Booking</th><th>Customer's Explanation</th><th>Decision</th></tr></thead>
            <tbody>
            <?php foreach ($disputes as $d): ?>
                <tr>
                    <td><strong>#<?= (int) $d['id'] ?></strong><div class="text-xs text-muted">Filed <?= e(format_datetime($d['filed'], false)) ?></div></td>
                    <td><strong><?= e($d['customer']) ?></strong><div class="text-xs text-muted"><?= e($d['email']) ?></div><div style="margin-top: 4px;"><?= status_badge($d['tier']) ?> <span class="text-xs text-muted"><?= e($d['score']) ?></span></div></td>
                    <td>Booking #<?= (int) $d['booking_id'] ?><div class="text-xs text-muted"><?= e($d['slot']) ?></div></td>
                    <td class="text-sm" style="max-width: 320px;"><?= e($d['reason']) ?></td>
                    <td style="white-space: nowrap;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.postWithReason('Uphold Dispute', 'Explain the outcome for the customer.', '/admin/disputes/resale/<?= (int) $d['id'] ?>/resolve')">Uphold</button>
                        <button type="button" class="btn btn-sm btn-outline" onclick="CourtPassApp.postWithReason('Dismiss Dispute', 'Explain why the dispute is dismissed.', '/admin/disputes/resale/<?= (int) $d['id'] ?>/resolve')">Dismiss</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
