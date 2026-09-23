<div style="background: var(--color-white); border-bottom: 1px solid var(--color-border); padding: var(--space-8) 0 var(--space-6);">
 <div class="container">
 <div class="breadcrumb">
 <a href="<?= url('/') ?>">Home</a>
 <span class="breadcrumb-separator">/</span>
 <span>Coaching Sessions</span>
 </div>
 <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
 <div>
 <h1 style="font-size: var(--font-size-2xl); margin-bottom: 6px;">Coaching Clinics & Masterclasses</h1>
 <p class="text-sm" style="color: var(--color-text-muted);">
 1-hour targeted group training hosted on real court slots by certified independent coaches.
 </p>
 </div>
 <a href="<?= url('/register-coach') ?>" class="btn btn-outline" style="color: #7c3aed; border-color: #c4b5fd;">
 Join as a Coach
 </a>
 </div>

 <!-- Filters -->
 <div style="margin-top: var(--space-5); display: flex; flex-wrap: wrap; gap: 12px;">
 <div style="flex: 1; min-width: 180px;">
 <select class="form-select" id="coachSportFilter" onchange="filterSessions()">
 <option value="">All Sports</option>
 <option value="badminton"> Badminton</option>
 <option value="futsal"> Futsal</option>
 <option value="pickleball"> Pickleball</option>
 <option value="squash"> Squash</option>
 </select>
 </div>
 <div style="flex: 1; min-width: 180px;">
 <select class="form-select" id="coachVenueFilter" onchange="filterSessions()">
 <option value="">All Venues</option>
 <option value="cr&fc">CR&FC Badminton Complex</option>
 <option value="colombo futsal">Colombo Futsal Club</option>
 <option value="propickle">ProPickle Arena</option>
 </select>
 </div>
 <div style="flex: 1; min-width: 180px;">
 <input type="date" class="form-control" value="2026-09-24" id="coachDateFilter" onchange="filterSessions()">
 </div>
 </div>
 </div>
</div>

<main style="padding: var(--space-8) 0;">
 <div class="container">
 <div class="grid grid-cols-3 gap-6" id="coachingGridContainer">
 <!-- Dynamic coaching sessions -->
 </div>
 </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
 renderCoachingSessions();
});

function renderCoachingSessions() {
 const container = document.getElementById('coachingGridContainer');
 if (!container) return;

 const sessions = CourtPassData.coachingSessions;
 let html = '';

 sessions.forEach(s => {
 const isFull = s.status === 'full';
 const statusBadge = isFull ? '<span class="badge badge-full">Session Full</span>' : `<span class="badge badge-confirmed">${s.capacity - s.registeredCount} Spots Available</span>`;

 html += `
 <div class="card card-hover session-card" data-sport="${s.sport.toLowerCase()}" data-venue="${s.venueName.toLowerCase()}" style="border-top: 4px solid #7c3aed; display: flex; flex-direction: column;">
 <div class="card-body flex flex-col flex-1">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
 <span class="badge" style="background: #f5f3ff; color: #7c3aed; font-weight: 700;">${s.sport}</span>
 ${statusBadge}
 </div>

 <h3 style="font-size: 17px; margin-bottom: 4px;">
 <a href="<?= url('/customer/session-details') ?>?id=${s.id}" style="color: var(--color-text-title);">${s.title}</a>
 </h3>
 <div class="text-sm" style="color: var(--color-text-muted); margin-bottom: 12px;">
 ${s.venueName} · ${s.courtName}
 </div>

 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 16px; flex: 1;">
 ${s.description}
 </p>

 <!-- Coach Profile Link -->
 <div style="display: flex; align-items: center; gap: 10px; padding: 10px; background: var(--color-bg-subtle); border-radius: var(--radius-md); margin-bottom: 16px;">
 <div style="width: 38px; height: 38px; border-radius: 50%; background: #c4b5fd; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #5b21b6;">
 ${s.coachName.split(' ').map(n=>n[0]).join('')}
 </div>
 <div style="flex: 1;">
 <div style="font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 4px;">
 <a href="<?= url('/coach-profile') ?>?id=${s.coachId}" style="color: var(--color-text-title);">
 ${s.coachName}
 </a>
 <span class="badge badge-verified" style="font-size: 10px; padding: 1px 6px;">Verified</span>
 </div>
 <div class="text-xs" style="color: var(--color-text-muted);">
 Capacity: ${s.registeredCount} / ${s.capacity} Enrolled
 </div>
 </div>
 </div>

 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
 <div>
 <div class="text-xs" style="color: var(--color-text-muted);">Time Slot</div>
 <div style="font-size: 13px; font-weight: 700;">${s.date} · ${s.timeSlot.split(' - ')[0]}</div>
 </div>
 <div style="text-align: right;">
 <div class="text-xs" style="color: var(--color-text-muted);">Fee per player</div>
 <div style="font-size: 16px; font-weight: 800; color: #7c3aed;">LKR ${s.fee.toLocaleString()}</div>
 </div>
 </div>

 <div>
 ${isFull ? 
 `<button class="btn btn-secondary btn-block disabled" disabled>Session Full</button>` :
 `<a href="<?= url('/customer/session-details') ?>?id=${s.id}" class="btn btn-primary btn-block" style="background: #7c3aed; border-color: #7c3aed;">Register with PayHere &rarr;</a>`
 }
 </div>
 </div>
 </div>
 `;
 });

 container.innerHTML = html;
}

function filterSessions() {
 const sport = (document.getElementById('coachSportFilter').value || '').toLowerCase();
 const venue = (document.getElementById('coachVenueFilter').value || '').toLowerCase();

 document.querySelectorAll('#coachingGridContainer .session-card').forEach(card => {
 const cardSport = card.dataset.sport;
 const cardVenue = card.dataset.venue;
 const matchSport = !sport || cardSport.includes(sport);
 const matchVenue = !venue || cardVenue.includes(venue);

 card.style.display = (matchSport && matchVenue) ? 'flex' : 'none';
 });
}
</script>
