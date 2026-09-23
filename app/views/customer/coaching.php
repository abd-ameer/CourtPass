<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Coaching Clinics</span>
 </div>
 <h1 class="page-title">Browse Coaching Masterclasses</h1>
 <div class="page-subtitle">Learn from verified independent coaches on dedicated 1-hour court slots.</div>
 </div>

 <a href="<?= url('/customer/my-sessions') ?>" class="btn btn-outline">
 My Registered Sessions (1) &rarr;
 </a>
 </div>

 <!-- Filters -->
 <div class="card" style="padding: 16px 20px; margin-bottom: var(--space-6);">
 <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
 <div>
 <label class="form-label" style="font-size: 11px;">Sport</label>
 <select class="form-select">
 <option value="">All Sports</option>
 <option value="badminton" selected> Badminton</option>
 <option value="futsal"> Futsal</option>
 <option value="pickleball"> Pickleball</option>
 <option value="squash"> Squash</option>
 </select>
 </div>
 <div>
 <label class="form-label" style="font-size: 11px;">Approved Venue</label>
 <select class="form-select">
 <option value="">All Venues</option>
 <option selected>CR&FC Badminton Complex</option>
 <option>Colombo Futsal Club</option>
 <option>ProPickle Arena Colombo</option>
 </select>
 </div>
 <div>
 <label class="form-label" style="font-size: 11px;">Date</label>
 <input type="date" class="form-control" value="2026-09-24">
 </div>
 </div>
 </div>

 <!-- Session Cards Grid -->
 <div class="grid grid-cols-3 gap-6">
 
 <!-- Session 1 -->
 <div class="card card-hover" style="border-top: 4px solid #7c3aed;">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge" style="background: #f5f3ff; color: #7c3aed; font-weight: 700;">Badminton</span>
 <span class="badge badge-confirmed">2 Spots Left</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 4px;">
 <a href="<?= url('/customer/session-details') ?>?id=session-101" style="color: var(--color-text-title);">
 Badminton Jump Smash & Forecourt Mastery
 </a>
 </h3>
 <div class="text-xs text-muted" style="margin-bottom: 12px;">
 CR&FC Badminton Complex · Court 1
 </div>
 <div style="background: var(--color-bg-subtle); padding: 10px; border-radius: var(--radius-md); display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
 <div style="width: 34px; height: 34px; border-radius: 50%; background: #c4b5fd; color: #5b21b6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
 DP
 </div>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Coach Dilshan Perera</div>
 <div class="text-xs text-muted">BWF Level 2 · 4.95</div>
 </div>
 </div>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
 <div>
 <div class="text-xs text-muted">Date & Slot</div>
 <div style="font-size: 13px; font-weight: 700;">Sept 24 · 07:00 PM</div>
 </div>
 <div style="text-align: right;">
 <div class="text-xs text-muted">Fee</div>
 <div style="font-size: 16px; font-weight: 900; color: #7c3aed;">LKR 3,500</div>
 </div>
 </div>
 <a href="<?= url('/customer/session-details') ?>?id=session-101" class="btn btn-primary btn-block" style="background: #7c3aed; border-color: #7c3aed;">
 View & Register &rarr;
 </a>
 </div>
 </div>

 <!-- Session 2 -->
 <div class="card card-hover" style="border-top: 4px solid #00b562;">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge badge-confirmed">Futsal</span>
 <span class="badge badge-full">Session Full</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 4px;">
 Futsal High-Press Tactical Clinic
 </h3>
 <div class="text-xs text-muted" style="margin-bottom: 12px;">
 Colombo Futsal Club · Turf 1
 </div>
 <div style="background: var(--color-bg-subtle); padding: 10px; border-radius: var(--radius-md); display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
 <div style="width: 34px; height: 34px; border-radius: 50%; background: #bbf7d0; color: #166534; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
 SF
 </div>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Coach Shanilka Fernando</div>
 <div class="text-xs text-muted">AFC "C" License · 4.88</div>
 </div>
 </div>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
 <div>
 <div class="text-xs text-muted">Date & Slot</div>
 <div style="font-size: 13px; font-weight: 700;">Sept 25 · 08:00 PM</div>
 </div>
 <div style="text-align: right;">
 <div class="text-xs text-muted">Fee</div>
 <div style="font-size: 16px; font-weight: 900; color: var(--color-primary-active);">LKR 4,000</div>
 </div>
 </div>
 <button class="btn btn-secondary btn-block disabled" disabled>
 Fully Booked (8/8)
 </button>
 </div>
 </div>

 <!-- Session 3 -->
 <div class="card card-hover" style="border-top: 4px solid #3b82f6;">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge" style="background: #eff6ff; color: #1d4ed8;">Pickleball</span>
 <span class="badge badge-confirmed">2 Spots Left</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 4px;">
 Pickleball Fundamentals & Match Drills
 </h3>
 <div class="text-xs text-muted" style="margin-bottom: 12px;">
 ProPickle Arena · Court 1
 </div>
 <div style="background: var(--color-bg-subtle); padding: 10px; border-radius: var(--radius-md); display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
 <div style="width: 34px; height: 34px; border-radius: 50%; background: #bfdbfe; color: #1e40af; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px;">
 AW
 </div>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Coach Amanda Wijesinghe</div>
 <div class="text-xs text-muted">IPTPA Level 2 · 4.92</div>
 </div>
 </div>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
 <div>
 <div class="text-xs text-muted">Date & Slot</div>
 <div style="font-size: 13px; font-weight: 700;">Sept 26 · 05:00 PM</div>
 </div>
 <div style="text-align: right;">
 <div class="text-xs text-muted">Fee</div>
 <div style="font-size: 16px; font-weight: 900; color: #2563eb;">LKR 3,000</div>
 </div>
 </div>
 <a href="<?= url('/customer/session-details') ?>?id=session-103" class="btn btn-primary btn-block">
 View & Register &rarr;
 </a>
 </div>
 </div>
