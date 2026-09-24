<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Coach Management</span>
 </div>
 <h1 class="page-title">Coach Venue Operating Requests (UC-VO-20, UC-VO-21)</h1>
 <div class="page-subtitle">Review independent coaches requesting permission to conduct paid sessions at Colombo Futsal Club.</div>
 </div>
 </div>

 <!-- Pending Coach Requests Table -->
 <div class="card" style="margin-bottom: var(--space-8); border: 1.5px solid #fde68a;">
 <div class="card-header" style="background: #fffbeb;">
 <h3 style="font-size: 15px; color: #92400e; margin-bottom: 0;">Pending Coach Approval Requests (1)</h3>
 <span class="badge badge-warning">Review Pending</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Coach Profile</th>
 <th>Sport & Specialty</th>
 <th>Accreditation & Experience</th>
 <th>Requested Courts</th>
 <th style="text-align: right;">Owner Decision</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Coach Chaminda Silva</strong>
 <div class="text-xs text-muted">Email: chaminda@coach.lk · +94 77 998 1234</div>
 </td>
 <td>
 <span class="badge badge-confirmed"> Badminton</span>
 <div class="text-xs text-muted" style="margin-top: 4px;">Footwork & Stamina Drills</div>
 </td>
 <td>
 <strong>WSF Level 1 Coach</strong>
 <div class="text-xs text-muted">7 Years competitive coaching</div>
 </td>
 <td>
 <strong>Wooden Badminton Court A</strong>
 </td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Approve Coach', 'Allow this coach to create sessions at your venue?', 'Approve', '/owner/coach-requests/2/approve')">
 Approve Coach
 </button>
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Decline Coach Request', 'A reason is required and is shown to the coach.', '/owner/coach-requests/2/decline')">
 Decline...
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Currently Approved Active Coaches (UC-VO-21, UC-VO-22) -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Approved Coaches at this Venue (2)</h3>
 <span class="text-xs text-muted">Coaches authorized to schedule 1-hour sessions</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Coach Name</th>
 <th>Sport Discipline</th>
 <th>Admin Verification</th>
 <th>Active Sessions</th>
 <th>Approval Status</th>
 <th style="text-align: right;">Action</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Coach Dilshan Perera</strong>
 <div class="text-xs text-muted">BWF Level 2 Senior Coach</div>
 </td>
 <td> Badminton</td>
 <td><span class="badge badge-verified">Verified Badge</span></td>
 <td><strong>1 Session</strong> (Sept 24, 07:00 PM)</td>
 <td><span class="badge badge-approved">Approved</span></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Revoke Approval', 'The coach keeps existing sessions but cannot create new ones here.', 'Revoke', '/owner/coach-requests/1/revoke')">
 Revoke Approval...
 </button>
 </td>
 </tr>
 <tr>
 <td>
 <strong>Coach Shanilka Fernando</strong>
 <div class="text-xs text-muted">AFC "C" License Futsal Trainer</div>
 </td>
 <td> Futsal</td>
 <td><span class="badge badge-verified">Verified Badge</span></td>
 <td><strong>1 Session</strong> (Sept 25, 08:00 PM)</td>
 <td><span class="badge badge-approved">Approved</span></td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Revoke Approval', 'The coach keeps existing sessions but cannot create new ones here.', 'Revoke', '/owner/coach-requests/1/revoke')">
 Revoke Approval...
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 
 


