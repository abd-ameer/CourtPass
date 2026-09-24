<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>System Alerts</span>
 </div>
 <h1 class="page-title">Administrative Alerts </h1>
 <div class="page-subtitle">Platform health warnings, pending approval alerts, and settlement notifications.</div>
 </div>

 <div>
 <button class="btn btn-outline" onclick="CourtPassApp.showToast('success', 'Done', 'All administrative alerts marked as read')">
 Mark All Read
 </button>
 </div>
 </div>

 <!-- Alerts List -->
 <div style="display: flex; flex-direction: column; gap: var(--space-3);">

 <div class="card notif-item unread" style="border-left: 4px solid var(--color-warning);">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">New Venue Application: Grand Arena Indoor Complex</strong>
 <span class="badge badge-warning">Action Required</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 BRN PV-00918234 submitted for 4 courts in Rajagiriya. Requires business verification.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Yesterday</div>
 </div>
 </div>

 <a href="<?= url('/admin/venues') ?>" class="btn btn-sm btn-primary">Review Application</a>
 </div>
 </div>
 </div>

 <div class="card notif-item unread" style="border-left: 4px solid #7c3aed;">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">Coach Verification Request: Coach Shanilka Fernando</strong>
 <span class="badge badge-primary">Review</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 WSF Level 1 Certificate uploaded for squash coaching verification badge.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">2 days ago</div>
 </div>
 </div>

 <a href="<?= url('/admin/coaches') ?>" class="btn btn-sm btn-outline">Inspect Cert</a>
 </div>
 </div>
 </div>

 <div class="card notif-item" style="border-left: 4px solid var(--color-danger);">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">No-Show Dispute Filed: Dispute #DSP-891</strong>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Customer Danushka W. contested a no-show penalty from Colombo Futsal Club citing area blackout.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">14 Sep 2026</div>
 </div>
 </div>

 <a href="<?= url('/admin/disputes/no-show') ?>" class="btn btn-sm btn-outline">Arbitrate</a>
 </div>
 </div>
 </div>
</div>

