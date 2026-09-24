<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Venue Approvals</span>
 </div>
 <h1 class="page-title">Venue Approvals Queue </h1>
 <div class="page-subtitle">Verify newly registered sports complexes, check business registration documents, and approve listings.</div>
 </div>

 <div>
 <span class="badge badge-warning" style="font-size: 13px; padding: 6px 14px;">2 Applications Awaiting Review</span>
 </div>
 </div>

 <!-- Pending Venues Queue -->
 <div style="display: flex; flex-direction: column; gap: var(--space-6);">

 <!-- Venue 1 -->
 <div class="card" id="venue-card-1">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
 <h3 class="card-title" style="font-size: 18px;">Grand Arena Indoor Complex</h3>
 <span class="badge badge-warning">Pending Review</span>
 <span class="badge badge-confirmed"> Badminton · Futsal</span>
 </div>
 <div class="card-subtitle">
 348/2 High Level Road, Nawala, Rajagiriya · Submitted on 21 Sep 2026 by <strong>Duminda Silva</strong> (+94 77 980 1122)
 </div>
 </div>

 <div style="display: flex; gap: 8px;">
 <a href="<?= url('/admin/venues/3') ?>" class="btn btn-sm btn-outline">Inspect Details</a>
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Venue', 'A reason is required and is shown to the owner.', '/admin/venues/3/reject')">Reject</button>
 <button class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Approve Venue', 'Make this venue public and bookable?', 'Approve', '/admin/venues/3/approve')"> Approve & Publish</button>
 </div>
 </div>

 <div class="card-body">
 <div class="grid grid-cols-3 gap-4" style="background: var(--color-bg); padding: var(--space-4); border-radius: var(--radius-md); margin-bottom: var(--space-4);">
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Proposed Courts (4)</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 • 2x BWF Wooden Badminton Courts<br>
 • 2x 5-a-side Futsal Turf Courts
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Business Verification</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 BRN: PV-00918234 (Verified in e-ROC)<br>
 Tax Reg: WHT/VAT Exempt
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Uploaded Documents</span>
 <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing BRN Certificate PDF...')"> BRN Certificate</button>
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing Lease Agreement PDF...')"> Lease Deed</button>
 </div>
 </div>
 </div>

 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0;">
 <strong>Owner Note:</strong> "All courts are fully equipped with Yonex rubber mats, LED tournament lighting, hot shower locker rooms, and a cafe. Ready for immediate booking launch."
 </p>
 </div>
 </div>

 <!-- Venue 2 -->
 <div class="card" id="venue-card-2">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
 <h3 class="card-title" style="font-size: 18px;">Nugegoda Pickleball & Table Tennis Hub</h3>
 <span class="badge badge-warning">Pending Review</span>
 <span class="badge badge-confirmed"> Pickleball · Table Tennis</span>
 </div>
 <div class="card-subtitle">
 12 Stanmore Crescent, Nugegoda · Submitted on 20 Sep 2026 by <strong>Rohan Wijesekara</strong> (+94 71 445 6677)
 </div>
 </div>

 <div style="display: flex; gap: 8px;">
 <a href="<?= url('/admin/venues/3') ?>" class="btn btn-sm btn-outline">Inspect Details</a>
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="CourtPassApp.postWithReason('Reject Venue', 'A reason is required and is shown to the owner.', '/admin/venues/3/reject')">Reject</button>
 <button class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Approve Venue', 'Make this venue public and bookable?', 'Approve', '/admin/venues/3/approve')"> Approve & Publish</button>
 </div>
 </div>

 <div class="card-body">
 <div class="grid grid-cols-3 gap-4" style="background: var(--color-bg); padding: var(--space-4); border-radius: var(--radius-md); margin-bottom: var(--space-4);">
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Proposed Courts (3)</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 • 2x Regulation Pickleball Courts<br>
 • 1x ITTF Stiga TT Table Room
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Business Verification</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 BRN: PV-00812900<br>
 Operator: Sole Proprietorship
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Uploaded Documents</span>
 <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing BRN Certificate PDF...')"> BRN Certificate</button>
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing Insurance Deed PDF...')"> Insurance Cover</button>
 </div>
 </div>
 </div>

 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0;">
 <strong>Owner Note:</strong> "Brand new indoor facility with climate control. Seeking approval before tournament weekend."
 </p>
 </div>
 </div>

 </div>

 
 


