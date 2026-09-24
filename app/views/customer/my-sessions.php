<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>My Coaching Registrations</span>
 </div>
 <h1 class="page-title">My Coaching Sessions</h1>
 <div class="page-subtitle">Track your masterclasses, attendance verification, and coach reviews.</div>
 </div>

 <a href="<?= url('/coaching') ?>" class="btn btn-primary" style="background: #7c3aed; border-color: #7c3aed;">
 + Find More Masterclasses
 </a>
 </div>

 <div class="card">
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Registration ID</th>
 <th>Clinic & Sport</th>
 <th>Coach</th>
 <th>Venue & Date</th>
 <th>Fee Paid</th>
 <th>Attendance Status</th>
 <th style="text-align: right;">Action</th>
 </tr>
 </thead>
 <tbody>
 
 <!-- Session 1: Upcoming -->
 <tr>
 <td>
 <strong>#3</strong>
 <div class="text-xs text-muted">Paid Online (PayHere)</div>
 </td>
 <td>
 <strong>Badminton Jump Smash Mastery</strong>
 <div class="text-xs text-muted">1-Hour Intensive Clinic</div>
 </td>
 <td>
 <div style="display: flex; align-items: center; gap: 6px;">
 <strong style="font-size: 13px;">Coach Dilshan Perera</strong>
 <span class="badge badge-verified" style="font-size: 10px; padding: 1px 5px;">Verified</span>
 </div>
 </td>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Sept 24, 2026 · 07:00 PM - 08:00 PM</div>
 </td>
 <td>
 <strong>LKR 3,500</strong>
 </td>
 <td>
 <span class="badge badge-confirmed">Registered</span>
 </td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Cancel Registration', 'Cancel this registration? The refund depends on how long before the session you cancel.', 'Cancel Registration', '/customer/registrations/3/cancel')">
 Cancel Registration
 </button>
 </td>
 </tr>

 <!-- Session 2: Past Attended -->
 <tr>
 <td>
 <strong>#3</strong>
 <div class="text-xs text-muted">Paid Online (PayHere)</div>
 </td>
 <td>
 <strong>Squash Length Control & Footwork</strong>
 <div class="text-xs text-muted">1-Hour Technical Clinic</div>
 </td>
 <td>
 <div style="display: flex; align-items: center; gap: 6px;">
 <strong style="font-size: 13px;">Coach Chaminda Silva</strong>
 </div>
 </td>
 <td>
 <strong>Otters Club Squash</strong>
 <div class="text-xs text-muted">Sept 10, 2026 · 06:00 PM - 07:00 PM</div>
 </td>
 <td>
 <strong>LKR 3,800</strong>
 </td>
 <td>
 <span class="badge badge-completed">Attended</span>
 </td>
 <td style="text-align: right;">
 <a href="<?= url('/customer/registrations/1/review') ?>" class="btn btn-sm btn-outline">
 Review Coach
 </a>
 </td>
 </tr>

 </tbody>
 </table>
 </div>
 </div>

 
 


