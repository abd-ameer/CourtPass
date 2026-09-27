<?php
/** @var array $rows the coach's sessions with paid and refunded registration totals */
$paid = array_sum(array_column($rows, 'paid'));
$refunded = array_sum(array_column($rows, 'refunded'));
$completed = count(array_filter($rows, fn (array $r) => $r['status'] === 'completed'));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <span>Earnings</span>
        </div>
        <h1 class="page-title">Earnings</h1>
        <div class="page-subtitle">Paid session registrations minus refunds. Payments between coaches and venues are settled outside CourtPass.</div>
    </div>
</div>

<div class="grid grid-cols-4 gap-6" style="margin-bottom: var(--space-6);">
    <div class="stat-card">
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
        <div>
            <div class="stat-value"><?= e(lkr($paid - $refunded)) ?></div>
            <div class="stat-label">Net Earnings</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
        <div>
            <div class="stat-value"><?= e(lkr($paid)) ?></div>
            <div class="stat-label">Paid Registrations</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg></div>
        <div>
            <div class="stat-value"><?= e(lkr($refunded)) ?></div>
            <div class="stat-label">Refunds</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
        <div>
            <div class="stat-value"><?= (int) $completed ?></div>
            <div class="stat-label">Completed Sessions</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Earnings by Session</h3>
    </div>
    <?php if ($rows === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No sessions yet</div>
            <div class="empty-state-desc">Earnings appear once students pay for your sessions.</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr><th>Session</th><th>Date</th><th>Status</th><th>Registrations</th><th>Paid</th><th>Refunded</th><th>Net</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><a href="<?= url('/coach/sessions/' . $r['id']) ?>"><strong><?= e($r['title']) ?></strong></a><div class="text-xs text-muted"><?= e($r['venue_name']) ?> · <?= e($r['court_name']) ?></div></td>
                            <td><?= e(format_datetime($r['starts_at'])) ?></td>
                            <td><?= status_badge($r['status']) ?></td>
                            <td><?= (int) $r['registration_count'] ?> / <?= (int) $r['capacity'] ?></td>
                            <td><?= e(lkr($r['paid'])) ?></td>
                            <td><?= e(lkr($r['refunded'])) ?></td>
                            <td><strong><?= e(lkr($r['paid'] - $r['refunded'])) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
