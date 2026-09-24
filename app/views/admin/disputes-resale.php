<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Resale Disputes</span>
 </div>
 <h1 class="page-title">Resale Disputes</h1>
 <div class="page-subtitle">Review disputes about released bookings, such as a missing 90% refund after the slot was rebooked.</div>
 </div>

 <div>
 <span class="badge badge-confirmed" style="font-size: 13px; padding: 6px 14px;">0 Urgent Resale Disputes</span>
 </div>
 </div>

 <!-- Resale Dispute History Table -->
 <div class="card">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
 <h3 class="card-title">Resolved Resale Disputes</h3>
 <span class="badge badge-confirmed">All Resolved</span>
 </div>
 <div class="table-container">
 <table class="table">
 <thead>
 <tr>
 <th>Dispute Ref</th>
 <th>Venue & Slot</th>
 <th>Original Customer</th>
 <th>Rebooked By</th>
 <th>Refund (90%)</th>
 <th>Ruling</th>
 <th>Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><strong>#RSL-012</strong></td>
 <td>
 <div style="font-weight: 600; font-size: 12px;">CR&FC Badminton</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Court 1 · 10 Sep (18:00)</div>
 </td>
 <td>Naveen Dias</td>
 <td>Tharindu Wickrama</td>
 <td>LKR 2,250</td>
 <td>
 <div style="font-size: 11px; font-weight: 600; color: var(--color-success);">Refund issued to original customer</div>
 <div style="font-size: 10px; color: var(--color-text-subtle);">Reason: slot was rebooked but the refund was not recorded</div>
 </td>
 <td><span class="badge badge-confirmed"> Resolved</span></td>
 </tr>
 </tbody>
 </table>
 </div>
</div>

