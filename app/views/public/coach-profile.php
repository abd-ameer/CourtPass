<?php
/** @var array $profile from CoachService::profile() @var array $sessions upcoming public sessions @var array $reviews active coach reviews */
$stars = fn (int $n) => str_repeat('★', $n) . str_repeat('☆', 5 - $n);
?>
<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= url('/') ?>">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coaching') ?>">Coaching</a>
            <span class="breadcrumb-separator">/</span>
            <span><?= e($profile['name']) ?></span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; margin-top: var(--space-4); flex-wrap: wrap;">
            <h1 style="font-size: var(--font-size-2xl);"><?= e($profile['name']) ?></h1>
            <?php if ($profile['is_verified']): ?>
                <span class="badge badge-verified" style="font-size: 13px; padding: 4px 10px;">Verified Coach</span>
            <?php endif; ?>
        </div>
        <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 0;">
            <?= e($profile['sport_names']) ?> · <?= e(ucfirst($profile['experience_level'])) ?> ·
            <?php if ($profile['avg_rating'] === null): ?>No reviews yet<?php else: ?>Rating <?= e(number_format($profile['avg_rating'], 1)) ?> (<?= (int) $profile['review_count'] ?> verified <?= $profile['review_count'] === 1 ? 'review' : 'reviews' ?>)<?php endif; ?>
        </p>
    </div>
</div>

<section style="padding: var(--space-8) 0;">
    <div class="container" style="display: flex; flex-direction: column; gap: var(--space-6);">
        <div class="card">
            <div class="card-header"><h3 style="font-size: 16px; margin-bottom: 0;">About the Coach</h3></div>
            <div class="card-body">
                <p style="line-height: 1.6; margin-bottom: 12px;"><?= e($profile['bio'] ?: 'This coach has not added a bio yet.') ?></p>
                <div class="text-sm" style="margin-bottom: 6px;"><strong>Certifications:</strong> <?= e($profile['certifications'] ?: 'None listed') ?></div>
                <div class="text-sm"><strong>Coaches at:</strong>
                    <?php if ($profile['venues'] === []): ?>
                        No venues yet
                    <?php else: ?>
                        <?php foreach ($profile['venues'] as $i => $v): ?><?= $i > 0 ? ', ' : '' ?><a href="<?= url('/venue/' . $v['slug']) ?>"><?= e($v['name']) ?></a><?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-muted" style="margin-top: 6px;">The Verified badge is set by the Platform Admin after offline checks.</div>
            </div>
        </div>

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

        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; margin-bottom: 0;">Verified Student Feedback (<?= count($reviews) ?>)</h3>
                <span class="badge badge-verified">Attended Sessions Only</span>
            </div>
            <?php if ($reviews === []): ?>
                <div class="card-body text-sm">No reviews yet. Students can review a session they attended within 7 days.</div>
            <?php endif; ?>
            <?php foreach ($reviews as $r): ?>
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border);">
                    <div style="display: flex; justify-content: space-between; gap: 10px;">
                        <strong><?= e($r['reviewer_name']) ?></strong>
                        <span style="color: #f59e0b;"><?= $stars($r['rating']) ?></span>
                    </div>
                    <div class="text-xs text-muted" style="margin-bottom: 4px;"><?= e($r['session_title']) ?> · <?= e(format_datetime($r['session_starts_at'], false)) ?></div>
                    <p class="text-sm" style="margin-bottom: 0;"><?= e($r['comment']) ?></p>
                    <?php if ($r['response_text'] !== null): ?>
                        <div style="margin-top: 8px; padding: 8px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);" class="text-sm"><strong>Coach response:</strong> <?= e($r['response_text']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
