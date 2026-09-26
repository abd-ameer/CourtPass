<?php
/** @var array $session a public session, or a private one opened through its link */
$s = $session;
$private = $s['visibility'] === 'private';
$open = $s['status'] === 'open' && $s['can_edit'];
$percent = $s['capacity'] > 0 ? min(100, round($s['registration_count'] / $s['capacity'] * 100)) : 0;
?>
<div class="container" style="padding: var(--space-8) 0;">
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coaching') ?>">Coaching Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <span>Session Details</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <h1 class="page-title"><?= e($s['title']) ?></h1>
            <span class="badge badge-sport"><?= e($s['sport_name']) ?></span>
            <?php if ($private): ?>
                <span class="badge badge-warning">Private session</span>
            <?php endif; ?>
            <?php if ($s['status'] !== 'open'): ?>
                <?= status_badge($s['status']) ?>
            <?php endif; ?>
        </div>
        <div class="page-subtitle">
            <?= e($s['venue_name']) ?> · <?= e($s['court_name']) ?> · <?= e(format_datetime($s['session_date'], false)) ?>, <?= e($s['start']) ?> to <?= e($s['end']) ?>
        </div>
    </div>
    <div style="text-align: right;">
        <div class="text-xs text-muted">Fee per person</div>
        <div style="font-size: 24px; font-weight: 900; color: var(--color-primary-active);"><?= e($s['fee_label']) ?></div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
    <div>
        <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-5);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <strong style="font-size: 14px;">Places</strong>
                <span class="badge <?= $s['spots_left'] > 0 ? 'badge-confirmed' : 'badge-warning' ?>">
                    <?= (int) $s['registration_count'] ?> of <?= (int) $s['capacity'] ?> taken<?= $s['group'] === 'upcoming' ? ' (' . (int) $s['spots_left'] . ' left)' : '' ?>
                </span>
            </div>
            <div style="width: 100%; height: 8px; background: var(--color-border); border-radius: var(--radius-full); overflow: hidden;">
                <div style="width: <?= (int) $percent ?>%; height: 100%; background: var(--color-primary); border-radius: var(--radius-full);"></div>
            </div>
        </div>

        <div class="card" style="margin-bottom: var(--space-6); padding: var(--space-6);">
            <h3 style="font-size: 16px; margin-bottom: 10px;">About this session</h3>
            <p style="line-height: 1.6; margin-bottom: 0;"><?= nl2br(e($s['description'] ?? '')) ?></p>
        </div>

        <div class="card" style="padding: var(--space-6);">
            <h3 style="font-size: 16px; margin-bottom: 12px;">Coach</h3>
            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                <strong style="font-size: 15px;"><?= e($s['coach_name']) ?></strong>
                <?php if ($s['coach_verified']): ?>
                    <span class="badge badge-verified">Verified Coach</span>
                <?php endif; ?>
            </div>
            <div class="text-xs text-muted" style="margin-bottom: 12px;">
                <?= $s['coach_rating'] !== null ? 'Average rating ' . e(number_format($s['coach_rating'], 1)) . ' of 5' : 'No reviews yet' ?>
            </div>
            <a href="<?= url('/coaches/' . $s['coach_id']) ?>" style="font-size: 13px; font-weight: 600;">View coach profile &rarr;</a>
        </div>
    </div>

    <div>
        <div class="card" style="padding: var(--space-6); position: sticky; top: calc(var(--header-height) + 20px);">
            <h3 style="font-size: 16px; margin-bottom: 14px;">Registration</h3>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px; margin-bottom: 16px; border-bottom: 1px solid var(--color-border); padding-bottom: 14px;">
                <div style="display: flex; justify-content: space-between;"><span class="text-muted">Venue</span><span style="font-weight: 600;"><?= e($s['venue_name']) ?></span></div>
                <div style="display: flex; justify-content: space-between;"><span class="text-muted">Court</span><span style="font-weight: 600;"><?= e($s['court_name']) ?></span></div>
                <div style="display: flex; justify-content: space-between;"><span class="text-muted">When</span><span style="font-weight: 600;"><?= e(format_datetime($s['starts_at'])) ?></span></div>
                <div style="display: flex; justify-content: space-between;"><span class="text-muted">Fee</span><span style="font-weight: 600;"><?= e($s['fee_label']) ?></span></div>
            </div>

            <?php if ($s['status'] === 'cancelled'): ?>
                <div class="text-sm" style="text-align: center; color: var(--color-danger);">This session was cancelled by the coach.</div>
            <?php elseif ($s['group'] !== 'upcoming'): ?>
                <div class="text-sm text-muted" style="text-align: center;">This session has already taken place.</div>
            <?php elseif (!$open): ?>
                <button type="button" class="btn btn-secondary btn-block" disabled>Session Full</button>
            <?php elseif (Auth::hasRole('customer')): ?>
                <?php if ($private): ?>
                    <button type="button" class="btn btn-secondary btn-block" disabled>Registration Opens Soon</button>
                    <div class="text-xs text-muted" style="margin-top: 8px; text-align: center;">Registration with online payment opens when PayHere payments are connected.</div>
                <?php else: ?>
                    <form method="POST" action="<?= url('/sessions/' . $s['id'] . '/register') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><?= $s['fee'] > 0 ? 'Register and Pay Online' : 'Register (Free)' ?></button>
                    </form>
                <?php endif; ?>
            <?php elseif (!Auth::check()): ?>
                <a href="<?= url('/login') ?>" class="btn btn-primary btn-block btn-lg">Log In to Register</a>
                <div class="text-xs text-muted" style="margin-top: 8px; text-align: center;">New here? <a href="<?= url('/register/customer') ?>">Create a customer account</a></div>
            <?php else: ?>
                <div class="text-sm text-muted" style="text-align: center;">Only customer accounts can register for sessions.</div>
            <?php endif; ?>

            <?php if ($s['fee'] > 0): ?>
                <div style="margin-top: 16px; font-size: 11px; color: var(--color-text-muted); line-height: 1.5; border-top: 1px solid var(--color-border); padding-top: 12px;">
                    <strong>Cancellation policy:</strong> more than 48 hours before, 100% refund; 12 to 48 hours, 50%; under 12 hours, no refund.
                    Registrations cannot be resold. If the coach cancels, you get a full refund.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
