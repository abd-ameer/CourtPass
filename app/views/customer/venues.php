<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Browse Venues</span>
 </div>
 <h1 class="page-title">Find & Book Sports Venues</h1>
 <div class="page-subtitle">Real-time availability across Colombo indoor sports courts and pitches.</div>
 </div>
 </div>

 <!-- Filter Controls -->
 <div class="card" style="padding: 16px 20px; margin-bottom: var(--space-6);">
 <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
 <div>
 <label class="form-label" style="font-size: 11px;">Search Venue / Location</label>
 <input type="text" id="custVenueSearch" class="form-control" placeholder="Search by name..." oninput="filterCustomerVenues()">
 </div>
 <div>
 <label class="form-label" style="font-size: 11px;">Sport</label>
 <select id="custSportFilter" class="form-select" onchange="filterCustomerVenues()">
 <option value="">All Sports</option>
 <option value="badminton"> Badminton</option>
 <option value="futsal"> Futsal</option>
 <option value="pickleball"> Pickleball</option>
 <option value="squash"> Squash</option>
 <option value="table-tennis"> Table Tennis</option>
 </select>
 </div>
 <div>
 <label class="form-label" style="font-size: 11px;">Preferred Time Window</label>
 <select class="form-select">
 <option>Any Time</option>
 <option>Morning (06:00 - 12:00)</option>
 <option>Afternoon (12:00 - 17:00)</option>
 <option selected>Evening Peak (17:00 - 23:00)</option>
 </select>
 </div>
 </div>
 </div>

 <!-- Venues Grid -->
 <div class="grid grid-cols-3 gap-6" id="custVenuesGrid">
 <!-- Rendered via script -->
 </div>

 </div>
 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
 const container = document.getElementById('custVenuesGrid');
 if (!container) return;

 const venues = CourtPassData.venues;
 let html = '';

 venues.forEach(v => {
 html += `
 <div class="card card-hover venue-card" data-sport="${v.sports.join(',')}" data-name="${v.name.toLowerCase()}">
 <div class="venue-card-img-wrapper">
 <img src="${v.image}" alt="${v.name}" class="venue-card-img">
 <div class="venue-price-badge">From LKR ${v.startingPrice.toLocaleString()} / hr</div>
 </div>
 <div class="card-body flex flex-col flex-1">
 <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
 <span class="badge badge-confirmed" style="text-transform: capitalize;">${v.sports[0]}</span>
 <span style="font-size: 13px; font-weight: 700; color: #d97706;"> ${v.rating}</span>
 </div>
 <h3 style="font-size: 17px; margin-bottom: 4px;">
 <a href="<?= url('/customer/venue-details') ?>?id=${v.id}" style="color: var(--color-text-title);">${v.name}</a>
 </h3>
 <p class="text-sm" style="margin-bottom: 14px; color: var(--color-text-muted);">
 ${v.city} · ${v.courts.length} Courts
 </p>
 <div style="margin-top: auto; display: flex; gap: 8px;">
 <a href="<?= url('/customer/venue-details') ?>?id=${v.id}" class="btn btn-outline btn-sm flex-1">Overview</a>
 <a href="<?= url('/customer/court-availability') ?>?court=${v.courts[0].id}" class="btn btn-primary btn-sm flex-1">Book Slot &rarr;</a>
 </div>
 </div>
 </div>
 `;
 });

 container.innerHTML = html;
});

function filterCustomerVenues() {
 const search = (document.getElementById('custVenueSearch').value || '').toLowerCase();
 const sport = (document.getElementById('custSportFilter').value || '').toLowerCase();

 document.querySelectorAll('#custVenuesGrid .venue-card').forEach(card => {
 const cardName = card.dataset.name;
 const cardSport = card.dataset.sport;
 const matchSearch = !search || cardName.includes(search);
 const matchSport = !sport || cardSport.includes(sport);
 card.style.display = (matchSearch && matchSport) ? 'flex' : 'none';
 });
}
</script>
