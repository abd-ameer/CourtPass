<?php
/** @var array $profile reliability fields @var array $noShows the customer's own no-show bookings */
$tier = $profile['reliability_tier'];
$score = $profile['reliability_score'] === null ? null : round((float) $profile['reliability_score']);
$tierNames = ['new_member' => 'New Member', 'restricted' => 'Restricted', 'standard' => 'Standard'];
$cash = $tier === 'standard';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <span>Reliability & Trust</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <h1 class="page-title">Reliability Score & Tier Standing</h1>
            <span class="badge tier-<?= e($tier) ?>"><?= e($tierNames[$tier]) ?></span>
        </div>
        <div class="page-subtitle">Your reliability score decides whether you can pay in cash at the venue.</div>
    </div>
    <button class="btn btn-outline" onclick="openDisputeModal()">
        File a Penalty Dispute
    </button>
</div>

<!-- Current standing -->
<div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6); background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1.5px solid var(--color-primary-border);">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 24px;">
            <div style="width: 88px; height: 88px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 800;">
                <span style="font-size: <?= $score === null ? '20px' : '28px' ?>; line-height: 1;"><?= $score === null ? 'New' : e($score) . '%' ?></span>
                <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9;">Score</span>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <h2 style="font-size: 20px; margin-bottom: 0;"><?= e($tierNames[$tier]) ?> Tier</h2>
                    <span class="badge <?= $cash ? 'badge-confirmed' : 'badge-warning' ?>"><?= $cash ? 'Cash on Arrival Active' : 'Online Payment Only' ?></span>
                </div>
                <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;">
                    You have <strong><?= (int) $profile['completed_count'] ?> completed bookings</strong> and <strong><?= (int) $profile['no_show_count'] ?> no-shows</strong>.
                </p>
                <div class="text-xs text-muted">Recalculated when a booking is completed or cancelled, never on a timer.</div>
            </div>
        </div>
        <div style="background: white; padding: 12px 18px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
            <div class="text-xs text-muted">Payment Options</div>
            <div style="font-size: 15px; font-weight: 800; color: var(--color-primary); margin-top: 2px;">
                <?= $cash ? 'Online or Cash on Arrival' : 'Online only' ?>
            </div>
        </div>
    </div>
</div>

<!-- Tier framework -->
<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <h3 style="font-size: 16px; margin-bottom: 0;">CourtPass Tier Framework</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tier</th>
                    <th>Criteria</th>
                    <th>Payment Options</th>
                    <th>Your Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ([
                    'new_member' => ['Fewer than 5 completed bookings', 'Online payment only'],
                    'restricted' => ['Score below 70%', 'Online payment only'],
                    'standard'   => ['Score 70% or more, with 5 or more completed bookings', 'Online payment or Cash on Arrival'],
                ] as $key => [$criteria, $payment]): ?>
                    <tr<?= $key === $tier ? ' style="background: var(--color-primary-light);"' : '' ?>>
                        <td><span class="badge tier-<?= e($key) ?>"><?= e($tierNames[$key]) ?></span></td>
                        <td><?= e($criteria) ?></td>
                        <td><?= e($payment) ?></td>
                        <td><?= $key === $tier ? '<span class="badge badge-confirmed">Current Tier</span>' : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">How Reliability is Calculated</h3>
        </div>
        <div class="card-body" style="font-size: 13px; display: flex; flex-direction: column; gap: 10px;">
            <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--color-border-subtle);">
                <span>Completed, checked-in booking</span>
                <strong style="color: var(--color-primary);">Raises the score</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--color-border-subtle);">
                <span>Responsible cancellation (over 48 hours before)</span>
                <strong>No penalty</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--color-border-subtle);">
                <span>Irresponsible cancellation (under 12 hours, cash)</span>
                <strong style="color: #ea580c;">Lowers the score</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>No-show</span>
                <strong style="color: var(--color-danger);">Lowers the score most</strong>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;">Contest an Unjust Penalty?</h3>
        </div>
        <div class="card-body">
            <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 14px;">
                If a venue marked you as a no-show by mistake, the Platform Admin can clear the penalty after review.
                <?= $noShows === [] ? 'You have no no-show bookings to dispute.' : '' ?>
            </p>
            <button class="btn btn-outline-primary btn-block" onclick="openDisputeModal()">
                Submit a No-Show Dispute &rarr;
            </button>
        </div>
    </div>
</div>

<!-- Dispute Submission Modal -->
<div id="noShowDisputeModal" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title">Submit a No-Show Dispute</h3>
            <button class="modal-close" onclick="CourtPassApp.closeModal('noShowDisputeModal')">&times;</button>
        </div>
        <div class="modal-body">
            <?php if ($noShows === []): ?>
                <p class="text-sm" style="margin-bottom: 0;">You have no no-show bookings, so there is nothing to dispute.</p>
            <?php else: ?>
                <form method="POST" action="<?= url('/customer/disputes') ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label" for="disputeBooking">No-Show Booking <span class="required-star">*</span></label>
                        <select name="booking_id" id="disputeBooking" class="form-select" required>
                            <option value="">Choose a booking</option>
                            <?php foreach ($noShows as $b): ?>
                                <option value="<?= (int) $b['id'] ?>">#<?= (int) $b['id'] ?>: <?= e($b['venue_name']) ?>, <?= e($b['court_name']) ?> (<?= e(format_datetime($b['slot_date'], false)) ?>, <?= e($b['start']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="disputeReason">Explanation <span class="required-star">*</span></label>
                        <textarea name="reason" id="disputeReason" class="form-control" rows="3" maxlength="1000" placeholder="Explain what happened at the venue." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        Submit to Platform Admin
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function openDisputeModal() {
    CourtPassApp.openModal('noShowDisputeModal');
}
</script>
