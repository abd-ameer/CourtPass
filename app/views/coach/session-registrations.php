<?php
/** @var int $sessionId @var array $session the coach's session with its registrations */
$s = $session;
$regs = $s['registrations'];
$paid = array_sum(array_map(fn (array $r) => (float) ($r['paid_amount'] ?? 0) - (float) ($r['refund_amount'] ?? 0), $regs));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <span>Coach Portal</span>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions') ?>">Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions/' . (int) $sessionId) ?>">#<?= (int) $sessionId ?></a>
            <span class="breadcrumb-separator">/</span>
            <span>Registrations</span>
        </div>
        <h1 class="page-title">Registrations: <?= e($s['title']) ?></h1>
        <div class="page-subtitle">Everyone who registered for session #<?= (int) $sessionId ?>, with payment and attendance.</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="<?= url('/coach/sessions/' . (int) $sessionId . '/attendance') ?>" class="btn btn-primary">Mark Attendance</a>
    </div>
</div>

<div style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6); flex-wrap: wrap;">
    <div style="background: #e6f8f0; border-radius: var(--radius-lg); padding: 12px 20px;">
        <div class="text-xs text-muted">Date & Time</div>
        <div style="font-weight: 600; font-size: 13px;"><?= e(format_datetime($s['starts_at'])) ?> to <?= e($s['end']) ?></div>
    </div>
    <div style="background: #dbeafe; border-radius: var(--radius-lg); padding: 12px 20px;">
        <div class="text-xs text-muted">Venue</div>
        <div style="font-weight: 600; font-size: 13px;"><?= e($s['venue_name']) ?> · <?= e($s['court_name']) ?></div>
    </div>
    <div style="background: #fef3c7; border-radius: var(--radius-lg); padding: 12px 20px;">
        <div class="text-xs text-muted">Places</div>
        <div style="font-weight: 600; font-size: 13px;"><?= (int) $s['registration_count'] ?> / <?= (int) $s['capacity'] ?> registered</div>
    </div>
    <div style="background: #d1fae5; border-radius: var(--radius-lg); padding: 12px 20px;">
        <div class="text-xs text-muted">Paid, less refunds</div>
        <div style="font-weight: 600; font-size: 13px;"><?= e(lkr($paid)) ?></div>
    </div>
</div>

<div class="card">
    <?php if ($regs === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No registrations yet</div>
            <div class="empty-state-desc">Students who register for this session appear here.</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Registered</th>
                        <th>Paid</th>
                        <th>Refund</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regs as $r): ?>
                        <tr>
                            <td>
                                <strong><?= e($r['customer_name']) ?></strong>
                                <div class="text-xs text-muted"><?= e($r['customer_email']) ?></div>
                            </td>
                            <td><?= e(format_datetime($r['created_at'])) ?></td>
                            <td><?= $r['paid_amount'] === null ? '<span class="text-muted">Not paid</span>' : e(lkr($r['paid_amount'])) ?></td>
                            <td><?= $r['refund_amount'] === null ? '<span class="text-muted">None</span>' : e(lkr($r['refund_amount'])) ?></td>
                            <td><?= status_badge($r['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
