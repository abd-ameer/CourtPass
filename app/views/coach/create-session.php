<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions') ?>">Sessions</a>
 <span class="breadcrumb-separator">/</span>
 <span>Create</span>
 </div>
 <h1 class="page-title">Create New Coaching Session</h1>
 <div class="page-subtitle">Schedule a 1-hour coaching session at an approved venue. Choose public or private (token-link) access.</div>
 </div>
 </div>

 <form id="createSessionForm" method="POST" action="<?= url('/coach/sessions') ?>">
 <?= csrf_field() ?>
 <div class="grid grid-cols-3 gap-6">
 
 <!-- Left: Session Details -->
 <div style="grid-column: span 2;">
 
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Session Details</h3>
 </div>
 <div class="card-body">
 <div class="form-group">
 <label class="form-label">Session Title *</label>
 <input type="text" class="form-input" placeholder="e.g. Badminton Fundamentals" name="title" maxlength="150" required id="sessionTitle">
 <div class="form-hint">Choose a clear, descriptive name for public discovery.</div>
 </div>

 <div class="form-group">
 <label class="form-label">Description *</label>
 <textarea class="form-input" rows="4" placeholder="Describe what students will learn in this session..." name="description" maxlength="2000" required id="sessionDesc"></textarea>
 <div class="form-hint">Include skill level requirements (beginner/intermediate/advanced), equipment needed, etc.</div>
 </div>

 
 </div>
 </div>

 <!-- Venue & Court Selection -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Venue & Court Selection</h3>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Approved Venue *</label>
 <select class="form-input" required id="sessionVenue" onchange="updateCourtOptions()">
 <option value="">— Select venue —</option>
 <option value="1">Colombo Sports Hub</option>
 </select>
 <div class="form-hint">Only venues that have approved your coaching request are shown.</div>
 </div>
 <div class="form-group">
 <label class="form-label">Court *</label>
 <select class="form-input" name="court_id" required id="sessionCourt">
 <option value="">— Select venue first —</option>
 </select>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-4">
 <div class="form-group">
 <label class="form-label">Date *</label>
 <input type="date" class="form-input" name="session_date" required id="sessionDate" min="<?= e(date('Y-m-d')) ?>">
 </div>
 <div class="form-group">
 <label class="form-label">Start Time *</label>
 <select class="form-input" name="start_time" required id="sessionTime">
 <option value="">— Select time —</option>
 <option value="06:00">06:00 AM</option>
 <option value="07:00">07:00 AM</option>
 <option value="08:00">08:00 AM</option>
 <option value="09:00">09:00 AM</option>
 <option value="16:00">04:00 PM</option>
 <option value="17:00" selected>05:00 PM</option>
 <option value="18:00">06:00 PM</option>
 <option value="19:00">07:00 PM</option>
 <option value="20:00">08:00 PM</option>
 <option value="21:00">09:00 PM</option>
 </select>
 <div class="form-hint">All sessions are exactly 1 hour in duration.</div>
 </div>
 <div class="form-group">
 <label class="form-label">Duration</label>
 <input type="text" class="form-input" value="1 Hour (Fixed)" disabled style="background: var(--color-bg-page);">
 </div>
 </div>

 <!-- Slot Availability Check -->
 <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-lg); padding: var(--space-4); margin-top: var(--space-2);">
 <div style="display: flex; align-items: center; gap: 8px;">
 <span style="color: var(--color-primary); font-weight: 700;"></span>
 <span style="font-size: 13px; font-weight: 600; color: #166534;">Slot available! No conflicts detected for the selected date and time.</span>
 </div>
 </div>
 </div>
 </div>

 <!-- Capacity & Pricing -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Capacity & Pricing</h3>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4">
 <div class="form-group">
 <label class="form-label">Max Participants *</label>
 <input type="number" class="form-input" name="capacity" value="8" min="1" max="50" required id="sessionCapacity">
 <div class="form-hint">Maximum number of students allowed (1–20).</div>
 </div>
 <div class="form-group">
 <label class="form-label">Fee per Person (LKR) *</label>
 <input type="number" class="form-input" name="fee" value="1500" min="0" step="0.01" required id="sessionFee">
 <div class="form-hint">Set to 0 for a free session.</div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- Right: Session Type & Summary -->
 <div>
 <!-- Session Type -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Session Visibility</h3>
 </div>
 <div class="card-body">
 <label style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 2px solid var(--color-primary); border-radius: var(--radius-lg); margin-bottom: 12px; cursor: pointer; background: #e6f8f0;">
 <input type="radio" name="visibility" value="public" checked style="margin-top: 3px;">
 <div>
 <div style="font-weight: 700; font-size: 14px;">Public Session</div>
 <div class="text-xs text-muted">Visible on the public coaching directory. Any logged-in customer can register and pay.</div>
 </div>
 </label>
 <label style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 2px solid var(--color-border); border-radius: var(--radius-lg); cursor: pointer;" id="privateLabel">
 <input type="radio" name="visibility" value="private" style="margin-top: 3px;">
 <div>
 <div style="font-weight: 700; font-size: 14px;">Private Session (Token Link)</div>
 <div class="text-xs text-muted">Not publicly listed. Students register via a unique secret link you share with them.</div>
 </div>
 </label>
 </div>
 </div>

 <!-- Booking Summary -->
 <div class="card" style="position: sticky; top: 80px;">
 <div class="card-header" style="background: #f0fdf4;">
 <h3 style="font-size: 15px; margin-bottom: 0; color: #166534;"> Session Summary</h3>
 </div>
 <div class="card-body">
 <div style="font-size: 13px;">
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Sport</span>
 <strong id="summSport">Badminton</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Venue</span>
 <strong id="summVenue">—</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Date</span>
 <strong id="summDate">—</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Time</span>
 <strong id="summTime">—</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Capacity</span>
 <strong id="summCapacity">8 students</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Fee / Person</span>
 <strong id="summFee">LKR 1,500</strong>
 </div>
 </div>

 <div style="margin-top: var(--space-4); padding-top: var(--space-4); border-top: 2px solid var(--color-primary);">
 <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700;">
 <span>Max Revenue (if full)</span>
 <span style="color: var(--color-primary);" id="summRevenue">LKR 12,000</span>
 </div>
 </div>

 <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: var(--space-4);">
 Create Session
 </button>
 <a href="<?= url('/coach/sessions') ?>" class="btn btn-secondary" style="width: 100%; margin-top: var(--space-2); text-align: center;">
 Cancel
 </a>
 </div>
 </div>
 </div>

 </div>
 </form>

 
 


<script>
// TODO: courts come from the coach's approved venues.
const courtData = {
    1: [
        { value: 3, label: 'Badminton Court 1' },
        { value: 4, label: 'Badminton Court 2' }
    ]
};

function updateCourtOptions() {
 const venue = document.getElementById('sessionVenue').value;
 const courtSel = document.getElementById('sessionCourt');
 courtSel.innerHTML = '<option value="">— Select court —</option>';
 if (courtData[venue]) {
 courtData[venue].forEach(c => {
 const opt = document.createElement('option');
 opt.value = c.value;
 opt.textContent = c.label;
 courtSel.appendChild(opt);
 });
 }
 document.getElementById('summVenue').textContent = venue ? document.getElementById('sessionVenue').selectedOptions[0].text : '—';
}

document.getElementById('sessionDate')?.addEventListener('change', function() {
 document.getElementById('summDate').textContent = this.value || '—';
});
document.getElementById('sessionTime')?.addEventListener('change', function() {
 document.getElementById('summTime').textContent = this.selectedOptions[0].text || '—';
});
document.getElementById('sessionCapacity')?.addEventListener('input', function() {
 document.getElementById('summCapacity').textContent = this.value + ' students';
 updateRevenue();
});
document.getElementById('sessionFee')?.addEventListener('input', updateRevenue);

function updateRevenue() {
 const cap = parseInt(document.getElementById('sessionCapacity').value) || 0;
 const fee = parseInt(document.getElementById('sessionFee').value) || 0;
 const total = cap * fee;
 document.getElementById('summFee').textContent = 'LKR ' + fee.toLocaleString();
 document.getElementById('summRevenue').textContent = 'LKR ' + total.toLocaleString();
}

</script>
