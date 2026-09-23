<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/admin/dashboard') ?>">Admin Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Review Moderation</span>
 </div>
 <h1 class="page-title">Review Moderation </h1>
 <div class="page-subtitle">Inspect flagged customer reviews, enforce community guidelines, and moderate abusive content.</div>
 </div>

 <div>
 <span class="badge badge-warning" style="font-size: 13px; padding: 6px 14px;">1 Review Flagged for Moderation</span>
 </div>
 </div>

 <!-- Flagged Reviews Container -->
 <div style="display: flex; flex-direction: column; gap: var(--space-4);">

 <!-- Flagged Item 1 -->
 <div class="card" id="flagged-card-1" style="border-left: 4px solid var(--color-warning);">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3); flex-wrap: wrap; gap: 12px;">
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="font-size: 14px; color: var(--color-text-heading);">Review on Colombo Futsal Club</strong>
 <span class="badge badge-warning">Flagged by Venue Owner</span>
 </div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">
 Author: <strong>Anonymous User</strong> · Verified Booking #CP-BK-3392 · Reported 1 day ago
 </div>
 </div>

 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-outline" onclick="dismissFlag('flagged-card-1')">
 Dismiss Flag (Keep Review)
 </button>
 <button class="btn btn-sm btn-outline" style="color: var(--color-danger); border-color: var(--color-danger);" onclick="removeReview('flagged-card-1')">
 Remove Review for Policy Violation
 </button>
 </div>
 </div>

 <div style="background: var(--color-bg); padding: var(--space-3) var(--space-4); border-radius: var(--radius-md); margin-bottom: var(--space-3);">
 <div style="color: #f59e0b; font-size: 12px; margin-bottom: 4px;"> (1.0 Rating)</div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-body); margin-bottom: 0; line-height: 1.5;">
 "Worst venue in Colombo! The staff was extremely rude and the court was slippery. Don't waste your money here!"
 </p>
 </div>

 <div style="font-size: 11px; color: var(--color-text-subtle);">
 <strong>Owner's Flag Report:</strong> "Customer arrived 45 mins late without prior notice and was informed their slot had ended. Retaliatory review."
 </div>
 </div>
 </div>

 </div>

 </div>
 </div>
</div>

<script>
function dismissFlag(cardId) {
 document.getElementById(cardId).style.display = 'none';
 CourtPassApp.showToast('Flag dismissed. Review remains published on venue profile.', 'success');
}

function removeReview(cardId) {
 document.getElementById(cardId).style.display = 'none';
 CourtPassApp.showToast('Review removed from public view for policy violation.', 'error');
}
</script>
