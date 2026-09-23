<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <span>Venues</span>
 </div>
 <h1 class="page-title">Venue Approvals & Requests</h1>
 <div class="page-subtitle">Request access to venues for conducting coaching sessions. Venue owners must approve your request before you can create sessions.</div>
 </div>
 <button type="button" class="btn btn-primary" onclick="showNewVenueRequest()">
 + Request Venue Access
 </button>
 </div>

 <!-- Approved Venues -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;"> Approved Venues (2)</h3>
 <span class="badge badge-confirmed">Can Create Sessions</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Venue</th>
 <th>Sport</th>
 <th>Available Courts</th>
 <th>Approved Since</th>
 <th>Sessions Held</th>
 <th style="text-align: right;">Actions</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>CR&FC Badminton Complex</strong>
 <div class="text-xs text-muted">Longdon Place, Colombo 07</div>
 </td>
 <td><span class="badge badge-sport" style="background:#e6f8f0; color:#00b562;"> Badminton</span></td>
 <td>
 <strong>3 Courts</strong>
 <div class="text-xs text-muted">Wooden, Synthetic, Training</div>
 </td>
 <td>April 2025</td>
 <td><strong>12</strong></td>
 <td style="text-align: right;">
 <a href="<?= url('/coach/create-session') ?>?venue=crfc" class="btn btn-sm btn-primary">+ Create Session</a>
 </td>
 </tr>
 <tr>
 <td>
 <strong>Royal College Sports Complex</strong>
 <div class="text-xs text-muted">Reid Avenue, Colombo 07</div>
 </td>
 <td><span class="badge badge-sport" style="background:#e6f8f0; color:#00b562;"> Badminton</span></td>
 <td>
 <strong>2 Courts</strong>
 <div class="text-xs text-muted">Synthetic, Concrete</div>
 </td>
 <td>June 2025</td>
 <td><strong>5</strong></td>
 <td style="text-align: right;">
 <a href="<?= url('/coach/create-session') ?>?venue=royal" class="btn btn-sm btn-primary">+ Create Session</a>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Pending Requests -->
 <div class="card" style="margin-bottom: var(--space-6); border: 2px solid #fde68a;">
 <div class="card-header" style="background: #fffbeb;">
 <h3 style="font-size: 15px; margin-bottom: 0; color: #92400e;">⏳ Pending Venue Requests (1)</h3>
 <span class="badge badge-pending">Awaiting Owner Approval</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Venue</th>
 <th>Sport Requested</th>
 <th>Submitted On</th>
 <th>Message to Owner</th>
 <th style="text-align: right;">Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>SpinMaster Academy</strong>
 <div class="text-xs text-muted">Rajagiriya</div>
 </td>
 <td><span class="badge badge-sport" style="background:#fef3c7; color:#92400e;"> Table Tennis</span></td>
 <td>Sept 18, 2026</td>
 <td>
 <div style="font-size: 13px; max-width: 280px; color: var(--color-text-body);">
 "Hi, I'd like to conduct beginner and intermediate TT coaching sessions at your venue. I have BWF L2 certification and 3 years of TT coaching experience."
 </div>
 </td>
 <td style="text-align: right;">
 <span class="badge badge-pending" style="font-size: 12px;">Pending</span>
 <br>
 <button type="button" class="btn btn-sm btn-secondary" style="margin-top: 6px; color: var(--color-danger);" onclick="cancelVenueRequest('VR-301')">
 Cancel Request
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Rejected Requests -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;"> Previously Rejected (1)</h3>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Venue</th>
 <th>Requested Sport</th>
 <th>Submitted</th>
 <th>Rejection Reason</th>
 <th style="text-align: right;">Actions</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Otters Aquatic Club</strong>
 <div class="text-xs text-muted">Colombo 03</div>
 </td>
 <td><span class="badge badge-sport" style="background:#ede9fe; color:#6d28d9;"> Squash</span></td>
 <td>Aug 5, 2026</td>
 <td>
 <div style="font-size: 13px; color: var(--color-danger);">
 "We currently have our own squash coaching program. Please try again in January 2027."
 </div>
 </td>
 <td style="text-align: right;">
 <button type="button" class="btn btn-sm btn-outline" onclick="showNewVenueRequest()">
 Re-apply
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 </div>
 </div>
</div>

<!-- New Venue Request Modal -->
<div class="modal-overlay" id="venueRequestModal" style="display:none;">
 <div class="modal" style="max-width: 520px;">
 <div class="modal-header">
 <h3 style="font-size: 16px; margin: 0;">Request Venue Access</h3>
 <button type="button" class="modal-close" onclick="CourtPassApp.closeModal('venueRequestModal')">&times;</button>
 </div>
 <div class="modal-body">
 <div class="form-group">
 <label class="form-label">Select Venue *</label>
 <select class="form-input">
 <option value="">— Choose a venue —</option>
 <option>Colombo Futsal Club — Dehiwala</option>
 <option>SpinMaster Academy — Rajagiriya</option>
 <option>Otters Aquatic Club — Colombo 03</option>
 <option>Arena Sports Hub — Nugegoda</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Sport *</label>
 <select class="form-input">
 <option value="">— Select sport —</option>
 <option>Badminton</option>
 <option>Futsal</option>
 <option>Table Tennis</option>
 <option>Squash</option>
 <option>Pickleball</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label">Message to Venue Owner</label>
 <textarea class="form-input" rows="4" placeholder="Introduce yourself and explain why you'd like to coach at this venue..."></textarea>
 <div class="form-hint">Include your experience and certifications for a better chance of approval.</div>
 </div>
 </div>
 <div class="modal-footer">
 <button type="button" class="btn btn-secondary" onclick="CourtPassApp.closeModal('venueRequestModal')">Cancel</button>
 <button type="button" class="btn btn-primary" onclick="submitVenueRequest()">Submit Request</button>
 </div>
 </div>
</div>

<script>
function showNewVenueRequest() {
 CourtPassApp.openModal('venueRequestModal');
}

function submitVenueRequest() {
 CourtPassApp.closeModal('venueRequestModal');
 CourtPassApp.showToast('success', 'Request Submitted', 'Your venue access request has been sent to the owner for approval.');
}

function cancelVenueRequest(id) {
 CourtPassApp.showToast('info', 'Request Cancelled', `Venue request ${id} has been withdrawn.`);
}
</script>
