<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Coach Verification</span>
 </div>
 <h1 class="page-title">Coach Verification Queue </h1>
 <div class="page-subtitle">Verify Sri Lankan coaching credentials, national federations diplomas, and assign Verified Coach badges.</div>
 </div>

 <div>
 <span class="badge badge-warning" style="font-size: 13px; padding: 6px 14px;">3 Applications Awaiting Review</span>
 </div>
 </div>

 <!-- Verification Cards -->
 <div style="display: flex; flex-direction: column; gap: var(--space-6);">

 <!-- Coach 1 -->
 <div class="card" id="coach-card-1">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; align-items: center; gap: 14px;">
 <div class="user-avatar" style="width: 48px; height: 48px; font-size: 16px; background: #ede9fe; color: #7c3aed;">SF</div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h3 class="card-title" style="font-size: 18px;">Coach Shanilka Fernando</h3>
 <span class="badge badge-warning">Pending Review</span>
 <span class="badge badge-primary">Squash</span>
 </div>
 <div class="card-subtitle">
 Colombo 07 · Applied on 21 Sep 2026 · +94 77 334 5566 (shanilka.squash@gmail.com)
 </div>
 </div>
 </div>

 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Verify Coach', 'Confirm the offline identity checks are complete. No ID documents are stored.', 'Mark Verified', '/admin/coaches/8/verify')"> Grant Verified Badge</button>
 </div>
 </div>

 <div class="card-body">
 <div class="grid grid-cols-3 gap-4" style="background: var(--color-bg); padding: var(--space-4); border-radius: var(--radius-md); margin-bottom: var(--space-4);">
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Claimed Certification</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 WSF Level 1 Coaching Certificate<br>
 Sri Lanka Squash Federation (SLSF)
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Experience</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 5+ Years Coaching Junior Squads<br>
 Former National Junior Semi-Finalist
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Uploaded Evidence</span>
 <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing WSF Certificate PDF...')"> WSF Certificate</button>
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing NIC Copy...')"> NIC Copy</button>
 </div>
 </div>
 </div>

 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0;">
 <strong>Coach Statement:</strong> "Conducting weekend junior development clinics at Otters Aquatic Club and Gymkhana Club. Certified by SLSF in 2023."
 </p>
 </div>
 </div>

 <!-- Coach 2 -->
 <div class="card" id="coach-card-2">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; align-items: center; gap: 14px;">
 <div class="user-avatar" style="width: 48px; height: 48px; font-size: 16px; background: #fef3c7; color: #d97706;">PD</div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h3 class="card-title" style="font-size: 18px;">Coach Priyantha Dias</h3>
 <span class="badge badge-warning">Pending Review</span>
 <span class="badge badge-primary">Table Tennis</span>
 </div>
 <div class="card-subtitle">
 Moratuwa · Applied on 20 Sep 2026 · +94 71 889 0011 (priyantha.tt@gmail.com)
 </div>
 </div>
 </div>

 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-primary" onclick="CourtPassApp.confirmPost('Verify Coach', 'Confirm the offline identity checks are complete. No ID documents are stored.', 'Mark Verified', '/admin/coaches/8/verify')"> Grant Verified Badge</button>
 </div>
 </div>

 <div class="card-body">
 <div class="grid grid-cols-3 gap-4" style="background: var(--color-bg); padding: var(--space-4); border-radius: var(--radius-md); margin-bottom: var(--space-4);">
 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Claimed Certification</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 ITTF Level 1 Coach Accreditation<br>
 Table Tennis Association of Sri Lanka (TTASL)
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Experience</span>
 <div style="font-size: 13px; font-weight: 600; color: var(--color-text-heading); margin-top: 2px;">
 8 Years Coaching Experience<br>
 Provincial Level Player
 </div>
 </div>

 <div>
 <span style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase; font-weight: 700;">Uploaded Evidence</span>
 <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
 <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('info', 'Note', 'Previewing ITTF Certificate PDF...')"> ITTF Accreditation</button>
 </div>
 </div>
 </div>

 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0;">
 <strong>Coach Statement:</strong> "Active TT instructor running private training for school tournament athletes."
 </p>
 </div>
 </div>

 </div>

 
 


