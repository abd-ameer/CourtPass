<?php
/** @var array $courts the owner's courts with venue_name */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Flash Deals</span>
 </div>
 <h1 class="page-title">Flash Deals</h1>
 <div class="page-subtitle">Discount an upcoming free slot. The deal ends when the slot starts.</div>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;">
 
 <!-- Create Flash Deal Form -->
 <div class="card" style="padding: var(--space-6);">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
 <span style="font-size: 20px;"></span>
 <h3 style="font-size: 16px; margin-bottom: 0;">Create Flash Deal</h3>
 </div>
 
 <?php if ($courts === []): ?>
            <p class="text-sm" style="margin-bottom: 0;">Add a court to an approved venue first.</p>
        <?php else: ?>
        <form method="POST" action="<?= url('/owner/flash-slots') ?>">
 <?= csrf_field() ?>
 <div class="form-group">
 <label class="form-label">Target Court <span class="required-star">*</span></label>
 <select name="court_id" class="form-select" id="flashCourtSelect" onchange="updateOriginalRate()" required>
                    <?php foreach ($courts as $c): ?>
                        <option value="<?= (int) $c['id'] ?>" data-rate="<?= e($c['hourly_rate']) ?>"><?= e($c['name']) ?>, <?= e($c['venue_name']) ?> (Regular: <?= e(lkr($c['hourly_rate'])) ?>)</option>
                    <?php endforeach; ?>
 </select>
 </div>

 <div class="form-group">
 <label class="form-label" for="flashDate">Upcoming Unbooked Slot <span class="required-star">*</span></label>
 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
 <input type="date" name="slot_date" id="flashDate" class="form-control" required>
 <input type="time" name="start_time" class="form-control" step="3600" required>
 </div>
 <div class="text-xs text-muted" style="margin-top: 4px;">
 Booked or blocked slots, including coaching sessions, cannot become flash deals.
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Discounted Flash Price (LKR) <span class="required-star">*</span></label>
 <input type="number" name="discounted_price" id="flashPriceInput" class="form-control" value="" min="1" step="0.01" required>
 <div class="text-xs" style="color: #ea580c; margin-top: 4px;" id="discountCalculatedText">
 30% Discount (Save LKR 1,500)
 </div>
 </div>

 <button type="submit" class="btn btn-primary btn-block" style="background: #ea580c; border-color: #ea580c;">
 Launch Flash Deal &rarr;
 </button>
 </form>
        <?php endif; ?>
 </div>

 <!-- Active Flash Deals Table -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Active Flash Deals</h3>
 <span class="badge badge-flash">Ends at slot start</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Court & Time Slot</th>
 <th>Original Rate</th>
 <th>Flash Deal Rate</th>
 <th>Ends</th>
  </tr>
 </thead>
 <tbody>
                <?php // Sample row matching the seed flash deal until flash deals are built. ?>
                <tr>
                    <td>
                        <strong>Badminton Court 2</strong>
                        <div class="text-xs text-muted">Colombo Sports Hub · <?= e(format_datetime(relative_date(1), false)) ?> · 14:00 - 15:00</div>
                    </td>
                    <td><span class="text-muted" style="text-decoration: line-through;"><?= e(lkr(2000)) ?></span></td>
                    <td>
                        <strong style="color: #ea580c; font-size: 15px;"><?= e(lkr(1400)) ?></strong>
                        <span class="badge badge-flash" style="font-size: 10px; margin-left: 4px;">30% OFF</span>
                    </td>
                    <td><span class="badge badge-pending">Ends <?= e(format_datetime(relative_date(1, '14:00:00'))) ?></span></td>
                </tr>
            </tbody>
 </table>
 </div>
 </div>

 </div>

 
 


<script>
function updateOriginalRate() {
 const select = document.getElementById('flashCourtSelect');
 if (!select) return;
 const rate = parseFloat(select.options[select.selectedIndex].dataset.rate);
 const flashInput = document.getElementById('flashPriceInput');
 const discText = document.getElementById('discountCalculatedText');

 const flashVal = Math.round(rate * 0.7);
 flashInput.value = flashVal;
 discText.textContent = `30% Discount (Save LKR ${(rate - flashVal).toLocaleString()})`;
}

updateOriginalRate();
</script>
