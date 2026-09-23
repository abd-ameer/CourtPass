<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
 <div class="container">
 <div class="breadcrumb">
 <a href="<?= url('/') ?>">Home</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coaching') ?>">Coaching</a>
 <span class="breadcrumb-separator">/</span>
 <span>Coach Dilshan Perera</span>
 </div>

 <!-- Coach Identity Header -->
 <div style="display: flex; gap: 24px; align-items: center; flex-wrap: wrap; margin-top: var(--space-4);">
 <div style="width: 90px; height: 90px; border-radius: 50%; background: #c4b5fd; color: #5b21b6; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; border: 3px solid var(--color-white); box-shadow: var(--shadow-md);">
 DP
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
 <h1 style="font-size: var(--font-size-2xl);">Coach Dilshan Perera</h1>
 <span class="badge badge-verified" style="font-size: 13px; padding: 4px 10px;">
 <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
 Admin Verified Coach
 </span>
 </div>
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 6px;">
 Badminton Specialist · 9 Years Coaching Experience · 150+ Students Mentored
 </p>
 <div style="display: flex; align-items: center; gap: 14px; font-size: 13px;">
 <span style="font-weight: 700; color: #d97706;"> 4.95 (48 Verified Student Reviews)</span>
 <span style="color: var(--color-border-strong);">|</span>
 <span>Hourly Session Fee: <strong>LKR 3,500</strong></span>
 </div>
 </div>
 </div>
 </div>
</div>

<main style="padding: var(--space-8) 0;">
 <div class="container">
 <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
 
 <!-- Left Column: Bio, Certifications, Upcoming Sessions, Reviews -->
 <div>
 <!-- Bio Card -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Coach Biography</h3>
 </div>
 <div class="card-body">
 <p style="line-height: 1.6; color: var(--color-text-main); margin-bottom: 12px;">
 Former Sri Lanka National Squad member and accredited senior badminton trainer. With nearly a decade of competitive and training experience across Colombo, Dilshan specializes in building dynamic footwork patterns, net deception, and high-impact jump smash technique.
 </p>
 <div style="padding: 12px 16px; background: var(--color-bg-subtle); border-radius: var(--radius-md); border-left: 3px solid #7c3aed;">
 <div style="font-size: 13px; font-weight: 700; color: var(--color-text-title); margin-bottom: 4px;">Certifications & Credentials (Text Verified):</div>
 <div class="text-sm" style="color: var(--color-text-muted);">
 • BWF Coach Level 2 Accreditation (2020)<br>
 • Diploma in Sports Science & Biomechanics, University of Colombo (UCSC)<br>
 • Certified First Aid & Sports Injury Responder
 </div>
 </div>
 </div>
 </div>

 <!-- Upcoming Public Sessions by this Coach -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Upcoming Masterclasses by Coach Dilshan</h3>
 <span class="badge badge-confirmed">Open for Booking</span>
 </div>
 <div class="card-body" style="padding: 0;">
 <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid var(--color-border);">
 <div>
 <h4 style="font-size: 15px; margin-bottom: 2px;">Badminton Jump Smash & Forecourt Mastery</h4>
 <div class="text-xs" style="color: var(--color-text-muted);">
 CR&FC Badminton Complex · Sept 24 · 07:00 PM - 08:00 PM (1 Hr)
 </div>
 <div class="badge badge-pending" style="margin-top: 6px;">2 Slots Remaining</div>
 </div>
 <div style="display: flex; align-items: center; gap: 16px;">
 <div style="text-align: right;">
 <div style="font-size: 16px; font-weight: 800; color: #7c3aed;">LKR 3,500</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per player</div>
 </div>
 <a href="<?= url('/customer/session-details') ?>?id=session-101" class="btn btn-sm btn-primary" style="background: #7c3aed; border-color: #7c3aed;">
 Register Now
 </a>
 </div>
 </div>
 </div>
 </div>

 <!-- Verified Reviews & Coach Responses -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Verified Student Feedback (48)</h3>
 <span class="badge badge-verified">Attended Session Only</span>
 </div>
 <div class="card-body">
 
 <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--color-border);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
 <strong>Kasun Jayawardena</strong>
 <span style="color: #d97706; font-weight: 700;"> 5.0</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 6px;">
 "Coach Dilshan's drills on rear court recovery transformed my singles match play in just two sessions. Clear feedback and intense training!"
 </p>
 <div class="text-xs" style="color: var(--color-text-muted);">Verified Session Attendance · Sept 10, 2026</div>

 <!-- Coach 1-time reply -->
 <div style="background: var(--color-bg-subtle); border-left: 3px solid #7c3aed; padding: 10px 14px; border-radius: var(--radius-md); margin-top: 8px;">
 <div style="font-size: 12px; font-weight: 700; color: #5b21b6; margin-bottom: 2px;">Coach Dilshan Perera:</div>
 <div style="font-size: 12px; color: var(--color-text-muted);">
 "Great work on your split-step recovery, Kasun! Keep up the consistency during high-pressure rallies."
 </div>
 </div>
 </div>

 </div>
 </div>

 </div>

 <!-- Right Column: Approved Venues & Contact -->
 <div>
 <!-- Approved Venues Card (UC-CO-03) -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Approved Training Venues</h3>
 </div>
 <div class="card-body">
 <p class="text-xs" style="color: var(--color-text-muted); margin-bottom: 12px;">
 Venues where this coach has active operating permissions approved by the venue owner.
 </p>
 <div style="display: flex; flex-direction: column; gap: 8px;">
 <div style="padding: 10px; background: var(--color-bg-subtle); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between;">
 <div>
 <div style="font-size: 13px; font-weight: 700;">CR&FC Badminton Complex</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Longdon Place, Colombo 07</div>
 </div>
 <span class="badge badge-approved">Approved</span>
 </div>
 <div style="padding: 10px; background: var(--color-bg-subtle); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between;">
 <div>
 <div style="font-size: 13px; font-weight: 700;">Colombo Futsal Club</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Marine Drive, Dehiwala</div>
 </div>
 <span class="badge badge-approved">Approved</span>
 </div>
 </div>
 </div>
 </div>

 <!-- Action Card -->
 <div class="card" style="background: var(--color-primary-light); border-color: var(--color-primary-border);">
 <div class="card-body text-center">
 <h4 style="color: var(--color-primary-active); margin-bottom: 8px;">Looking for Private Training?</h4>
 <p class="text-xs" style="color: var(--color-text-main); margin-bottom: 16px;">
 Coach Dilshan also accepts 1-on-1 private coaching sessions via invite links.
 </p>
 <a href="<?= url('/customer/coaching') ?>" class="btn btn-primary btn-block">
 Browse All Sessions
 </a>
 </div>
 </div>

 </div>

 </div>
 </div>
</main>
