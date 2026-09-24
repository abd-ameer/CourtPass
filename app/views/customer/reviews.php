<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>My Reviews</span>
 </div>
 <h1 class="page-title">My Verified Reviews & Ratings</h1>
 <div class="page-subtitle">Feedback submitted for attended court bookings and coaching clinics.</div>
 </div>

 <a href="<?= url('/customer/bookings/1/review') ?>" class="btn btn-primary">
 + Write New Review
 </a>
 </div>

 <!-- Reviews Container -->
 <div style="display: flex; flex-direction: column; gap: 16px;">
 
 <!-- Review 1: Venue with Owner Response -->
 <div class="card">
 <div class="card-header">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <strong style="font-size: 15px;">Colombo Futsal Club</strong>
 <span class="badge badge-verified">Check-in Verified</span>
 </div>
 <div style="display: flex; gap: 8px;">
 <a href="<?= url('/customer/reviews/1/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
 <button class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Delete Review', 'Delete this review? This cannot be undone.', 'Delete', '/customer/reviews/1', 'DELETE')">Delete</button>
 </div>
 </div>
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
 <span style="color: #d97706; font-weight: 700; font-size: 14px;"> 5.0 (Turf Court 1)</span>
 <span class="text-xs text-muted">Submitted on Sept 19, 2026</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 12px;">
 "Excellent turf condition after the recent upgrade! The drainage held up even during evening rain. Check-in at the front desk was smooth via QR code."
 </p>

 <!-- Venue Owner Response -->
 <div style="background: var(--color-bg-subtle); border-left: 3px solid var(--color-primary); padding: 12px 16px; border-radius: var(--radius-md);">
 <div style="font-size: 12px; font-weight: 700; color: var(--color-text-title); margin-bottom: 2px;">
 Official Response from Nuwan Senanayake (Venue Owner):
 </div>
 <div style="font-size: 12px; color: var(--color-text-muted);">
 "Thank you Kasun! We strive to maintain FIFA standards for all our community players. Looking forward to your next session!"
 </div>
 </div>
 </div>
 </div>

 <!-- Review 2: Coach Review -->
 <div class="card">
 <div class="card-header">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <strong style="font-size: 15px;">Coach Dilshan Perera (Badminton)</strong>
 <span class="badge badge-verified">Attended Session Verified</span>
 </div>
 <div style="display: flex; gap: 8px;">
 <a href="<?= url('/customer/reviews/1/edit') ?>" class="btn btn-sm btn-outline">Edit</a>
 <button class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.confirmPost('Delete Review', 'Delete this review? This cannot be undone.', 'Delete', '/customer/reviews/2', 'DELETE')">Delete</button>
 </div>
 </div>
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
 <span style="color: #d97706; font-weight: 700; font-size: 14px;"> 5.0</span>
 <span class="text-xs text-muted">Submitted on Sept 11, 2026</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 12px;">
 "Coach Dilshan's drills on rear court recovery transformed my singles match play in just two sessions. Clear feedback and intense training!"
 </p>

 <!-- Coach Reply -->
 <div style="background: var(--color-bg-subtle); border-left: 3px solid #7c3aed; padding: 12px 16px; border-radius: var(--radius-md);">
 <div style="font-size: 12px; font-weight: 700; color: #5b21b6; margin-bottom: 2px;">
 Official Reply from Coach Dilshan:
 </div>
 <div style="font-size: 12px; color: var(--color-text-muted);">
 "Great work on your split-step recovery, Kasun! Keep up the consistency during high-pressure rallies."
 </div>
 </div>
 </div>
 </div>

 </div>

 
 


