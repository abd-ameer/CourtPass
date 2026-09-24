<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Reliability & Trust</span>
 </div>
 <div style="display: flex; align-items: center; gap: 8px;">
 <h1 class="page-title">Reliability Score & Tier Standing</h1>
 <span class="badge tier-standard">Standard Tier</span>
 </div>
 <div class="page-subtitle">Your trust rating determines access to flexible privileges like Cash on Arrival.</div>
 </div>

 <button class="btn btn-outline" onclick="openDisputeModal()">
 File a Penalty Dispute
 </button>
 </div>

 <!-- Current Score Hero Card -->
 <div class="card" style="padding: var(--space-6); margin-bottom: var(--space-6); background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1.5px solid var(--color-primary-border);">
 <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
 <div style="display: flex; align-items: center; gap: 24px;">
 <div style="width: 88px; height: 88px; border-radius: 50%; background: var(--color-primary); color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 900; box-shadow: var(--shadow-brand);">
 <span style="font-size: 28px; line-height: 1;">88%</span>
 <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.9;">Score</span>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
 <h2 style="font-size: 20px; margin-bottom: 0;">Standard Tier Standing</h2>
 <span class="badge badge-confirmed">Cash on Arrival Active</span>
 </div>
 <p class="text-sm" style="color: var(--color-text-main); margin-bottom: 4px;">
 Congratulations! You have completed <strong>14 verified matches</strong> with <strong>0 no-shows</strong>.
 </p>
 <div class="text-xs text-muted">
 Last calculated on: <strong>Sept 22, 2026 (Trigger-on-action basis)</strong>
 </div>
 </div>
 </div>

 <div style="background: white; padding: 12px 18px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); text-align: center;">
 <div class="text-xs text-muted">Payment Privilege</div>
 <div style="font-size: 15px; font-weight: 800; color: var(--color-primary); margin-top: 2px;">
 Online + Cash on Arrival
 </div>
 </div>
 </div>
 </div>

 <!-- Platform Reliability Tier System Breakdown Table (Direct from Proposal Table) -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">CourtPass Platform Tier Framework</h3>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Tier</th>
 <th>Reliability Score Criteria</th>
 <th>Payment Eligibility</th>
 <th>Your Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td><span class="badge tier-new">New Member</span></td>
 <td>Fewer than 5 completed bookings</td>
 <td>Online payment only (PayHere Sandbox)</td>
 <td><span class="text-xs text-muted">Passed (14 completed)</span></td>
 </tr>
 <tr>
 <td><span class="badge tier-restricted">Restricted</span></td>
 <td>Below 70% reliability</td>
 <td>Online payment only (PayHere Sandbox)</td>
 <td><span class="text-xs text-muted">Exceeded (88%)</span></td>
 </tr>
 <tr style="background: var(--color-primary-light);">
 <td><span class="badge tier-standard">Standard & Above</span></td>
 <td><strong>70% and above</strong> (with $\ge 5$ completed)</td>
 <td><strong>Choice of Online Payment or Cash-on-Arrival</strong></td>
 <td><span class="badge badge-confirmed">CURRENT TIER</span></td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Score History & Factor Weights -->
 <div class="grid grid-cols-2 gap-6">
 
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">How Reliability is Calculated</h3>
 </div>
 <div class="card-body" style="font-size: 13px; display: flex; flex-direction: column; gap: 10px;">
 <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--color-border-subtle);">
 <span>Completed & Checked-in Booking</span>
 <strong style="color: var(--color-primary);">+ Positive Impact</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--color-border-subtle);">
 <span>Responsible Cancellation (&gt; 48 hrs)</span>
 <strong>Neutral (No Penalty)</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding-bottom: 8px; border-bottom: 1px solid var(--color-border-subtle);">
 <span>Irresponsible Cancellation (&lt; 12 hrs on Cash)</span>
 <strong style="color: #ea580c;">- Partial Deduction</strong>
 </div>
 <div style="display: flex; justify-content: space-between;">
 <span>Unexcused No-Show Event</span>
 <strong style="color: var(--color-danger);">- Severe Deduction</strong>
 </div>
 </div>
 </div>

 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Contest an Unjust Penalty?</h3>
 </div>
 <div class="card-body">
 <p class="text-sm" style="color: var(--color-text-muted); margin-bottom: 14px;">
 If a venue marked a no-show in error due to front desk delays or system connectivity issues, platform administrators can clear your penalty upon review.
 </p>
 <button class="btn btn-outline-primary btn-block" onclick="openDisputeModal()">
 Submit No-Show Dispute Ticket &rarr;
 </button>
 </div>
 </div>

 </div>

 
 


<!-- Dispute Submission Modal -->
<div id="noShowDisputeModal" class="modal-backdrop">
 <div class="modal-dialog">
 <div class="modal-header">
 <h3 class="modal-title">Submit Penalty Dispute to Admin</h3>
 <button class="modal-close" onclick="CourtPassApp.closeModal('noShowDisputeModal')">&times;</button>
 </div>
 <div class="modal-body">
 <form method="POST" action="<?= url('/customer/disputes') ?>">
 <?= csrf_field() ?>
 <div class="form-group">
 <label class="form-label" for="disputeBooking">No-Show Booking <span class="required-star">*</span></label>
 <select name="booking_id" id="disputeBooking" class="form-select" required>
 <option value="">Choose booking...</option>
 <option value="2">#2: Colombo Sports Hub, Futsal Court A (Tue 22 Sep)</option>
 </select>
 </div>
 <div class="form-group">
 <label class="form-label" for="disputeReason">Explanation &amp; Evidence <span class="required-star">*</span></label>
 <textarea name="reason" id="disputeReason" class="form-control" rows="3" maxlength="1000" placeholder="Explain what happened at the venue (e.g. front desk was unattended, venue confirmed verbally...)" required></textarea>
 </div>
 <button type="submit" class="btn btn-primary btn-block">
 Submit to Platform Admin
 </button>
 </form>
 </div>
 </div>
</div>

<script>
function openDisputeModal() {
 CourtPassApp.openModal('noShowDisputeModal');
}

</script>
