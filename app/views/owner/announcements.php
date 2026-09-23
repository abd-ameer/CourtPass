<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Announcements</span>
 </div>
 <h1 class="page-title">Venue Announcements (UC-VO-15)</h1>
 <div class="page-subtitle">Publish Operational notices (maintenance, court repairs) or Promotional updates for player discovery.</div>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;">
 
 <!-- Create Announcement Form -->
 <div class="card" style="padding: var(--space-6);">
 <h3 style="font-size: 16px; margin-bottom: 14px;">Publish Announcement</h3>
 <form onsubmit="handlePostAnnouncement(event)">
 <div class="form-group">
 <label class="form-label">Announcement Type <span class="required-star">*</span></label>
 <select class="form-select" required>
 <option value="operational">Operational (e.g. maintenance, hours)</option>
 <option value="promotional">Promotional (e.g. tournament, discounts)</option>
 </select>
 </div>

 <div class="form-group">
 <label class="form-label">Headline Title <span class="required-star">*</span></label>
 <input type="text" class="form-control" placeholder="e.g., Floodlight upgrade completed on Court 1" required>
 </div>

 <div class="form-group">
 <label class="form-label">Announcement Body <span class="required-star">*</span></label>
 <textarea class="form-control" rows="4" placeholder="Provide full details visible on your public venue listing..." required></textarea>
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
 <span class="badge badge-confirmed">2 Active</span>
 </div>
 <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
 
 <div style="padding: 14px; background: var(--color-bg-subtle); border-radius: var(--radius-md); border-left: 3px solid var(--color-primary);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
 <span class="badge badge-confirmed">Operational</span>
 <span class="text-xs text-muted">Sept 20, 2026</span>
 </div>
 <h4 style="font-size: 15px; margin-bottom: 4px;">Monsoon Synthetic Turf Resurfacing Complete</h4>
 <p class="text-sm text-muted" style="margin-bottom: 8px;">
 Turf Court 1 has been upgraded with FIFA-standard shock pads and high-drainage infill. Optimal play during light rain is now fully supported.
 </p>
 <button class="btn btn-sm btn-ghost" style="color: var(--color-danger); padding: 0;" onclick="CourtPassApp.showToast('info', 'Removed', 'Announcement archived.');">
 Remove Notice
 </button>
 </div>

 <div style="padding: 14px; background: var(--color-bg-subtle); border-radius: var(--radius-md); border-left: 3px solid #7c3aed;">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
 <span class="badge" style="background: #f5f3ff; color: #7c3aed;">Promotional</span>
 <span class="text-xs text-muted">Sept 16, 2026</span>
 </div>
 <h4 style="font-size: 15px; margin-bottom: 4px;">Weekend Futsal Night League Slots Available</h4>
 <p class="text-sm text-muted" style="margin-bottom: 8px;">
 Register for corporate friendly night matches every Friday and Saturday between 8 PM and 11 PM.
 </p>
 <button class="btn btn-sm btn-ghost" style="color: var(--color-danger); padding: 0;" onclick="CourtPassApp.showToast('info', 'Removed', 'Announcement archived.');">
 Remove Notice
 </button>
 </div>

 </div>
 </div>

 </div>

 </div>
 </div>
</div>

<script>
function handlePostAnnouncement(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Announcement Published (UC-VO-15)', 'Your announcement is now visible on the public venue profile.');
 setTimeout(() => window.location.reload(), 1000);
}
</script>
