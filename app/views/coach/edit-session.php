<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions') ?>">Sessions</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions/' . $sessionId) ?>">#<?= e($sessionId) ?></a>
 <span class="breadcrumb-separator">/</span>
 <span>Edit</span>
 </div>
 <h1 class="page-title">Edit Session — Badminton Fundamentals</h1>
 <div class="page-subtitle">Modify session details. Note: capacity cannot be reduced below the current number of registrations (6).</div>
 </div>
 </div>

 <form method="POST" action="<?= url('/coach/sessions/' . $sessionId) ?>">
 <?= csrf_field() ?>
 <input type="hidden" name="_method" value="PUT">
 <div class="grid grid-cols-3 gap-6">
 
 <div style="grid-column: span 2;">
 
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Session Details</h3>
 <span class="badge badge-info">Session #2</span>
 </div>
 <div class="card-body">
 <div class="form-group">
 <label class="form-label">Session Title *</label>
 <input type="text" name="title" class="form-input" value="Intermediate Rally Drills" maxlength="150" required>
 </div>
 <div class="form-group">
 <label class="form-label">Description *</label>
 <textarea name="description" class="form-input" rows="4" maxlength="2000" required>Consistency and shot placement drills.</textarea>
 </div>
 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Sport</label>
 <input type="text" class="form-input" value=" Badminton" disabled style="background: var(--color-bg-page);">
 <div class="form-hint">Sport cannot be changed after creation.</div>
 </div>
 
 </div>
 </div>
 </div>

 <!-- Venue & Time (Read-only) -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Venue & Schedule</h3>
 <span class="badge badge-warning" style="font-size: 11px;">Cannot change venue/time after booking</span>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-3 gap-4">
 <div class="form-group">
 <label class="form-label">Venue</label>
 <input type="text" class="form-input" value="Colombo Sports Hub" disabled style="background: var(--color-bg-page);">
 </div>
 <div class="form-group">
 <label class="form-label">Court</label>
 <input type="text" class="form-input" value="Badminton Court 2" disabled style="background: var(--color-bg-page);">
 </div>
 <div class="form-group">
 <label class="form-label">Date & Time</label>
 <input type="text" class="form-input" value="Tue 29 Sep 2026 · 08:00" disabled style="background: var(--color-bg-page);">
 </div>
 </div>
 </div>
 </div>

 <!-- Capacity & Pricing -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Capacity & Pricing</h3>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Max Participants *</label>
 <input type="number" name="capacity" class="form-input" value="4" min="1" max="50" required>
 <div class="form-hint" style="color: var(--color-warning);"> Minimum 6 — cannot reduce below current registrations.</div>
 </div>
 <div class="form-group">
 <label class="form-label">Fee per Person (LKR)</label>
 <div class="text-xs text-muted">The fee can only change before anyone registers.</div>
 <input type="number" name="fee" class="form-input" value="1800" min="0" step="0.01" required>
 <div class="form-hint">Fee cannot be changed after students have registered.</div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- Right Sidebar -->
 <div>
 <div class="card" style="position: sticky; top: 80px;">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Current Status</h3>
 </div>
 <div class="card-body">
 <div style="font-size: 13px;">
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Status</span>
 <span class="badge badge-confirmed">Upcoming</span>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Registrations</span>
 <strong>6 / 8</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Revenue So Far</span>
 <strong style="color: var(--color-primary);">LKR 9,000</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0;">
 <span class="text-muted">Visibility</span>
 <span class="badge badge-confirmed">Public</span>
 </div>
 </div>
 <div style="margin-top: var(--space-4); display: flex; flex-direction: column; gap: 8px;">
 <button type="submit" class="btn btn-primary" style="width: 100%;"> Save Changes</button>
 <a href="<?= url('/coach/sessions/2') ?>" class="btn btn-secondary" style="width: 100%; text-align: center;">Cancel</a>
 </div>
 </div>
 </div>
 </div>

 </div>
 </form>

 
 


