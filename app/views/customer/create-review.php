<?php
/**
 * @var string $target 'venue' or 'coach'
 * @var int|null $bookingId venue review of this booking
 * @var int|null $registrationId coach review of this registration
 * @var int|null $reviewId editing an existing review
 * Optional (venue reviews): string $subject, int $rating, string $comment, ?string $reviewUntil, array $errors
 */
$isEdit = $reviewId !== null;
if ($isEdit) {
    $action = '/customer/reviews/' . $reviewId;
} elseif ($target === 'coach') {
    $action = '/customer/registrations/' . $registrationId . '/review';
} else {
    $action = '/customer/bookings/' . $bookingId . '/review';
}
// TODO: the coach review subject, rating and comment come from the registration (coach reviews).
$subject ??= $target === 'coach' ? 'Coach Ashan Weerasinghe (Beginner Badminton Basics)' : 'Colombo Sports Hub (Badminton Court 1)';
$rating ??= 5;
$comment ??= '';
$reviewUntil ??= null;
$errors ??= [];
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/customer/reviews') ?>">My Reviews</a>
            <span class="breadcrumb-separator">/</span>
            <span><?= $isEdit ? 'Edit Review' : 'Write Review' ?></span>
        </div>
        <h1 class="page-title"><?= $isEdit ? 'Edit Your Review' : ($target === 'coach' ? 'Review Your Coach' : 'Review the Venue') ?></h1>
        <div class="page-subtitle"><?= e($subject) ?></div>
    </div>
</div>

<div class="card" style="max-width: 620px; padding: var(--space-8); margin: 0 auto;">
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: var(--space-6); font-size: 12px; color: #1e40af;">
        <strong>Verified reviews only:</strong>
        <?= $target === 'coach'
            ? 'you can review a coach once per session you were marked Attended for, within 7 days of the session.'
            : 'you can review a venue once per checked-in booking, within 7 days of the check-in.' ?>
        <?php if ($reviewUntil !== null): ?>
            <br>This booking can be reviewed until <?= e(format_datetime($reviewUntil)) ?>.
        <?php endif; ?>
    </div>

    <form id="reviewForm" method="POST" action="<?= url($action) ?>" novalidate>
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="form-group">
            <span class="form-label">Rating (1 to 5 stars) <span class="required-star">*</span></span>
            <div style="display: flex; gap: 8px; font-size: 28px; cursor: pointer;" id="starPicker">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" data-value="<?= $i ?>" aria-label="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>" style="background: none; border: 0; cursor: pointer; font-size: 28px; color: <?= $i <= $rating ? '#d97706' : '#d1d5db' ?>;">&#9733;</button>
                <?php endfor; ?>
            </div>
            <input type="hidden" name="rating" id="selectedRating" value="<?= e($rating) ?>">
            <?php if (isset($errors['rating'])): ?>
                <span class="form-feedback invalid" style="display: block;"><?= e($errors['rating']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="form-label" for="reviewComment">Comment <span class="required-star">*</span></label>
            <textarea name="comment" id="reviewComment" class="form-control<?= $invalid('comment') ?>" rows="4" maxlength="2000" required><?= e($comment) ?></textarea>
            <span class="form-feedback invalid"><?= e($errors['comment'] ?? '') ?></span>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
            <a href="<?= url('/customer/reviews') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Review' : 'Publish Review' ?> &rarr;</button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('#starPicker button').forEach((btn) => {
    btn.addEventListener('click', () => {
        const value = Number(btn.dataset.value);
        document.getElementById('selectedRating').value = value;
        document.querySelectorAll('#starPicker button').forEach((b) => {
            b.style.color = Number(b.dataset.value) <= value ? '#d97706' : '#d1d5db';
        });
    });
});
CourtPassApp.setupFormValidation('reviewForm');
</script>
