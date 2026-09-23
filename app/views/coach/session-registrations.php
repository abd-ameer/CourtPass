<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions') ?>">Sessions</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/session-details') ?>?id=CS-201">#CS-201</a>
 <span class="breadcrumb-separator">/</span>
 <span>Registrations</span>
 </div>
 <h1 class="page-title">Session Registrations — Badminton Fundamentals</h1>
 <div class="page-subtitle">Participant list with contact details, payment status, and attendance tracking for session #CS-201.</div>
 </div>
 <div style="display: flex; gap: 10px;">
 <a href="<?= url('/coach/attendance') ?>?id=CS-201" class="btn btn-primary"> Mark Attendance</a>
 <button type="button" class="btn btn-outline" onclick="CourtPassApp.showToast('info', 'Export', 'Participant list downloaded as CSV.')"> Export CSV</button>
 </div>
 </div>

 <!-- Session Info Summary -->
 <div style="display: flex; gap: var(--space-4); margin-bottom: var(--space-6); flex-wrap: wrap;">
 <div style="background: #e6f8f0; border-radius: var(--radius-lg); padding: 12px 20px; display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <div>
 <div class="text-xs text-muted">Date & Time</div>
 <div style="font-weight: 600; font-size: 13px;">Sept 22, 2026 · 05:00 PM</div>
 </div>
 </div>
 <div style="background: #dbeafe; border-radius: var(--radius-lg); padding: 12px 20px; display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <div>
 <div class="text-xs text-muted">Venue</div>
 <div style="font-weight: 600; font-size: 13px;">CR&FC · Court 2</div>
 </div>
 </div>
 <div style="background: #fef3c7; border-radius: var(--radius-lg); padding: 12px 20px; display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <div>
 <div class="text-xs text-muted">Capacity</div>
 <div style="font-weight: 600; font-size: 13px;">6 / 8 Registered</div>
 </div>
 </div>
 <div style="background: #d1fae5; border-radius: var(--radius-lg); padding: 12px 20px; display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <div>
 <div class="text-xs text-muted">Revenue</div>
 <div style="font-weight: 600; font-size: 13px;">LKR 9,000</div>
 </div>
 </div>
 </div>

 <!-- Participants Table -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Registered Participants (6)</h3>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>#</th>
 <th>Student</th>
 <th>Contact</th>
 <th>Registered On</th>
 <th>Payment</th>
 <th>Attendance</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>1</td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div style="width: 32px; height: 32px; background: #dbeafe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1d4ed8; font-size: 12px;">NK</div>
 <div>
 <strong>Nuwan Karunanayake</strong>
 <div class="text-xs text-muted">Member since Aug 2025</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 13px;">nuwan.k@email.com</div>
 <div class="text-xs text-muted">+94 77 123 4567</div>
 </td>
 <td>Sept 22, 2026</td>
 <td><span class="badge badge-paid">Paid LKR 1,500</span></td>
 <td><span class="badge" style="background: var(--color-bg-page); border: 1px solid var(--color-border);">Pending</span></td>
 </tr>
 <tr>
 <td>2</td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div style="width: 32px; height: 32px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #065f46; font-size: 12px;">RP</div>
 <div>
 <strong>Ruwan Perera</strong>
 <div class="text-xs text-muted">Member since Jan 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 13px;">ruwan.p@email.com</div>
 <div class="text-xs text-muted">+94 71 987 6543</div>
 </td>
 <td>Sept 21, 2026</td>
 <td><span class="badge badge-paid">Paid LKR 1,500</span></td>
 <td><span class="badge" style="background: var(--color-bg-page); border: 1px solid var(--color-border);">Pending</span></td>
 </tr>
 <tr>
 <td>3</td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div style="width: 32px; height: 32px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #92400e; font-size: 12px;">KD</div>
 <div>
 <strong>Kasun De Silva</strong>
 <div class="text-xs text-muted">Member since Mar 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 13px;">kasun.ds@email.com</div>
 <div class="text-xs text-muted">+94 76 456 7890</div>
 </td>
 <td>Sept 20, 2026</td>
 <td><span class="badge badge-paid">Paid LKR 1,500</span></td>
 <td><span class="badge" style="background: var(--color-bg-page); border: 1px solid var(--color-border);">Pending</span></td>
 </tr>
 <tr>
 <td>4</td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div style="width: 32px; height: 32px; background: #fce7f3; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #be185d; font-size: 12px;">MA</div>
 <div>
 <strong>Malini Amarasinghe</strong>
 <div class="text-xs text-muted">Member since Feb 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 13px;">malini.a@email.com</div>
 <div class="text-xs text-muted">+94 70 321 6543</div>
 </td>
 <td>Sept 19, 2026</td>
 <td><span class="badge badge-paid">Paid LKR 1,500</span></td>
 <td><span class="badge" style="background: var(--color-bg-page); border: 1px solid var(--color-border);">Pending</span></td>
 </tr>
 <tr>
 <td>5</td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div style="width: 32px; height: 32px; background: #ede9fe; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #6d28d9; font-size: 12px;">TF</div>
 <div>
 <strong>Tharindu Fernando</strong>
 <div class="text-xs text-muted">Member since May 2025</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 13px;">tharindu.f@email.com</div>
 <div class="text-xs text-muted">+94 72 654 3210</div>
 </td>
 <td>Sept 18, 2026</td>
 <td><span class="badge badge-paid">Paid LKR 1,500</span></td>
 <td><span class="badge" style="background: var(--color-bg-page); border: 1px solid var(--color-border);">Pending</span></td>
 </tr>
 <tr>
 <td>6</td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div style="width: 32px; height: 32px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #92400e; font-size: 12px;">DS</div>
 <div>
 <strong>Dhanushi Senanayake</strong>
 <div class="text-xs text-muted">Member since Jul 2026</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 13px;">dhanushi.s@email.com</div>
 <div class="text-xs text-muted">+94 75 789 0123</div>
 </td>
 <td>Sept 17, 2026</td>
 <td><span class="badge badge-paid">Paid LKR 1,500</span></td>
 <td><span class="badge" style="background: var(--color-bg-page); border: 1px solid var(--color-border);">Pending</span></td>
 </tr>
 </tbody>
 </table>
 </div>
