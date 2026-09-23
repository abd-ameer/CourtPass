<?php
$is_edit = $is_edit ?? isset($_GET['edit']);
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/customer/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/customer/reviews') ?>">My Reviews</a>
 <span class="breadcrumb-separator">/</span>
 <span><?php echo $is_edit ? 'Edit Review' : 'Write Review'; ?></span>
 </div>
 <h1 class="page-title"><?php echo $is_edit ? 'Edit Verified Review' : 'Write a Verified Review'; ?></h1>
 <div class="page-subtitle">Share your real experience with the Sri Lankan sporting community.</div>
 </div>
 </div>

 <div class="card" style="max-width: 620px; padding: var(--space-8); margin: 0 auto;">
 
 <!-- 7-Day Eligibility Notice (SRS Requirement) -->
 <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius-md); padding: 12px 16px; margin-bottom: var(--space-6); font-size: 12px; color: #1e40af;">
 <strong>Verified Review Eligibility:</strong> You attended a session within the last 7 days. Reviews on CourtPass are strictly gated on verified check-in events to eliminate fake feedback.
 </div>

 <form id="reviewForm" onsubmit="handleReviewSubmit(event)">
 
 <div class="form-group">
 <label class="form-label">Reviewing Facility or Coach <span class="required-star">*</span></label>
 <select class="form-select" required>
 <option value="Otters Club Squash" selected>Otters Club Squash (Attended Sept 18)</option>
 <option value="Colombo Futsal Club">Colombo Futsal Club (Attended Sept 14)</option>
 <option value="Coach Dilshan Perera">Coach Dilshan Perera (Badminton Clinic)</option>
 </select>
 </div>

 <!-- Interactive Star Rating Picker -->
 <div class="form-group">
 <label class="form-label">Overall Rating (1 to 5 Stars) <span class="required-star">*</span></label>
 <div style="display: flex; gap: 8px; font-size: 28px; cursor: pointer; color: #d97706;" id="starPicker">
 <span onclick="setRating(1)"></span>
 <span onclick="setRating(2)"></span>
 <span onclick="setRating(3)"></span>
 <span onclick="setRating(4)"></span>
 <span onclick="setRating(5)"></span>
 </div>
 <input type="hidden" name="rating" id="selectedRating" value="5">
 <span class="text-xs text-muted" id="ratingLabel">5 Stars - Excellent</span>
 </div>

 <div class="form-group">
 <label class="form-label">Your Honest Review & Feedback <span class="required-star">*</span></label>
 <textarea name="comment" class="form-control" rows="4" placeholder="How was the court surface, lighting, changing rooms, or coach instruction?" required><?php echo $is_edit ? 'Excellent turf condition after the recent upgrade! The drainage held up even during evening rain.' : ''; ?></textarea>
 <span class="form-feedback invalid"></span>
 </div>

 <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: var(--space-6);">
 <a href="<?= url('/customer/reviews') ?>" class="btn btn-secondary">
 Cancel
 </a>
 <button type="submit" class="btn btn-primary">
 <?php echo $is_edit ? 'Update Review' : 'Publish Verified Review'; ?> &rarr;
 </button>
 </div>
 </form>

 </div>

 </div>
 </div>
</div>

<script>
function setRating(val) {
 document.getElementById('selectedRating').value = val;
 const stars = document.querySelectorAll('#starPicker span');
 stars.forEach((s, idx) => {
 s.style.color = (idx < val) ? '#d97706' : '#d1d5db';
 });

 const labels = ['', '1 Star - Poor', '2 Stars - Fair', '3 Stars - Good', '4 Stars - Very Good', '5 Stars - Excellent'];
 document.getElementById('ratingLabel').textContent = labels[val];
}

function handleReviewSubmit(e) {
 e.preventDefault();
 CourtPassApp.showToast('success', 'Review Published!', 'Thank you! Your verified review is now live.');
 setTimeout(() => {
 window.location.href = '<?= url('/customer/reviews') ?>';
 }, 1200);
}
</script>
