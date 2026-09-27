<?php
/** @var array $venues the owner's approved venues @var array $announcements active announcements on the owner's listed venues */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Announcements</span>
 </div>
 <h1 class="page-title">Venue Announcements</h1>
 <div class="page-subtitle">Post operational notices (maintenance, hours) or promotional updates on your public venue pages.</div>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;">
 
 <!-- Create Announcement Form -->
 <div class="card" style="padding: var(--space-6);">
 <h3 style="font-size: 16px; margin-bottom: 14px;">Publish Announcement</h3>
 <form method="POST" action="<?= url('/owner/announcements') ?>">
 <?= csrf_field() ?>
 <div class="form-group">
 <label class="form-label" for="annVenue">Venue <span class="required-star">*</span></label>
 <select name="venue_id" id="annVenue" class="form-select" required>
                    <?php foreach ($venues as $v): ?>
                        <option value="<?= (int) $v['id'] ?>"><?= e($v['name']) ?></option>
                    <?php endforeach; ?>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Announcement Type <span class="required-star">*</span></label>
 <select name="type" class="form-select" required>
 <option value="operational">Operational (e.g. maintenance, hours)</option>
 <option value="promotional">Promotional (e.g. tournament, discounts)</option>
 </select>
 </div>

 <div class="form-group">
 <label class="form-label">Headline Title <span class="required-star">*</span></label>
 <input type="text" name="title" class="form-control" maxlength="150" placeholder="e.g., Court B closed for maintenance on Saturday" required>
 </div>

 <div class="form-group">
 <label class="form-label">Announcement Body <span class="required-star">*</span></label>
 <textarea name="body" class="form-control" rows="4" maxlength="2000" placeholder="Provide full details visible on your public venue listing..." required></textarea>
 </div>

 <button type="submit" class="btn btn-primary btn-block">
 Publish to Venue Page &rarr;
 </button>
 </form>
 </div>

 <!-- Active Announcements List -->
    <div class="card">
        <div class="card-header">
            <h3 style="font-size: 16px; margin-bottom: 0;">Published Announcements</h3>
            <span class="badge badge-confirmed"><?= count($announcements) ?> Active</span>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
            <?php if ($announcements === []): ?>
                <p class="text-sm" style="margin-bottom: 0;">No active announcements. Announcements appear on the public page of an approved, listed venue.</p>
            <?php endif; ?>
            <?php foreach ($announcements as $a): ?>
                <?php $promo = $a['type'] === 'promotional'; ?>
                <div style="padding: 14px; background: var(--color-bg-subtle); border-radius: var(--radius-md); border-left: 3px solid <?= $promo ? '#7c3aed' : 'var(--color-primary)' ?>;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; gap: 8px;">
                        <span class="badge <?= $promo ? '' : 'badge-confirmed' ?>"<?= $promo ? ' style="background: #f5f3ff; color: #7c3aed;"' : '' ?>><?= $promo ? 'Promotional' : 'Operational' ?></span>
                        <span class="text-xs text-muted"><?= e($a['venue_name']) ?> · <?= e(format_datetime($a['posted_at'], false)) ?></span>
                    </div>
                    <h4 style="font-size: 15px; margin-bottom: 4px;"><?= e($a['title']) ?></h4>
                    <p class="text-sm text-muted" style="margin-bottom: 0;"><?= e($a['body']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
