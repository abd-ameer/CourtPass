<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Resale Marketplace</span>
 </div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h1 class="page-title">Community Ticket Resale Marketplace</h1>
 <span class="badge badge-flash">Max 90% Price Capped</span>
 </div>
 <div class="page-subtitle">Grab confirmed slots listed by fellow athletes at discounted rates. Safe transfer guaranteed.</div>
 </div>

 <a href="<?= url('/customer/my-resales') ?>" class="btn btn-outline">
 My Active Resale Listings &rarr;
 </a>
 </div>

 <!-- Resale Safeguard Banner (UC-CU-10, UC-CU-11) -->
 <div class="card" style="margin-bottom: var(--space-6); background: #fff7ed; border-color: #fdba74; padding: 16px 20px;">
 <div style="display: flex; align-items: center; gap: 14px;">
 <div style="font-size: 28px;"></div>
 <div>
 <strong style="color: #9a3412; font-size: 14px;">Server-Enforced 90% Anti-Scalping Price Cap</strong>
 <p class="text-xs" style="color: #c2410c; margin-bottom: 0; line-height: 1.4;">
 Sellers can only list confirmed, online-paid bookings at a maximum of 90% of original venue rates. Upon PayHere payment, ownership transfers instantly with an updated digital pass.
 </p>
 </div>
 </div>
 </div>

 <!-- Resale Listings Grid -->
 <div class="grid grid-cols-3 gap-6">
 
 <!-- Listing 1 -->
 <div class="card card-hover">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge badge-confirmed"> Futsal</span>
 <span class="badge badge-flash">16% Discount</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 2px;">Colombo Futsal Club</h3>
 <div class="text-xs text-muted" style="margin-bottom: 12px;">Turf Court 2 (Indoor)</div>
 
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md); margin-bottom: 14px; font-size: 13px;">
 <div> <strong>Thursday, Sept 24, 2026</strong></div>
 <div style="color: var(--color-primary-active); font-weight: 700; margin-top: 2px;"> 09:00 PM - 10:00 PM</div>
 <div class="text-xs text-muted" style="margin-top: 4px;">Seller: Shenal G. (68% Restricted)</div>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
 <div>
 <div class="text-xs text-muted" style="text-decoration: line-through;">Orig: LKR 4,500</div>
 <div class="text-xs" style="color: #166534; font-weight: 600;">Below 90% Cap</div>
 </div>
 <div style="text-align: right;">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-text-title);">LKR 3,800</div>
 </div>
 </div>

 <button type="button" class="btn btn-primary btn-block" onclick="claimResale('RS-401', 'Colombo Futsal Club', 3800)">
 Buy via PayHere Sandbox &rarr;
 </button>
 </div>
 </div>

 <!-- Listing 2 -->
 <div class="card card-hover">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge badge-confirmed"> Badminton</span>
 <span class="badge badge-flash">11% Discount</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 2px;">CR&FC Badminton Complex</h3>
 <div class="text-xs text-muted" style="margin-bottom: 12px;">Court 2 - Yonex Mat</div>
 
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md); margin-bottom: 14px; font-size: 13px;">
 <div> <strong>Friday, Sept 25, 2026</strong></div>
 <div style="color: var(--color-primary-active); font-weight: 700; margin-top: 2px;"> 07:00 PM - 08:00 PM</div>
 <div class="text-xs text-muted" style="margin-top: 4px;">Seller: Nuwan P. (92% Standard)</div>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
 <div>
 <div class="text-xs text-muted" style="text-decoration: line-through;">Orig: LKR 2,800</div>
 <div class="text-xs" style="color: #166534; font-weight: 600;">Below 90% Cap</div>
 </div>
 <div style="text-align: right;">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-text-title);">LKR 2,500</div>
 </div>
 </div>

 <button type="button" class="btn btn-primary btn-block" onclick="claimResale('RS-402', 'CR&FC Badminton Complex', 2500)">
 Buy via PayHere Sandbox &rarr;
 </button>
 </div>
 </div>

 <!-- Listing 3 -->
 <div class="card card-hover">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
 <span class="badge" style="background: #eff6ff; color: #1d4ed8;"> Pickleball</span>
 <span class="badge badge-flash">20% Discount</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 2px;">ProPickle Arena Colombo</h3>
 <div class="text-xs text-muted" style="margin-bottom: 12px;">Pickleball Court 2</div>
 
 <div style="background: var(--color-bg-subtle); padding: 12px; border-radius: var(--radius-md); margin-bottom: 14px; font-size: 13px;">
 <div> <strong>Thursday, Sept 24, 2026</strong></div>
 <div style="color: var(--color-primary-active); font-weight: 700; margin-top: 2px;"> 06:00 PM - 07:00 PM</div>
 <div class="text-xs text-muted" style="margin-top: 4px;">Seller: Akila S. (85% Standard)</div>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
 <div>
 <div class="text-xs text-muted" style="text-decoration: line-through;">Orig: LKR 3,000</div>
 <div class="text-xs" style="color: #166534; font-weight: 600;">Below 90% Cap</div>
 </div>
 <div style="text-align: right;">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-text-title);">LKR 2,400</div>
 </div>
 </div>

 <button type="button" class="btn btn-primary btn-block" onclick="claimResale('RS-403', 'ProPickle Arena', 2400)">
 Buy via PayHere Sandbox &rarr;
 </button>
 </div>
 </div>

 </div>

 </div>
 </div>
</div>

<script>
function claimResale(listingId, venue, price) {
 CourtPassApp.confirmDialog(
 'Purchase Resale Ticket',
 `Confirm purchase of ${venue} slot for LKR ${price.toLocaleString()} via PayHere Sandbox? Ownership and QR pass will transfer to your account automatically.`,
 'Pay & Transfer Ownership',
 function() {
 CourtPassApp.showToast('info', 'Processing Transfer', 'Connecting to PayHere Sandbox...');
 setTimeout(() => {
 CourtPassApp.showToast('success', 'Ticket Transferred!', 'Booking ownership transferred to Kasun Jayawardena. Pass generated in My Bookings.');
 setTimeout(() => {
 window.location.href = '<?= url('/customer/bookings') ?>';
 }, 1200);
 }, 1000);
 }
 );
}
</script>
