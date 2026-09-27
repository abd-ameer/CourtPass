<?php
/** @var int $coachId @var array $sessions the coach's upcoming public sessions */
// Sample profile matching the seed coach until coach profiles are built.
$sample = $coachId === 7 ? [
    'name' => 'Ashan Weerasinghe', 'verified' => true, 'sports' => 'Badminton, Pickleball', 'level' => 'Professional',
    'bio' => 'Former national-level badminton player. Coaching juniors and adults for 8 years.',
    'certs' => 'BWF Level 1 Coach', 'rating' => 4.0,
    'reviews' => [['student' => 'Saman Fernando', 'session' => 'Beginner Badminton Basics', 'rating' => 4, 'comment' => 'Very clear explanations, good for beginners.']],
] : null;
$name = $sample['name'] ?? ($sessions[0]['coach_name'] ?? null);
$stars = fn (int $n) => str_repeat('★', $n) . str_repeat('☆', 5 - $n);
?>
<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coaching') ?>">Coaching</a>
            <span class="breadcrumb-separator">/</span>
            <span><?= e($name ?? 'Coach') ?></span>
        </div>
        <?php if ($name === null): ?>
            <h1 style="font-size: var(--font-size-2xl); margin-top: var(--space-4);">Coach Profile</h1>
            <p class="text-sm text-muted">This coach has no public profile yet.</p>
        <?php else: ?>
            <div style="display: flex; align-items: center; gap: 10px; margin-top: var(--space-4); flex-wrap: wrap;">
                <h1 style="font-size: var(--font-size-2xl);"><?= e($name) ?></h1>
                <?php if ($sample['verified'] ?? ($sessions[0]['coach_verified'] ?? false)): ?>
                    <span class="badge badge-verified" style="font-size: 13px; padding: 4px 10px;">Verified Coach</span>
                <?php endif; ?>
            </div>
            <?php if ($sample !== null): ?>
                <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 0;">
                    <?= e($sample['sports']) ?> · <?= e($sample['level']) ?> · Rating <?= e(number_format($sample['rating'], 1)) ?> (<?= count($sample['reviews']) ?> verified <?= count($sample['reviews']) === 1 ? 'review' : 'reviews' ?>)
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<section style="padding: var(--space-8) 0;">
    <div class="container" style="display: flex; flex-direction: column; gap: var(--space-6);">
        <?php if ($sample !== null): ?>
            <div class="card">
                <div class="card-header"><h3 style="font-size: 16px; margin-bottom: 0;">About the Coach</h3></div>
                <div class="card-body">
                    <p style="line-height: 1.6; margin-bottom: 12px;"><?= e($sample['bio']) ?></p>
                    <div class="text-sm"><strong>Certifications:</strong> <?= e($sample['certs']) ?></div>
                    <div class="text-xs text-muted" style="margin-top: 6px;">The Verified badge is set by the Platform Admin after offline checks.</div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; margin-bottom: 0;">Upcoming Public Sessions</h3>
            </div>
            <?php if ($sessions === []): ?>
                <div class="card-body text-sm">No upcoming public sessions.</div>
            <?php endif; ?>
            <?php foreach ($sessions as $s): ?>
                <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid var(--color-border);">
                    <div>
                        <h4 style="font-size: 15px; margin-bottom: 2px;"><?= e($s['title']) ?></h4>
                        <div class="text-xs text-muted"><?= e($s['venue_name']) ?> · <?= e($s['court_name']) ?> · <?= e(format_datetime($s['starts_at'])) ?> (1 hour)</div>
                        <div class="badge badge-pending" style="margin-top: 6px;"><?= (int) $s['spots_left'] ?> <?= (int) $s['spots_left'] === 1 ? 'place' : 'places' ?> left</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <strong style="color: #7c3aed;"><?= e($s['fee_label']) ?></strong>
                        <a href="<?= url('/sessions/' . $s['id']) ?>" class="btn btn-sm btn-primary">View Session</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($sample !== null): ?>
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 16px; margin-bottom: 0;">Verified Student Feedback (<?= count($sample['reviews']) ?>)</h3>
                    <span class="badge badge-verified">Attended Sessions Only</span>
                </div>
                <div class="card-body">
                    <?php foreach ($sample['reviews'] as $r): ?>
                        <div style="display: flex; justify-content: space-between; gap: 10px;">
                            <strong><?= e($r['student']) ?></strong>
                            <span style="color: #f59e0b;"><?= $stars($r['rating']) ?></span>
                        </div>
                        <div class="text-xs text-muted" style="margin-bottom: 4px;"><?= e($r['session']) ?></div>
                        <p class="text-sm" style="margin-bottom: 0;"><?= e($r['comment']) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
