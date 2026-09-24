<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Flash Deals</span>
 </div>
 <h1 class="page-title">Flash-Slot & Last-Minute Deals (UC-VO-14)</h1>
 <div class="page-subtitle">Discount upcoming unsold court slots to fill empty inventory. Auto-expires when slot starts (UC-AS-03).</div>
 </div>
 </div>

 <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 32px;">
 
 <!-- Create Flash Deal Form -->
 <div class="card" style="padding: var(--space-6);">
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
 <span style="font-size: 20px;"></span>
 <h3 style="font-size: 16px; margin-bottom: 0;">Create Flash Deal</h3>
 </div>
 
 <form method="POST" action="<?= url('/owner/flash-slots') ?>">
 <?= csrf_field() ?>
 <div class="form-group">
 <label class="form-label">Target Court <span class="required-star">*</span></label>
 <select name="court_id" class="form-select" id="flashCourtSelect" onchange="updateOriginalRate()" required>
 <option value="1" data-rate="5000">Futsal Court A (Regular: LKR 5,000)</option>
 <option value="2" data-rate="5000">Futsal Court B (Regular: LKR 5,000)</option>
 <option value="3" data-rate="2000">Badminton Court 1 (Regular: LKR 2,000)</option>
 </select>
 </div>

 <div class="form-group">
 <label class="form-label" for="flashDate">Upcoming Unbooked Slot <span class="required-star">*</span></label>
 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
 <input type="date" name="slot_date" id="flashDate" class="form-control" required>
 <input type="time" name="start_time" class="form-control" step="3600" required>
 </div>
 <div class="text-xs text-muted" style="margin-top: 4px;">
 Note: Coaching session slots cannot be marked as flash deals.
 </div>
 </div>

 <div class="form-group">
 <label class="form-label">Discounted Flash Price (LKR) <span class="required-star">*</span></label>
 <input type="number" name="discounted_price" id="flashPriceInput" class="form-control" value="3500" min="1" step="0.01" required>
 <div class="text-xs" style="color: #ea580c; margin-top: 4px;" id="discountCalculatedText">
 30% Discount (Save LKR 1,500)
 </div>
 </div>

 <button type="submit" class="btn btn-primary btn-block" style="background: #ea580c; border-color: #ea580c;">
 Launch Flash Deal &rarr;
 </button>
 </form>
 </div>

 <!-- Active Flash Deals Table -->
 <div class="card">
 <div class="card-header">
 <h3 style="font-size: 16px; margin-bottom: 0;">Active Flash Slots (Available Now)</h3>
 <span class="badge badge-flash">Auto-Expires (UC-AS-03)</span>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Court & Time Slot</th>
 <th>Original Rate</th>
 <th>Flash Deal Rate</th>
 <th>Auto-Expiry</th>
 <th style="text-align: right;">Action</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Turf Court 1 (Floodlit)</strong>
 <div class="text-xs text-muted">Today · 10:00 PM - 11:00 PM</div>
 </td>
 <td><span class="text-muted" style="text-decoration: line-through;">LKR 5,000</span></td>
 <td>
 <strong style="color: #ea580c; font-size: 15px;">LKR 3,500</strong>
 <span class="badge badge-flash" style="font-size: 10px; margin-left: 4px;">30% OFF</span>
 </td>
 <td>
 <span class="badge badge-pending">In 2 hrs 40 mins</span>
 </td>
 <td style="text-align: right;">
 <button class="btn btn-sm btn-secondary" style="color: var(--color-danger);" onclick="CourtPassApp.showToast('info', 'Deal Cancelled', 'Flash slot returned to standard rate.');">
 Cancel Deal
 </button>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 </div>

 
 


<script>
function updateOriginalRate() {
 const select = document.getElementById('flashCourtSelect');
 const rate = parseFloat(select.options[select.selectedIndex].dataset.rate);
 const flashInput = document.getElementById('flashPriceInput');
 const discText = document.getElementById('discountCalculatedText');

 const flashVal = Math.round(rate * 0.7);
 flashInput.value = flashVal;
 discText.textContent = `30% Discount (Save LKR ${(rate - flashVal).toLocaleString()})`;
}

</script>
