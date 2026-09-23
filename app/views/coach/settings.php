<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Settings</span>
 </div>
 <h1 class="page-title">Coach Settings </h1>
 <div class="page-subtitle">Manage your coaching defaults, payout bank account, and account preferences.</div>
 </div>

 <div>
 <button class="btn btn-primary" onclick="saveCoachSettings()">Save Changes</button>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Left Column: Settings Navigation Links -->
 <div>
 <div class="card" style="padding: var(--space-3);">
 <div style="display: flex; flex-direction: column; gap: 4px;">
 <a href="#general" class="btn btn-outline" style="justify-content: flex-start; border: none; background: #e6f8f0; color: var(--color-primary-active); font-weight: 700;">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
 General Profile
 </a>
 <a href="#coaching-defaults" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
 Coaching Defaults
 </a>
 <a href="#payout-settings" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 Payout & Banking
 </a>
 <a href="#notifications" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
 Notification Alerts
 </a>
 <a href="#security" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
 Security & Password
 </a>
 </div>
 </div>

 <!-- Coach Verification Status Card -->
 <div class="card" style="margin-top: var(--space-4); background: #e6f8f0; border: 1px solid #a7f3d0;">
 <div class="card-body">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
 <span class="badge badge-confirmed"> Verified Coach</span>
 </div>
 <div style="font-weight: 700; color: var(--color-text-heading); font-size: 13px; margin-bottom: 4px;">SLBA Level 2 Certified</div>
 <p style="font-size: 11px; color: var(--color-text-muted); margin-bottom: 8px; line-height: 1.4;">
 Your verified credentials appear with a checkmark on all coaching search results and public booking slots.
 </p>
 <a href="<?= url('/coach/profile') ?>" class="text-xs" style="color: var(--color-primary-active); font-weight: 700;">View Public Profile →</a>
 </div>
 </div>
 </div>

 <!-- Right Column: Settings Forms -->
 <div style="grid-column: span 2; display: flex; flex-direction: column; gap: var(--space-6);">

 <!-- Section 1: General Info -->
 <div class="card" id="general">
 <div class="card-header">
 <h3 class="card-title">General Information</h3>
 <div class="card-subtitle">Update your personal contact information displayed to approved venues.</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Full Name</label>
 <input type="text" class="form-control" value="Dilshan Perera" required>
 </div>
 <div class="form-group">
 <label class="form-label">Display Title / Tagline</label>
 <input type="text" class="form-control" value="Head Coach & Former National Player">
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Email Address</label>
 <input type="email" class="form-control" value="dilshan.coach@gmail.com" required>
 </div>
 <div class="form-group">
 <label class="form-label">Phone Number (Sri Lanka)</label>
 <input type="tel" class="form-control" value="+94 77 123 4567" required>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Coach Bio / Background</label>
 <textarea class="form-control" rows="3">Former Sri Lanka National Badminton Squad member with 8+ years of dedicated professional coaching experience. Specialising in junior development, smash velocity mechanics, split-step footwork, and tournament match preparation.</textarea>
 </div>
 </div>
 </div>

 <!-- Section 2: Coaching Defaults -->
 <div class="card" id="coaching-defaults">
 <div class="card-header">
 <h3 class="card-title">Coaching Defaults & Pricing</h3>
 <div class="card-subtitle">These presets will automatically populate when creating new sessions.</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-3 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Primary Sport</label>
 <select class="form-control">
 <option selected>Badminton</option>
 <option>Squash</option>
 <option>Table Tennis</option>
 <option>Pickleball</option>
 </select>
 </div>

 <div class="form-group">
 <label class="form-label">Default Fee per Student (LKR)</label>
 <input type="number" class="form-control" value="3500">
 </div>

 <div class="form-group">
 <label class="form-label">Default Max Capacity</label>
 <input type="number" class="form-control" value="8" min="1" max="20">
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Available Coaching Experience Levels</label>
 <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 6px;">
 <label style="display: flex; align-items: center; gap: 6px; font-size: var(--font-size-xs); cursor: pointer;">
 <input type="checkbox" checked> Beginner
 </label>
 <label style="display: flex; align-items: center; gap: 6px; font-size: var(--font-size-xs); cursor: pointer;">
 <input type="checkbox" checked> Intermediate
 </label>
 <label style="display: flex; align-items: center; gap: 6px; font-size: var(--font-size-xs); cursor: pointer;">
 <input type="checkbox" checked> Advanced / Competitive
 </label>
 </div>
 </div>
 </div>
 </div>

 <!-- Section 3: Payout Banking -->
 <div class="card" id="payout-settings">
 <div class="card-header">
 <h3 class="card-title">Payout & Bank Account</h3>
 <div class="card-subtitle">Direct deposit details for coaching fee disbursements (Bi-weekly schedule).</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Bank Name</label>
 <select class="form-control">
 <option selected>Commercial Bank of Ceylon</option>
 <option>Sampath Bank PLC</option>
 <option>Hatton National Bank (HNB)</option>
 <option>Bank of Ceylon (BOC)</option>
 <option>People's Bank</option>
 <option>Nations Trust Bank (NTB)</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Branch Name</label>
 <input type="text" class="form-control" value="Torrington / Colombo 07">
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Account Number</label>
 <input type="text" class="form-control" value="8004 9281 0021" style="font-family: monospace; font-weight: 700;">
 </div>
 <div class="form-group">
 <label class="form-label">Account Beneficiary Name</label>
 <input type="text" class="form-control" value="Dilshan Ravindra Perera">
 </div>
 </div>
 </div>
 </div>

 <!-- Section 4: Notification Alerts -->
 <div class="card" id="notifications">
 <div class="card-header">
 <h3 class="card-title">Notification Alerts</h3>
 <div class="card-subtitle">Configure when and how CourtPass notifies you.</div>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-direction: column; gap: 12px;">
 <label style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
 <div>
 <div style="font-weight: 600; font-size: var(--font-size-sm); color: var(--color-text-heading);">Instant Student Registration Alert</div>
 <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">Receive an SMS & in-app notification each time a student books your session.</div>
 </div>
 <input type="checkbox" checked style="width: 18px; height: 18px; accent-color: var(--color-primary-active);">
 </label>

 <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 0;">

 <label style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
 <div>
 <div style="font-weight: 600; font-size: var(--font-size-sm); color: var(--color-text-heading);">Venue Approval Notifications</div>
 <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">Get notified immediately when venue owners accept your coaching requests.</div>
 </div>
 <input type="checkbox" checked style="width: 18px; height: 18px; accent-color: var(--color-primary-active);">
 </label>

 <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 0;">

 <label style="display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
 <div>
 <div style="font-weight: 600; font-size: var(--font-size-sm); color: var(--color-text-heading);">Bi-Weekly Payout Confirmations</div>
 <div style="font-size: var(--font-size-xs); color: var(--color-text-muted);">Email statement and bank reference on every successful disbursement.</div>
 </div>
 <input type="checkbox" checked style="width: 18px; height: 18px; accent-color: var(--color-primary-active);">
 </label>
 </div>
 </div>
 </div>

 <!-- Section 5: Security -->
 <div class="card" id="security">
 <div class="card-header">
 <h3 class="card-title">Security & Password</h3>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Current Password</label>
 <input type="password" class="form-control" placeholder="••••••••">
 </div>
 <div class="form-group">
 <label class="form-label">New Password</label>
 <input type="password" class="form-control" placeholder="Enter at least 8 characters">
 </div>
 </div>
 <button class="btn btn-sm btn-outline" style="margin-top: var(--space-2);" onclick="CourtPassApp.showToast('Password updated successfully!', 'success')">Update Password</button>
 </div>
 </div>

 </div>

 </div>

 </div>
 </div>
</div>

<script>
function saveCoachSettings() {
 CourtPassApp.showToast('Coach profile and preferences updated successfully!', 'success');
}
</script>
