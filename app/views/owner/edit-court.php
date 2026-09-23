<?php
$is_edit = $is_edit ?? (isset($_GET['id']) || isset($_GET['edit']));
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/owner/courts') ?>">Courts</a>
 <span class="breadcrumb-separator">/</span>
 <span><?php echo $is_edit ? 'Edit Court' : 'Add Court'; ?></span>
 </div>
 <h1 class="page-title"><?php echo $is_edit ? 'Edit Court Details (UC-VO-03)' : 'Add Court to Venue (UC-VO-03)'; ?></h1>
 <div class="page-subtitle">Configure specifications, hourly pricing, and slot schedule generation.</div>
 </div>
 </div>

 <div class="card" style="max-width: 680px; padding: var(--space-8); margin: 0 auto;">
 <form onsubmit="handleCourtSave(event)">
 
 <div class="form-group">
 <label class="form-label">Court / Pitch Name <span class="required-star">*</span></label>
 <input type="text" class="form-control" value="<?php echo $is_edit ? 'Turf Court 1 (Floodlit)' : ''; ?>" placeholder="e.g., Badminton Court B or Futsal Pitch 1" required>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
 <div class="form-group">
 <label class="form-label">Sport Discipline <span class="required-star">*</span></label>
 <select class="form-select" required>
 <option value="futsal" <?php if($is_edit) echo 'selected'; ?>> Futsal</option>
 <option value="badminton"> Badminton</option>
 <option value="pickleball"> Pickleball</option>
 <option value="squash"> Squash</option>
 <option value="table-tennis"> Table Tennis</option>
 <option value="billiards"> Billiards</option>
 <option value="carrom"> Carrom</option>
 </select>
 </div>

 <div class="form-group">
 <label class="form-label">Hourly Rate (LKR) <span class="required-star">*</span></label>
 <input type="number" class="form-control" value="<?php echo $is_edit ? '5000' : '3000'; ?>" step="100" min="500" required>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Surface Material / Flooring Specification</label>
 <input type="text" class="form-control" value="<?php echo $is_edit ? 'FIFA-Grade Synthetic Grass with High Drainage Shock Pad' : ''; ?>" placeholder="e.g. Teak wood, Yonex rubber mat, Acrylic cushion...">
 </div>

 <div style="background: var(--color-bg-subtle); border-radius: var(--radius-md); padding: 14px; margin-bottom: 20px;">
 <div style="font-size: 13px; font-weight: 700; color: var(--color-text-title); margin-bottom: 6px;">
 Fixed 1-Hour Slot Engine (UC-VO-05):
 </div>
 <div class="text-xs text-muted" style="line-height: 1.5;">
 All bookings and coaching clinics are strictly divided into fixed 1-hour increments. Operating hours (06:00 AM - 11:00 PM) will generate 17 distinct bookable slots per day.
 </div>
 </div>

 <div class="form-group">
 <label class="inline-flex items-center gap-2" style="font-size: 14px; font-weight: 600; cursor: pointer;">
 <input type="checkbox" checked> Court is Active and Open for Public Reservations
 </label>
 </div>

 <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
 <a href="<?= url('/owner/courts') ?>" class="btn btn-secondary">Cancel</a>
 <button type="submit" class="btn btn-primary">Save Court & Generate Slots &rarr;</button>
 </div>
 </form>
 </div>

 </div>
 </div>
</div>

<script>
function handleCourtSave(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Court Saved', 'Court configuration updated and 1-hour slots generated.');
 setTimeout(() => {
 window.location.href = '<?= url('/owner/courts') ?>';
 }, 1000);
}
</script>
