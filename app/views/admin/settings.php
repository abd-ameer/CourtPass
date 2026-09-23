<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Platform Settings</span>
 </div>
 <h1 class="page-title">Platform Business Rules & Parameters </h1>
 <div class="page-subtitle">Configure global platform commissions, reliability scoring parameters, resale caps, and cancellation policies.</div>
 </div>

 <div>
 <button class="btn btn-primary" onclick="savePlatformSettings()">
 Save System Parameters
 </button>
 </div>
 </div>

 <div class="grid grid-cols-2 gap-6">

 <!-- Card 1: Reliability & Cash-on-Arrival Engine -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Reliability Score Engine Parameters</h3>
 <div class="card-subtitle">Rules dictating user trust tiers and Cash-on-Arrival privilege.</div>
 </div>
 <div class="card-body">
 <div class="form-group" style="margin-bottom: var(--space-4);">
 <label class="form-label">Cash-on-Arrival Minimum Score (%)</label>
 <input type="number" class="form-control" value="70" min="50" max="95">
 <span style="font-size: 11px; color: var(--color-text-subtle);">Users with a score below this threshold are restricted to Online PayHere only.</span>
 </div>

 <div class="form-group" style="margin-bottom: var(--space-4);">
 <label class="form-label">New Member Minimum Completed Bookings</label>
 <input type="number" class="form-control" value="5" min="1" max="15">
 <span style="font-size: 11px; color: var(--color-text-subtle);">New users must complete this many paid bookings before unlocking Cash-on-Arrival.</span>
 </div>

 <div class="form-group">
 <label class="form-label">No-Show Penalty Deduction (Points)</label>
 <input type="number" class="form-control" value="45" min="10" max="60">
 <span style="font-size: 11px; color: var(--color-text-subtle);">Score reduction incurred when an unexcused no-show is recorded.</span>
 </div>
 </div>
 </div>

 <!-- Card 2: Resale Marketplace Rules -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Resale Marketplace & Escrow Rules</h3>
 <div class="card-subtitle">Anti-scalping protections and resale listing caps.</div>
 </div>
 <div class="card-body">
 <div class="form-group" style="margin-bottom: var(--space-4);">
 <label class="form-label">Maximum Resale Price Cap (% of original fee)</label>
 <input type="number" class="form-control" value="90" min="50" max="100">
 <span style="font-size: 11px; color: var(--color-text-subtle);">Strict system constraint: sellers can never list higher than 90% of base slot price.</span>
 </div>

 <div class="form-group" style="margin-bottom: var(--space-4);">
 <label class="form-label">Resale Cutoff Window (Hours before slot)</label>
 <input type="number" class="form-control" value="6" min="1" max="24">
 <span style="font-size: 11px; color: var(--color-text-subtle);">Unsold tickets are automatically revoked and returned to original owner at cutoff.</span>
 </div>

 <div class="form-group">
 <label class="form-label">Resale Platform Processing Fee (%)</label>
 <input type="number" class="form-control" value="2.5" step="0.5" min="0" max="10">
 <span style="font-size: 11px; color: var(--color-text-subtle);">Deducted from seller payout upon successful secondary purchase.</span>
 </div>
 </div>
 </div>

 <!-- Card 3: Tiered Cancellation & Refund Policy -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Tiered Cancellation Rules</h3>
 <div class="card-subtitle">Standard refund tiers enforced at customer cancellation checkout.</div>
 </div>
 <div class="card-body">
 <div class="form-group" style="margin-bottom: var(--space-3);">
 <label class="form-label">> 48 Hours Prior Refund</label>
 <input type="text" class="form-control" value="100% Full Refund (Minus PG gateway fees)" readonly>
 </div>

 <div class="form-group" style="margin-bottom: var(--space-3);">
 <label class="form-label">12 to 48 Hours Prior Refund</label>
 <input type="text" class="form-control" value="50% Partial Refund (or list on Resale Marketplace)" readonly>
 </div>

 <div class="form-group">
 <label class="form-label">< 12 Hours Prior Refund</label>
 <input type="text" class="form-control" value="0% Non-refundable (Slot held for customer)" readonly>
 </div>
 </div>
 </div>

 <!-- Card 4: Supported Sri Lankan Sports -->
 <div class="card">
 <div class="card-header">
 <h3 class="card-title">Supported Sports Directory</h3>
 <div class="card-subtitle">Active sports categories supported across venue search and filters.</div>
 </div>
 <div class="card-body">
 <div style="display: flex; flex-wrap: wrap; gap: 8px;">
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Badminton</span>
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Futsal</span>
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Pickleball</span>
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Squash</span>
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Billiards & Snooker</span>
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Carrom</span>
 <span class="badge badge-confirmed" style="font-size: 12px; padding: 6px 12px;"> Table Tennis</span>
 </div>
 </div>
 </div>

 </div>

 </div>
 </div>
</div>

<script>
function savePlatformSettings() {
 CourtPassApp.showToast('Platform business rules & parameters saved successfully!', 'success');
}
</script>
