<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>No-Show Disputes</span>
 </div>
 <h1 class="page-title">No-Show Dispute Resolution </h1>
 <div class="page-subtitle">Arbitrate contested no-show penalty flags between venue owners and customers.</div>
 </div>

 <div>
 <span class="badge badge-danger" style="font-size: 13px; padding: 6px 14px;">1 Active Dispute Awaiting Ruling</span>
 </div>
 </div>

 <!-- Dispute Item -->
 <div class="card" id="dispute-card-1" style="border-left: 4px solid var(--color-danger); margin-bottom: var(--space-6);">
 <div class="card-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
 <h3 class="card-title" style="font-size: 18px;">Dispute #DSP-891: Colombo Futsal Club vs Danushka W.</h3>
 <span class="badge badge-danger">High Priority</span>
 </div>
 <div class="card-subtitle">
 Booking Ref #CP-BK-4091 · Turf Court 1 · Slot: 14 Sep 2026 (21:00 - 22:00) · LKR 5,000 Cash-on-Arrival
 </div>
 </div>

 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="upholdPenalty('DSP-891')">
 Uphold Penalty (Keep 50% Restricted)
 </button>
 <button class="btn btn-sm btn-primary" onclick="reversePenalty('DSP-891')">
 Reverse Penalty & Restore Score (95%)
 </button>
 </div>
 </div>

 <div class="card-body">
 <div class="grid grid-cols-2 gap-6" style="margin-bottom: var(--space-4);">
 
 <!-- Owner's Claim -->
 <div style="background: var(--color-bg); padding: var(--space-4); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
 <span class="badge badge-confirmed">Venue Owner Report</span>
 <strong style="font-size: 13px;">Kusal Mendis (Owner)</strong>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); line-height: 1.5; margin-bottom: 6px;">
 "The customer booked Turf Court 1 under Cash-on-Arrival privilege for 9:00 PM. Front desk waited until 9:25 PM, customer never arrived and calls went unanswered. Slot went unutilized."
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">
 Check-in Desk Log: <strong>Marked No-Show at 21:30 by Staff PIN</strong>
 </div>
 </div>

 <!-- Customer's Appeal -->
 <div style="background: #fff8f8; border: 1px solid #fee2e2; padding: var(--space-4); border-radius: var(--radius-md);">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
 <span class="badge badge-danger">Customer Appeal</span>
 <strong style="font-size: 13px;">Danushka Wickramasinghe</strong>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); line-height: 1.5; margin-bottom: 6px;">
 "Our team reached the venue gate at 9:05 PM, but the area experienced an unexpected transformer fuse blow / blackout on De Alwis Place. The venue floodlights were off for 40 minutes. We left because playing was impossible in darkness."
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">
 Uploaded Proof: <button class="btn btn-xs btn-outline" onclick="CourtPassApp.showToast('Previewing CEB Power Interruption Notice SMS image...', 'info')"> CEB Outage SMS Log</button>
 </div>
 </div>

 </div>

 <!-- Impact Summary -->
 <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-md); padding: var(--space-3) var(--space-4); font-size: var(--font-size-xs); color: #1e40af;">
 <strong>System Impact:</strong> Customer's score dropped from 95% to 50% (Restricted Tier: barred from Cash-on-Arrival). Reversing this dispute will instantly reinstate the customer to <strong>95% Standard Tier</strong>.
 </div>
 </div>
 </div>

 </div>
 </div>
</div>

<script>
function reversePenalty(dspId) {
 document.getElementById('dispute-card-1').style.display = 'none';
 CourtPassApp.showToast(`Dispute #${dspId} resolved in customer's favor. Score restored to 95%!`, 'success');
}

function upholdPenalty(dspId) {
 document.getElementById('dispute-card-1').style.display = 'none';
 CourtPassApp.showToast(`Dispute #${dspId} closed. No-show penalty upheld.`, 'info');
}
</script>
