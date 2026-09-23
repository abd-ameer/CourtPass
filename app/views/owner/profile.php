<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Owner Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Settings</span>
 </div>
 <h1 class="page-title">Venue Settings & Profile </h1>
 <div class="page-subtitle">Configure venue operations, payment gateway keys, bank accounts, and front-desk PINs.</div>
 </div>

 <div>
 <button class="btn btn-primary" onclick="saveOwnerSettings()">Save Changes</button>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <!-- Left Column: Navigation Shortcuts -->
 <div>
 <div class="card" style="padding: var(--space-3);">
 <div style="display: flex; flex-direction: column; gap: 4px;">
 <a href="#business-profile" class="btn btn-outline" style="justify-content: flex-start; border: none; background: #e6f8f0; color: var(--color-primary-active); font-weight: 700;">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
 Business Profile
 </a>
 <a href="#banking" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 Settlement Bank Account
 </a>
 <a href="#gateway" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
 Payment Gateway (PayHere)
 </a>
 <a href="#front-desk" class="btn btn-outline" style="justify-content: flex-start; border: none; color: var(--color-text-body);">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
 Front-Desk PIN & Staff
 </a>
 </div>
 </div>

 <!-- Quick Venue Badge -->
 <div class="card" style="margin-top: var(--space-4); background: #e6f8f0; border: 1px solid #a7f3d0;">
 <div class="card-body">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
 <span class="badge badge-confirmed"> Approved Partner Venue</span>
 </div>
 <div style="font-weight: 700; color: var(--color-text-heading); font-size: 13px; margin-bottom: 4px;">Colombo Futsal Club</div>
 <p style="font-size: 11px; color: var(--color-text-muted); margin-bottom: 8px; line-height: 1.4;">
 Active since 12 Jan 2026. 3 courts listed, 4.9 star rating with 48 customer reviews.
 </p>
 <a href="<?= url('/owner/venue-details') ?>?id=venue-1" class="text-xs" style="color: var(--color-primary-active); font-weight: 700;">View Venue Profile →</a>
 </div>
 </div>
 </div>

 <!-- Right Column: Settings Sections -->
 <div style="grid-column: span 2; display: flex; flex-direction: column; gap: var(--space-6);">

 <!-- Section 1: Business Profile -->
 <div class="card" id="business-profile">
 <div class="card-header">
 <h3 class="card-title">Business & Operator Profile</h3>
 <div class="card-subtitle">Official business entity details and primary emergency contacts.</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Company / Entity Name</label>
 <input type="text" class="form-control" value="Colombo Sports Hub (Pvt) Ltd" required>
 </div>
 <div class="form-group">
 <label class="form-label">Business Reg Number (BRN)</label>
 <input type="text" class="form-control" value="PV-00239108" required>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Owner / Managing Director</label>
 <input type="text" class="form-control" value="Kusal Mendis" required>
 </div>
 <div class="form-group">
 <label class="form-label">Primary Hotline (Front Desk)</label>
 <input type="tel" class="form-control" value="+94 11 234 5678" required>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Official Email Address</label>
 <input type="email" class="form-control" value="owner@colombofutsal.lk" required>
 </div>
 </div>
 </div>

 <!-- Section 2: Banking & Settlements -->
 <div class="card" id="banking">
 <div class="card-header">
 <h3 class="card-title">Settlement Bank Account</h3>
 <div class="card-subtitle">Automated bi-weekly disbursements are transferred to this Sri Lankan bank account.</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">Bank</label>
 <select class="form-control">
 <option selected>Sampath Bank PLC</option>
 <option>Commercial Bank of Ceylon</option>
 <option>Hatton National Bank</option>
 <option>Bank of Ceylon</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Branch</label>
 <input type="text" class="form-control" value="Dehiwala Main Branch">
 </div>
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Account Number</label>
 <input type="text" class="form-control" value="0019 4432 9980" style="font-family: monospace; font-weight: 700;">
 </div>
 <div class="form-group">
 <label class="form-label">Account Beneficiary Name</label>
 <input type="text" class="form-control" value="Colombo Futsal Club (Pvt) Ltd">
 </div>
 </div>
 </div>
 </div>

 <!-- Section 3: PayHere Sandbox Gateway -->
 <div class="card" id="gateway">
 <div class="card-header">
 <h3 class="card-title">PayHere Payment Gateway Configuration</h3>
 <div class="card-subtitle">Integration credentials for direct customer online card and FriMi payments.</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="margin-bottom: var(--space-4);">
 <div class="form-group">
 <label class="form-label">PayHere Merchant ID</label>
 <input type="text" class="form-control" value="1224890" style="font-family: monospace;">
 </div>
 <div class="form-group">
 <label class="form-label">PayHere Environment</label>
 <select class="form-control">
 <option selected>Sandbox / Test Mode</option>
 <option>Live Production</option>
 </select>
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Merchant Secret</label>
 <input type="password" class="form-control" value="84f889201a4bc8820019b" style="font-family: monospace;">
 </div>
 </div>
 </div>

 <!-- Section 4: Front-Desk PIN -->
 <div class="card" id="front-desk">
 <div class="card-header">
 <h3 class="card-title">Front-Desk PIN & Staff Check-in</h3>
 <div class="card-subtitle">Allow court marshals or counter staff to access the Check-In screen without full owner credentials.</div>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4" style="align-items: flex-end;">
 <div class="form-group">
 <label class="form-label">Rapid Check-in 4-Digit PIN</label>
 <input type="password" class="form-control" value="4421" maxlength="4" style="font-size: 20px; font-weight: 900; letter-spacing: 6px; text-align: center; width: 160px;">
 </div>
 <div>
 <button class="btn btn-outline" onclick="CourtPassApp.showToast('Check-in URL copied to clipboard!', 'info')">
 Copy Front-Desk URL
 </button>
 </div>
 </div>
 </div>
 </div>

 </div>

 </div>

 </div>
 </div>
</div>

<script>
function saveOwnerSettings() {
 CourtPassApp.showToast('Venue settings and credentials saved successfully!', 'success');
}
</script>
