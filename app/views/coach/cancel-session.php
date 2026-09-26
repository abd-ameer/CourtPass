<?php
/** @var array $session @var array $reasons @var array $old @var array $errors */
$s = $session;
$live = array_values(array_filter($s['registrations'], fn (array $r) => $r['live']));
$refundTotal = array_sum(array_map(fn (array $r) => $r['paid_amount'] ?? 0.0, $live));
$isOther = $old['reason_choice'] === 'other';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions') ?>">Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions/' . $s['id']) ?>">#<?= (int) $s['id'] ?></a>
            <span class="breadcrumb-separator">/</span>
            <span>Cancel</span>
        </div>
        <h1 class="page-title">Cancel Coaching Session</h1>
        <div class="page-subtitle">Check who is affected before confirming. This cannot be undone.</div>
    </div>
</div>

<form id="cancelSessionForm" method="POST" action="<?= url('/coach/sessions/' . $s['id'] . '/cancel') ?>" novalidate>
    <?= csrf_field() ?>
    <div class="grid grid-cols-3 gap-6">
        <div style="grid-column: span 2;">
            <div style="background: var(--color-danger-bg); border: 1px solid var(--color-danger-border); border-radius: var(--radius-lg); padding: var(--space-5); margin-bottom: var(--space-6);">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">Cancel "<?= e($s['title']) ?>"?</h3>
                <p style="font-size: 13px; line-height: 1.6; margin: 0;">
                    <?php if ($live === []): ?>
                        Nobody is registered yet. The session is cancelled and its court slot is released.
                    <?php else: ?>
                        <?= count($live) ?> registration(s) will be cancelled. Paid registrations are refunded in full (simulated),
                        everyone gets an in-app notification with your reason, and the court slot is released.
                    <?php endif; ?>
                </p>
            </div>

            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Session</h3>
                    <?= status_badge($s['status']) ?>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-muted">Venue and Court</div>
                            <div style="font-weight: 600;"><?= e($s['venue_name']) ?></div>
                            <div class="text-xs text-muted"><?= e($s['court_name']) ?></div>
                        </div>
                        <div>
                            <div class="text-xs text-muted">Date and Time</div>
                            <div style="font-weight: 600;"><?= e(format_datetime($s['session_date'], false)) ?></div>
                            <div class="text-xs text-muted"><?= e($s['start']) ?> to <?= e($s['end']) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($live !== []): ?>
                <div class="card" style="margin-bottom: var(--space-6);">
                    <div class="card-header">
                        <h3 style="font-size: 15px; margin-bottom: 0;">Affected Students (<?= count($live) ?>)</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Registered</th>
                                    <th>Status</th>
                                    <th>Refund</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($live as $r): ?>
                                    <tr>
                                        <td>
                                            <strong><?= e($r['customer_name']) ?></strong>
                                            <div class="text-xs text-muted"><?= e($r['customer_email']) ?></div>
                                        </td>
                                        <td><?= e(format_datetime($r['created_at'])) ?></td>
                                        <td><?= status_badge($r['status']) ?></td>
                                        <td><strong><?= $r['paid_amount'] !== null ? e(lkr($r['paid_amount'])) : 'None (not paid)' ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Cancellation Reason <span class="required-star">*</span></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="cancelReason">Reason</label>
                        <select name="reason_choice" id="cancelReason" class="form-select<?= isset($errors['reason_choice']) ? ' is-invalid' : '' ?>" required>
                            <option value="">Choose a reason</option>
                            <?php foreach ($reasons as $reason): ?>
                                <option value="<?= e($reason) ?>" <?= $old['reason_choice'] === $reason ? 'selected' : '' ?>><?= e($reason) ?></option>
                            <?php endforeach; ?>
                            <option value="other" <?= $isOther ? 'selected' : '' ?>>Other</option>
                        </select>
                        <span class="form-feedback invalid" id="reasonChoiceFeedback"><?= e($errors['reason_choice'] ?? '') ?></span>
                    </div>
                    <div class="form-group" id="customReasonGroup" style="<?= $isOther ? '' : 'display: none;' ?>">
                        <label class="form-label" for="customReasonText">Please specify <span class="required-star">*</span></label>
                        <textarea name="reason_text" id="customReasonText" class="form-control<?= isset($errors['reason_text']) ? ' is-invalid' : '' ?>" rows="3" maxlength="500" placeholder="This is shown to every registered student."><?= e($old['reason_text']) ?></textarea>
                        <span class="form-feedback invalid" id="reasonTextFeedback"><?= e($errors['reason_text'] ?? '') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Cancellation Impact</h3>
                </div>
                <div class="card-body" style="font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Students affected</span><strong><?= count($live) ?></strong></div>
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Total refund</span><strong><?= e(lkr($refundTotal)) ?></strong></div>
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Court slot</span><strong>Released</strong></div>
                    <button type="submit" id="confirmCancelBtn" class="btn btn-danger" style="width: 100%; margin-top: var(--space-4);">Confirm Cancellation</button>
                    <a href="<?= url('/coach/sessions/' . $s['id']) ?>" class="btn btn-secondary" style="width: 100%; margin-top: var(--space-2); text-align: center;">Keep Session</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
(() => {
    const form = document.getElementById('cancelSessionForm');
    const select = document.getElementById('cancelReason');
    const group = document.getElementById('customReasonGroup');
    const text = document.getElementById('customReasonText');
    let confirmed = false;

    select.addEventListener('change', () => {
        group.style.display = select.value === 'other' ? '' : 'none';
        select.classList.remove('is-invalid');
        document.getElementById('reasonChoiceFeedback').textContent = '';
    });
    text.addEventListener('input', () => {
        text.classList.remove('is-invalid');
        document.getElementById('reasonTextFeedback').textContent = '';
    });

    form.addEventListener('submit', (event) => {
        if (confirmed) {
            return;
        }
        event.preventDefault();
        if (!select.value) {
            select.classList.add('is-invalid');
            document.getElementById('reasonChoiceFeedback').textContent = 'Choose a reason for cancelling.';
            return;
        }
        if (select.value === 'other' && !text.value.trim()) {
            text.classList.add('is-invalid');
            document.getElementById('reasonTextFeedback').textContent = 'Type the reason for cancelling.';
            return;
        }
        CourtPassApp.confirmDialog('Cancel Session', 'Registrations are cancelled and refunded in full, and the court slot is released.', 'Cancel Session', () => {
            confirmed = true;
            form.submit();
        });
    });
})();
</script>
