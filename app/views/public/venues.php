<?php
$selected_sport = $selected_sport ?? $_GET['sport'] ?? '';
$selected_city = $selected_city ?? $_GET['city'] ?? '';
?>
<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
 <div class="container">
 <div class="breadcrumb">
 <a href="<?= url('/') ?>">Home</a>
 <span class="breadcrumb-separator">/</span>
 <span>Venues</span>
 </div>
 <h1 style="font-size: var(--font-size-2xl); margin-bottom: 6px;">Browse Sports Venues & Courts</h1>
 <p class="text-sm" style="color: var(--color-text-muted);">
 Explore verified indoor courts, turfs and academies across Colombo. Check live slot availability and book directly.
 </p>

 <!-- Search & Filter Controls -->
 <div style="margin-top: var(--space-5); display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
 <div style="flex: 2; min-width: 240px;">
 <input type="text" id="venueSearchInput" class="form-control" placeholder="Search by venue name or location (e.g. CR&FC, Dehiwala)..." oninput="filterVenues()">
 </div>
 <div style="flex: 1; min-width: 160px;">
 <select id="sportFilterSelect" class="form-select" onchange="filterVenues()">
 <option value="">All Sports</option>
 <option value="badminton" <?php if($selected_sport==='badminton') echo 'selected'; ?>> Badminton</option>
 <option value="futsal" <?php if($selected_sport==='futsal') echo 'selected'; ?>> Futsal</option>
 <option value="pickleball" <?php if($selected_sport==='pickleball') echo 'selected'; ?>> Pickleball</option>
 <option value="squash" <?php if($selected_sport==='squash') echo 'selected'; ?>> Squash</option>
 <option value="table-tennis" <?php if($selected_sport==='table-tennis') echo 'selected'; ?>> Table Tennis</option>
 <option value="carrom" <?php if($selected_sport==='carrom') echo 'selected'; ?>> Carrom</option>
 <option value="billiards" <?php if($selected_sport==='billiards') echo 'selected'; ?>> Billiards</option>
 </select>
 </div>
 <div style="flex: 1; min-width: 160px;">
 <select id="locationFilterSelect" class="form-select" onchange="filterVenues()">
 <option value="">All Locations</option>
 <option value="Colombo 07" <?php if($selected_city==='Colombo 07') echo 'selected'; ?>>Colombo 07</option>
 <option value="Dehiwala" <?php if($selected_city==='Dehiwala') echo 'selected'; ?>>Dehiwala</option>
 <option value="Rajagiriya" <?php if($selected_city==='Rajagiriya') echo 'selected'; ?>>Rajagiriya</option>
 <option value="Battaramulla" <?php if($selected_city==='Battaramulla') echo 'selected'; ?>>Battaramulla</option>
 </select>
 </div>
 </div>
 </div>
</div>

<main style="padding: var(--space-8) 0;">
 <div class="container">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4);">
 <div style="font-size: 14px; font-weight: 600; color: var(--color-text-title);">
 Showing <span id="venueCountDisplay">5</span> Verified Venues
 </div>
 <div style="display: flex; gap: 8px; align-items: center; font-size: 13px;">
 <span style="color: var(--color-text-muted);">Sort:</span>
 <select class="form-select" style="width: auto; padding: 4px 10px; font-size: 12px;">
 <option>Highest Rated</option>
 <option>Most Active</option>
 <option>Price: Low to High</option>
 </select>
 </div>
 </div>

 <!-- Venues Grid -->
 <div class="grid grid-cols-3 gap-6" id="venuesGridContainer">
 <!-- Venues dynamically populated by script or PHP fallback -->
 </div>
 </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
 renderVenuesList();
});

function renderVenuesList() {
 const container = document.getElementById('venuesGridContainer');
 if (!container) return;

 const venues = CourtPassData.venues;
 let html = '';

 venues.forEach(v => {
 const sportsBadges = v.sports.map(s => `<span class="badge badge-confirmed" style="text-transform: capitalize;">${s}</span>`).join(' ');
 
 html += `
 <div class="card card-hover venue-card" data-sports="${v.sports.join(',')}" data-city="${v.city}" data-name="${v.name.toLowerCase()}">
 <div class="venue-card-img-wrapper">
 <img src="${v.image}" alt="${v.name}" class="venue-card-img">
 <div class="venue-badge-overlay">
 <span class="badge badge-active">${v.status}</span>
 <span class="badge badge-flash"> Flash Slots</span>
 </div>
 <div class="venue-price-badge">From LKR ${v.startingPrice.toLocaleString()} / hr</div>
 </div>
 <div class="card-body flex flex-col flex-1">
 <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
 <div style="display: flex; gap: 4px; flex-wrap: wrap;">${sportsBadges}</div>
 <span style="font-size: 13px; font-weight: 700; color: #d97706;"> ${v.rating} (${v.reviewCount})</span>
 </div>
 <h3 style="font-size: 18px; margin-bottom: 4px;">
 <a href="<?= url('/venue-details') ?>?id=${v.id}" style="color: var(--color-text-title);">${v.name}</a>
 </h3>
 <p class="text-sm" style="margin-bottom: 12px; color: var(--color-text-muted);">
 ${v.address} · ${v.courts.length} Courts
 </p>

 <div style="font-size: 12px; color: var(--color-text-main); margin-bottom: 16px; background: var(--color-bg-subtle); padding: 8px 12px; border-radius: var(--radius-md);">
 <strong>Most Active Player:</strong> ${v.mostActiveCustomers[0].name} (${v.mostActiveCustomers[0].hours} hrs)
 </div>

 <div style="margin-top: auto; display: flex; gap: 8px;">
 <a href="<?= url('/venue-details') ?>?id=${v.id}" class="btn btn-outline btn-sm flex-1">View Details</a>
 <a href="<?= url('/court-details') ?>?court=${v.courts[0].id}&venue=${v.id}" class="btn btn-primary btn-sm flex-1">Check Slots</a>
 </div>
 </div>
 </div>
 `;
 });

 container.innerHTML = html;
 filterVenues();
}

function filterVenues() {
 const searchVal = (document.getElementById('venueSearchInput').value || '').toLowerCase();
 const sportVal = (document.getElementById('sportFilterSelect').value || '').toLowerCase();
 const cityVal = (document.getElementById('locationFilterSelect').value || '').toLowerCase();

 const cards = document.querySelectorAll('#venuesGridContainer .venue-card');
 let visibleCount = 0;

 cards.forEach(card => {
 const cardSports = card.dataset.sports.toLowerCase();
 const cardCity = card.dataset.city.toLowerCase();
 const cardName = card.dataset.name.toLowerCase();

 const matchesSearch = !searchVal || cardName.includes(searchVal) || cardCity.includes(searchVal);
 const matchesSport = !sportVal || cardSports.includes(sportVal);
 const matchesCity = !cityVal || cardCity.includes(cityVal);

 if (matchesSearch && matchesSport && matchesCity) {
 card.style.display = 'flex';
 visibleCount++;
 } else {
 card.style.display = 'none';
 }
 });

 const countDisplay = document.getElementById('venueCountDisplay');
 if (countDisplay) countDisplay.textContent = visibleCount;
}
</script>
