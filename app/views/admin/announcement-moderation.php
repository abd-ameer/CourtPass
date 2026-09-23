<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Announcements</span>
 </div>
 <h1 class="page-title">Announcement Moderation </h1>
 <div class="page-subtitle">Review and manage announcements published by venue owners across the platform.</div>
 </div>

 <div>
 <button class="btn btn-primary" onclick="CourtPassApp.showToast('Opening Global Platform Broadcast Composer...', 'info')">
 + Publish Platform-Wide Notice
 </button>
 </div>
 </div>

 <!-- Published Announcements Table -->
 <div class="card">
 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Venue</th>
 <th>Announcement Title & Content</th>
 <th>Type</th>
 <th>Dates Active</th>
 <th>Status</th>
 <th>Action</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Colombo Futsal Club</strong>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Dehiwala</div>
 </td>
 <td>
 <div style="font-weight: 700; color: var(--color-text-heading);">Annual Futsal Monsoon Carnival 2026</div>
 <div style="font-size: 11px; color: var(--color-text-muted); max-width: 380px;">
 Registrations now open for 5-a-side teams. Cash prizes worth LKR 150,000!
 </div>
 </td>
 <td><span class="badge badge-primary">Promotional</span></td>
 <td>20 Sep - 30 Sep 2026</td>
 <td><span class="badge badge-confirmed">Published</span></td>
 <td>
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger);" onclick="CourtPassApp.showToast('Announcement removed', 'error')">Remove</button>
 </td>
 </tr>

 <tr>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Longdon Place</div>
 </td>
 <td>
 <div style="font-weight: 700; color: var(--color-text-heading);">Court 3 Floor Recoating Maintenance</div>
 <div style="font-size: 11px; color: var(--color-text-muted); max-width: 380px;">
 Court 3 will be closed for surface polishing from 08:00 to 14:00 on 25th September.
 </div>
 </td>
 <td><span class="badge badge-warning">Maintenance</span></td>
 <td>24 Sep - 25 Sep 2026</td>
 <td><span class="badge badge-confirmed">Published</span></td>
 <td>
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger);" onclick="CourtPassApp.showToast('Announcement removed', 'error')">Remove</button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
