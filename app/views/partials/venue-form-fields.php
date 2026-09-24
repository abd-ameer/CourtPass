<?php
/**
 * Venue fields (venues + venue_sports). Expects $venue, $sportTypes, $errors.
 */
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
$chosen = array_map('intval', $venue['sport_type_ids'] ?? []);
?>
<div class="form-group">
    <label class="form-label" for="venueName">Venue Name <span class="required-star">*</span></label>
    <input type="text" name="name" id="venueName" class="form-control<?= $invalid('name') ?>" value="<?= e($venue['name'] ?? '') ?>" maxlength="150" required>
    <span class="form-feedback invalid"><?= e($errors['name'] ?? '') ?></span>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
    <div class="form-group">
        <label class="form-label" for="venueCity">City <span class="required-star">*</span></label>
        <input type="text" name="city" id="venueCity" class="form-control<?= $invalid('city') ?>" value="<?= e($venue['city'] ?? '') ?>" maxlength="100" required>
        <span class="form-feedback invalid"><?= e($errors['city'] ?? '') ?></span>
    </div>
    <div class="form-group">
        <label class="form-label" for="venuePhone">Contact Phone <span class="required-star">*</span></label>
        <input type="tel" name="contact_phone" id="venuePhone" class="form-control<?= $invalid('contact_phone') ?>" value="<?= e($venue['contact_phone'] ?? '') ?>" maxlength="20" required>
        <span class="form-feedback invalid"><?= e($errors['contact_phone'] ?? '') ?></span>
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="venueAddress">Address <span class="required-star">*</span></label>
    <input type="text" name="address" id="venueAddress" class="form-control<?= $invalid('address') ?>" value="<?= e($venue['address'] ?? '') ?>" maxlength="255" required>
    <span class="form-feedback invalid"><?= e($errors['address'] ?? '') ?></span>
</div>

<div class="form-group">
    <span class="form-label">Sport Types <span class="required-star">*</span></span>
    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 6px;">
        <?php foreach ($sportTypes as $sport): ?>
            <label class="inline-flex items-center gap-1" style="font-size: 13px;">
                <input type="checkbox" name="sport_type_ids[]" value="<?= e($sport['id']) ?>" <?= in_array((int) $sport['id'], $chosen, true) ? 'checked' : '' ?>>
                <?= e($sport['name']) ?>
            </label>
        <?php endforeach; ?>
    </div>
    <span class="form-feedback invalid"><?= e($errors['sport_type_ids'] ?? '') ?></span>
</div>

<div class="form-group">
    <label class="form-label" for="venueDescription">Description</label>
    <textarea name="description" id="venueDescription" class="form-control" rows="3" maxlength="2000"><?= e($venue['description'] ?? '') ?></textarea>
</div>
