<?php
/** @var int $userId @var array $user name, email, phone, role @var array $history bookings (customer), venues (owner) or sessions (coach) */
$roleNames = ['admin' => 'Platform Admin', 'owner' => 'Venue Owner', 'customer' => 'Customer', 'coach' => 'Coach'];
$profile = $user['role'] === 'customer'
    ? ($history[0] ?? ['reliability_score' => null, 'reliability_tier' => 'new_member', 'completed_count' => 0, 'no_show_count' => 0])
    : null;
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/admin/users') ?>">Users</a>
            <span class="breadcrumb-separator">/</span>
            <span>#<?= (int) $userId ?></span>
        </div>
        <h1 class="page-title"><?= e($user['name']) ?></h1>
        <div class="page-subtitle"><?= e($roleNames[$user['role']]) ?> account</div>
    </div>
    <?php if ($user['role'] !== 'admin'): ?>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Deactivate Account', 'The user loses access. A coach also has future sessions cancelled with full refunds.', 'Deactivate', '/admin/users/<?= (int) $userId ?>/deactivate')">
                Deactivate Account
            </button>
        </div>
    <?php endif; ?>
</div>

<div class="grid grid-cols-3 gap-6">
    <div class="card" style="padding: var(--space-6);">
        <h3 style="font-size: 15px; margin-bottom: 12px;">Account</h3>
        <div class="text-sm" style="display: flex; flex-direction: column; gap: 8px;">
            <div><span class="text-muted">Email:</span> <strong><?= e($user['email']) ?></strong></div>
            <div><span class="text-muted">Phone:</span> <strong><?= e($user['phone']) ?></strong></div>
            <div><span class="text-muted">Role:</span> <strong><?= e($roleNames[$user['role']]) ?></strong></div>
            <?php if ($profile !== null): ?>
                <div><span class="text-muted">Reliability:</span>
                    <span class="tier-badge tier-<?= e($profile['reliability_tier']) ?>"><?= $profile['reliability_score'] === null ? '' : e(round($profile['reliability_score'])) . '% ' ?><?= e(status_label($profile['reliability_tier'])) ?></span>
                </div>
                <div><span class="text-muted">Completed / no-shows:</span> <strong><?= (int) $profile['completed_count'] ?> / <?= (int) $profile['no_show_count'] ?></strong></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card" style="grid-column: span 2;">
        <div class="card-header">
            <h3 style="font-size: 15px; margin-bottom: 0;"><?= ['customer' => 'Bookings', 'owner' => 'Venues', 'coach' => 'Coaching Sessions', 'admin' => 'Activity'][$user['role']] ?> (<?= count($history) ?>)</h3>
        </div>
        <?php if ($history === []): ?>
            <div class="card-body text-sm">Nothing to show yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <tbody>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <?php if ($user['role'] === 'customer'): ?>
                                    <td><strong>#<?= (int) $h['id'] ?></strong></td>
                                    <td><?= e($h['venue_name']) ?><div class="text-xs text-muted"><?= e($h['court_name']) ?></div></td>
                                    <td><?= e(format_datetime($h['starts_at'])) ?></td>
                                    <td><?= e(lkr($h['amount'])) ?></td>
                                    <td><?= status_badge($h['status']) ?></td>
                                <?php elseif ($user['role'] === 'owner'): ?>
                                    <td><strong><?= e($h['name']) ?></strong><div class="text-xs text-muted"><?= e($h['city']) ?></div></td>
                                    <td><?= (int) $h['court_count'] ?> <?= (int) $h['court_count'] === 1 ? 'court' : 'courts' ?></td>
                                    <td><?= status_badge($h['status']) ?></td>
                                    <td style="text-align: right;"><a href="<?= url('/admin/venues/' . $h['id']) ?>" class="btn btn-sm btn-outline">View</a></td>
                                <?php else: ?>
                                    <td><strong><?= e($h['title']) ?></strong><div class="text-xs text-muted"><?= e($h['venue_name']) ?> · <?= e($h['court_name']) ?></div></td>
                                    <td><?= e(format_datetime($h['starts_at'])) ?></td>
                                    <td><?= (int) $h['registration_count'] ?> / <?= (int) $h['capacity'] ?></td>
                                    <td><?= status_badge($h['status']) ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
