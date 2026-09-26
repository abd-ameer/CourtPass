<?php
/** @var array $desk date, search, bookings, counts, found, message */
$search = $desk['search'];
$postPath = fn (int $id) => '/owner/bookings/' . $id . '/check-in' . ($search === '' ? '' : '?q=' . rawurlencode($search));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Check-in Desk</span>
        </div>
        <h1 class="page-title">Customer Check-in</h1>
        <div class="page-subtitle">Mark customers as arrived for confirmed bookings, from 1 hour before the start until the slot ends.</div>
    </div>

    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <span class="badge badge-status-completed" style="font-size: 13px; padding: 6px 14px;">Checked in: <?= e($desk['counts']['checked_in']) ?></span>
        <span class="badge badge-status-confirmed" style="font-size: 13px; padding: 6px 14px;">Waiting: <?= e($desk['counts']['waiting']) ?></span>
        <span class="badge badge-secondary" style="font-size: 13px; padding: 6px 14px;">Ended without check-in: <?= e($desk['counts']['missed']) ?></span>
    </div>
</div>

<div class="card" style="padding: 16px 20px; margin-bottom: var(--space-6);">
    <form method="GET" action="<?= url('/owner/check-in') ?>" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <input type="search" name="q" class="form-control" style="flex: 1; min-width: 220px;" placeholder="Booking number (e.g. 12) or customer name" value="<?= e($search) ?>" maxlength="100">
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if ($search !== ''): ?>
            <a href="<?= url('/owner/check-in') ?>" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="font-size: 16px; margin-bottom: 0;"><?= $desk['found'] !== null ? 'Booking #' . e($desk['found']['id']) : "Today's Arrivals" ?></h3>
        <span class="text-xs text-muted"><?= e(format_datetime($desk['date'], false)) ?></span>
    </div>

    <?php if ($desk['bookings'] === []): ?>
        <div class="card-body" style="text-align: center; color: var(--color-text-muted);">
            <?= e($desk['message'] ?? 'No confirmed bookings at your venues today.') ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Venue and Court</th>
                        <th>Time</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($desk['bookings'] as $b): ?>
                        <tr>
                            <td><a href="<?= url('/owner/bookings/' . $b['id']) ?>"><strong>#<?= e($b['id']) ?></strong></a></td>
                            <td>
                                <strong><?= e($b['customer_name']) ?></strong>
                                <span class="tier-badge tier-<?= e($b['reliability_tier']) ?>" style="font-size: 10px; margin-left: 4px;"><?= $b['reliability_score'] === null ? '' : e(round($b['reliability_score'])) . '% ' ?><?= e(status_label($b['reliability_tier'])) ?></span>
                            </td>
                            <td>
                                <strong><?= e($b['court_name']) ?></strong>
                                <div class="text-xs text-muted"><?= e($b['venue_name']) ?></div>
                            </td>
                            <td>
                                <?= e($b['start']) ?> to <?= e($b['end']) ?>
                                <?php if ($b['slot_date'] !== $desk['date']): ?>
                                    <div class="text-xs text-muted"><?= e(format_datetime($b['slot_date'], false)) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($b['payment_method'] === 'cash_on_arrival'): ?>
                                    <span class="badge badge-unpaid">Cash on arrival: collect <?= e(lkr($b['amount'])) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-paid">Paid online</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($b['desk_state'] === 'checked_in'): ?>
                                    <span class="badge badge-status-completed">Checked in <?= e(date('H:i', strtotime($b['checked_in_at']))) ?></span>
                                <?php elseif ($b['desk_state'] === 'missed'): ?>
                                    <span class="badge badge-secondary">Ended, no check-in</span>
                                <?php else: ?>
                                    <?= status_badge($b['status']) ?>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?php if ($b['can_check_in']): ?>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Confirm Check-in', <?= e(json_encode('Mark ' . $b['customer_name'] . ' as arrived for booking #' . $b['id'] . '?')) ?>, 'Check In', <?= e(json_encode($postPath($b['id']))) ?>)">
                                        Mark Checked-In
                                    </button>
                                <?php elseif ($b['desk_state'] === 'waiting'): ?>
                                    <span class="text-xs text-muted">Opens <?= $b['slot_date'] !== $desk['date'] ? e(format_datetime($b['slot_date'], false)) . ', ' : 'at ' ?><?= e($b['check_in_opens']) ?></span>
                                <?php elseif ($b['desk_state'] === null): ?>
                                    <span class="text-xs text-muted">Only confirmed bookings can be checked in</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
