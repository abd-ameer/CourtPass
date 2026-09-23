<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Profile & Settings</span>
 </div>
 <h1 class="page-title">Account Settings</h1>
 <div class="page-subtitle">Manage personal profile, contact information, and security preferences.</div>
 </div>

 <button class="btn btn-primary" onclick="CourtPassApp.showToast('success', 'Profile Updated', 'Your contact details and preferences have been saved.');">
 Save Changes
 </button>
 </div>

 <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
 
 <!-- Left: Form Info -->
 <div>
 <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6);">
 <h3 style="font-size: 16px; margin-bottom: 16px;">Personal Information</h3>

 <div class="form-group">
 <label class="form-label">Full Name</label>
 <input type="text" class="form-control" value="Kasun Jayawardena">
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
 <div class="form-group">
 <label class="form-label">Email Address</label>
 <input type="email" class="form-control" value="kasun.j@gmail.com">
 </div>
 <div class="form-group">
 <label class="form-label">Contact Phone</label>
 <input type="tel" class="form-control" value="+94 77 123 4567">
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Primary Sports</label>
 <div style="display: flex; gap: 12px; flex-wrap: wrap;">
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Badminton</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Futsal</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox" checked> Squash</label>
 <label class="inline-flex items-center gap-1" style="font-size: 13px;"><input type="checkbox"> Pickleball</label>
 </div>
 </div>
 </div>

 <!-- Password Change -->
 <div class="card" style="padding: var(--space-6);">
 <h3 style="font-size: 16px; margin-bottom: 16px;">Security & Password</h3>

 <div class="form-group">
 <label class="form-label">Current Password</label>
 <input type="password" class="form-control" placeholder="••••••••">
 </div>

 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
 <div class="form-group">
 <label class="form-label">New Password</label>
 <input type="password" class="form-control" placeholder="New password">
 </div>
 <div class="form-group">
 <label class="form-label">Confirm New Password</label>
 <input type="password" class="form-control" placeholder="Confirm password">
 </div>
 </div>
 </div>
 </div>

 <!-- Right: Reliability & Tier Summary -->
 <div>
 <div class="card" style="padding: var(--space-6); background: var(--color-bg-subtle);">
 <div style="text-align: center; margin-bottom: 16px;">
 <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--color-primary-light); color: var(--color-primary-active); display: inline-flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; margin-bottom: 8px;">
 KJ
 </div>
 <div style="font-weight: 700; font-size: 16px;">Kasun Jayawardena</div>
 <div class="text-xs text-muted">Member since March 2026</div>
 </div>

 <div style="background: white; border-radius: var(--radius-md); padding: 12px; border: 1px solid var(--color-border); margin-bottom: 14px;">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
 <span class="text-xs text-muted">Reliability Score</span>
 <span class="badge tier-standard">88% (Standard)</span>
 </div>
 <div class="text-xs text-muted">
 Completed matches: <strong>14</strong> · No-shows: <strong>0</strong>
 </div>
 </div>

 <a href="<?= url('/customer/reliability') ?>" class="btn btn-outline-primary btn-block btn-sm">
 View Tier Privileges &rarr;
 </a>
 </div>
 </div>
