<!-- Venue Banner & Identity -->
<section style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
 <div class="container">
 <div class="breadcrumb">
 <a href="<?= url('/') ?>">Home</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/venues') ?>">Venues</a>
 <span class="breadcrumb-separator">/</span>
 <span id="venueBreadcrumbName">Colombo Futsal Club</span>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap; margin-top: var(--space-4);">
 <div>
 <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
 <h1 style="font-size: var(--font-size-2xl);" id="venueTitle">Colombo Futsal Club</h1>
 <span class="badge badge-verified">Verified Facility</span>
 <span class="badge badge-active">Active</span>
 </div>
 <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 8px;" id="venueAddress">
 No. 42 Marine Drive, Dehiwala, Colombo · +94 11 273 8910
 </p>
 <div style="display: flex; align-items: center; gap: 16px; font-size: 13px;">
 <span style="font-weight: 700; color: #d97706;"> 4.8 (124 Verified Reviews)</span>
 <span style="color: var(--color-border-strong);">|</span>
 <span style="color: var(--color-text-main);"> <strong>Hours:</strong> 06:00 AM - 11:00 PM Daily</span>
 <span style="color: var(--color-border-strong);">|</span>
 <span style="color: var(--color-primary-active); font-weight: 600;"> platform.lk/venue/colombofutsal</span>
 </div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="navigator.clipboard.writeText(window.location.href); CourtPassApp.showToast('success', 'Link Copied', 'Shareable venue URL copied to clipboard.');">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
 Share Venue
 </button>
 <a href="<?= url('/court-details') ?>?court=c1" class="btn btn-primary">
 Check Slot Availability
 </a>
 </div>
 </div>
 </div>
</section>

<!-- Content Grid -->
<main style="padding: var(--space-8) 0;">
 <div class="container">
 <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;" class="venue-detail-layout">
 
 <!-- Left Column: Courts, Gallery, Announcements, Reviews -->
 <div>
 
 <!-- Gallery Banner -->
 <div class="card" style="margin-bottom: var(--space-6); overflow: hidden;">
 <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80" alt="Venue Cover" style="width: 100%; height: 320px; object-fit: cover;">
 <div class="card-body">
 <h3 style="font-size: 18px; margin-bottom: 6px;">Facility Amenities</h3>
 <div style="display: flex; gap: 8px; flex-wrap: wrap;">
 <span class="badge" style="background: #f1f5f9;"> Pro Floodlights</span>
 <span class="badge" style="background: #f1f5f9;"> Showers & Changing Rooms</span>
 <span class="badge" style="background: #f1f5f9;"> Secure Parking (40+ Vehicles)</span>
 <span class="badge" style="background: #f1f5f9;"> Player Café & Lounge</span>
 <span class="badge" style="background: #f1f5f9;"> Purified Water Stations</span>
 <span class="badge" style="background: #f1f5f9;"> Certified First Aid</span>
 </div>
 </div>
 </div>

 <!-- Venue Announcements -->
 <div class="card" style="margin-bottom: var(--space-6); border-left: 4px solid var(--color-primary);">
 <div class="card-header" style="background: var(--color-bg-subtle);">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 16px;"></span>
 <h3 style="font-size: 15px; margin-bottom: 0;">Official Venue Announcements</h3>
 </div>
 <span class="badge badge-confirmed">Operational Update</span>
 </div>
 <div class="card-body">
 <div style="font-weight: 700; color: var(--color-text-title); margin-bottom: 4px;">Monsoon Synthetic Turf Resurfacing Complete</div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 6px;">
 Turf Court 1 has been upgraded with FIFA-standard shock pads and high-drainage infill. Optimal play during light rain is now fully supported.
 </p>
 <div class="text-xs" style="color: var(--color-text-muted);">Posted by Management · Sept 20, 2026</div>
 </div>
 </div>

 <!-- Available Courts List -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 17px; margin-bottom: 0;">Courts at this Venue</h3>
 <span class="text-sm" style="color: var(--color-text-muted);">3 Courts Available</span>
 </div>
 <div class="card-body" style="padding: 0;">
 
 <!-- Court 1 -->
 <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h4 style="font-size: 16px; margin-bottom: 0;">Turf Court 1 (Floodlit)</h4>
 <span class="badge badge-confirmed"> Futsal</span>
 </div>
 <div class="text-xs" style="color: var(--color-text-muted); margin-top: 4px;">
 Surface: Synthetic Grass · Dimensions: 38m x 18m · 5v5 / 7v7
 </div>
 </div>
 <div style="display: flex; align-items: center; gap: 16px;">
 <div style="text-align: right;">
 <div style="font-size: 17px; font-weight: 800; color: var(--color-text-title);">LKR 5,000</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per 1-hour slot</div>
 </div>
 <a href="<?= url('/court-details') ?>?court=c1" class="btn btn-sm btn-primary">Check Availability</a>
 </div>
 </div>

 <!-- Court 2 -->
 <div style="padding: 16px 20px; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h4 style="font-size: 16px; margin-bottom: 0;">Turf Court 2 (Indoor)</h4>
 <span class="badge badge-confirmed"> Futsal</span>
 <span class="badge badge-flash"> Flash Slots Available</span>
 </div>
 <div class="text-xs" style="color: var(--color-text-muted); margin-top: 4px;">
 Surface: Indoor Turf · Climate Controlled Roof
 </div>
 </div>
 <div style="display: flex; align-items: center; gap: 16px;">
 <div style="text-align: right;">
 <div style="font-size: 17px; font-weight: 800; color: var(--color-text-title);">LKR 4,500</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per 1-hour slot</div>
 </div>
 <a href="<?= url('/court-details') ?>?court=c2" class="btn btn-sm btn-primary">Check Availability</a>
 </div>
 </div>

 <!-- Court 3 -->
 <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h4 style="font-size: 16px; margin-bottom: 0;">Wooden Badminton Court A</h4>
 <span class="badge badge-confirmed"> Badminton</span>
 </div>
 <div class="text-xs" style="color: var(--color-text-muted); margin-top: 4px;">
 Surface: Teak Hardwood · Glare-free overhead LED lighting
 </div>
 </div>
 <div style="display: flex; align-items: center; gap: 16px;">
 <div style="text-align: right;">
 <div style="font-size: 17px; font-weight: 800; color: var(--color-text-title);">LKR 2,500</div>
 <div class="text-xs" style="color: var(--color-text-muted);">per 1-hour slot</div>
 </div>
 <a href="<?= url('/court-details') ?>?court=c3" class="btn btn-sm btn-primary">Check Availability</a>
 </div>
 </div>

 </div>
 </div>

 <!-- Verified Reviews & Owner Response Section -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 17px; margin-bottom: 0;">Verified Player Reviews (124)</h3>
 <span class="badge badge-verified">Check-in Verified Only</span>
 </div>
 <div class="card-body">
 
 <!-- Review 1 with Owner Response -->
 <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--color-border);">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
 <div style="display: flex; align-items: center; gap: 8px;">
 <strong>Kasun Jayawardena</strong>
 <span class="tier-badge tier-standard">Standard Tier</span>
 </div>
 <span style="color: #d97706; font-weight: 700; font-size: 13px;"> 5.0</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 8px;">
 "Excellent turf condition after the recent upgrade! The drainage held up even during evening rain. Check-in at the front desk was smooth via QR code."
 </p>
 <div class="text-xs" style="color: var(--color-text-muted);">Verified Check-in on Sept 18, 2026</div>

 <!-- Owner Response -->
 <div style="background: var(--color-bg-subtle); border-left: 3px solid var(--color-primary); padding: 10px 14px; border-radius: var(--radius-md); margin-top: 10px;">
 <div style="font-size: 12px; font-weight: 700; color: var(--color-text-title); margin-bottom: 2px;">
 Response from Nuwan Senanayake (Venue Owner):
 </div>
 <div style="font-size: 12px; color: var(--color-text-muted);">
 "Thank you Kasun! We strive to maintain FIFA standards for all our community players. Looking forward to your next session!"
 </div>
 </div>
 </div>

 <!-- Review 2 -->
 <div>
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
 <div style="display: flex; align-items: center; gap: 8px;">
 <strong>Shenal Gunaratne</strong>
 <span class="tier-badge tier-restricted">Restricted Tier</span>
 </div>
 <span style="color: #d97706; font-weight: 700; font-size: 13px;"> 4.0</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 8px;">
 "Great facilities and changing rooms. Parking gets slightly packed during peak 8 PM weekend hours, but security was helpful."
 </p>
 <div class="text-xs" style="color: var(--color-text-muted);">Verified Check-in on Sept 14, 2026</div>
 </div>

 </div>
 </div>

 </div>

 <!-- Right Column: "Most Active This Month" Community Leaderboard & Location -->
 <div>
 
 <!-- Most Active This Month Section (Required in SRS UC-CU-29) -->
 <div class="card" style="margin-bottom: var(--space-6); border: 1.5px solid #bbf7d0;">
 <div class="card-header" style="background: var(--color-primary-light);">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="font-size: 18px;"></span>
 <h3 style="font-size: 15px; color: var(--color-primary-active); margin-bottom: 0;">Most Active This Month</h3>
 </div>
 <span class="badge badge-confirmed">Top 5</span>
 </div>
 <div class="card-body">
 <p class="text-xs" style="color: var(--color-text-muted); margin-bottom: 12px;">
 Recognizing the top 5 dedicated athletes at this venue by verified court attendance in September 2026.
 </p>

 <div style="display: flex; flex-direction: column; gap: 10px;">
 
 <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 10px;">
 <span style="font-weight: 800; color: #d97706; width: 16px;">1</span>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Kasun Jayawardena</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Standard Tier</div>
 </div>
 </div>
 <div style="font-weight: 800; color: var(--color-primary); font-size: 13px;">26 Hours</div>
 </div>

 <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 10px;">
 <span style="font-weight: 800; color: #94a3b8; width: 16px;">2</span>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Shenal Gunaratne</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Restricted Tier</div>
 </div>
 </div>
 <div style="font-weight: 800; color: var(--color-primary); font-size: 13px;">19 Hours</div>
 </div>

 <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 10px;">
 <span style="font-weight: 800; color: #b45309; width: 16px;">3</span>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Akila Samarasinghe</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Standard Tier</div>
 </div>
 </div>
 <div style="font-weight: 800; color: var(--color-primary); font-size: 13px;">16 Hours</div>
 </div>

 <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 10px;">
 <span style="font-weight: 800; color: var(--color-text-subtle); width: 16px;">4</span>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Tharindu Fernando</div>
 <div class="text-xs" style="color: var(--color-text-muted);">Standard Tier</div>
 </div>
 </div>
 <div style="font-weight: 800; color: var(--color-primary); font-size: 13px;">14 Hours</div>
 </div>

 <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: var(--color-bg-subtle); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 10px;">
 <span style="font-weight: 800; color: var(--color-text-subtle); width: 16px;">5</span>
 <div>
 <div style="font-size: 13px; font-weight: 700;">Pradeep Bandara</div>
 <div class="text-xs" style="color: var(--color-text-muted);">New Member</div>
 </div>
 </div>
 <div style="font-weight: 800; color: var(--color-primary); font-size: 13px;">12 Hours</div>
 </div>

 </div>
 </div>
 </div>

 <!-- Venue Contact & Map Snapshot -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Venue Location & Contact</h3>
 </div>
 <div class="card-body">
 <div style="background: #e2e8f0; height: 160px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted); font-size: 13px; margin-bottom: 12px;">
 Map View: Marine Drive, Dehiwala
 </div>
 <div style="font-size: 13px; color: var(--color-text-main); display: flex; flex-direction: column; gap: 6px;">
 <div><strong>Owner:</strong> Nuwan Senanayake</div>
 <div><strong>Direct Contact:</strong> +94 11 273 8910</div>
 <div><strong>Email:</strong> bookings@colombofutsal.lk</div>
 </div>
 <a href="<?= url('/court-details') ?>?court=c1" class="btn btn-primary btn-block" style="margin-top: 16px;">
 Book a Court Now
 </a>
 </div>
 </div>

 </div>

 </div>
 </div>
</main>
