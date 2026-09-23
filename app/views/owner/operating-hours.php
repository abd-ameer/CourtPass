<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Operating Hours</span>
 </div>
 <h1 class="page-title">Court Operating Hours & Slot Generation (UC-VO-04, UC-VO-05)</h1>
 <div class="page-subtitle">Configure opening and closing schedules per day. System automatically divides into fixed 1-hour slots.</div>
 </div>

 <button class="btn btn-primary" onclick="CourtPassApp.showToast('success', 'Schedules Saved', 'Weekly schedule updated and booking slots regenerated.');">
 Save All Schedules
 </button>
 </div>

 <div class="card">
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Day of Week</th>
 <th>Status</th>
 <th>Opening Time</th>
 <th>Closing Time</th>
 <th>Daily Slots Generated</th>
 <th>Action</th>
 </tr>
 </thead>
 <tbody>
 <?php
 $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
 foreach ($days as $idx => $day):
 ?>
 <tr>
 <td><strong><?php echo $day; ?></strong></td>
 <td><span class="badge badge-active">Open</span></td>
 <td>
 <input type="text" class="form-control" style="width: 120px; padding: 6px 10px;" value="06:00 AM">
 </td>
 <td>
 <input type="text" class="form-control" style="width: 120px; padding: 6px 10px;" value="11:00 PM">
 </td>
 <td>
 <strong style="color: var(--color-primary-active);">17 Fixed 1-Hr Slots</strong>
 </td>
 <td>
 <button class="btn btn-sm btn-outline" onclick="CourtPassApp.showToast('info', 'Schedule Adjusted', 'Slots for <?php echo $day; ?> regenerated.');">
 Sync Slots
 </button>
 </td>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
 </div>
