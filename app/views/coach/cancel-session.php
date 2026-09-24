<div class="page-header">
 <div>
 <div class="breadcrumb">
 <span>Coach Portal</span>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions') ?>">Sessions</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions/' . $sessionId) ?>">#3</a>
 <span class="breadcrumb-separator">/</span>
 <span>Cancel</span>
 </div>
 <h1 class="page-title">Cancel Coaching Session</h1>
 <div class="page-subtitle">Review the impact of cancelling this session before confirming.</div>
 </div>
 </div>

 <div class="grid grid-cols-3 gap-6">
 
 <div style="grid-column: span 2;">
 
 <!-- Cancellation Warning -->
 <div style="background: #fef2f2; border: 2px solid #fecaca; border-radius: var(--radius-xl); padding: var(--space-6); margin-bottom: var(--space-6);">
 <div style="display: flex; align-items: flex-start; gap: 12px;">
 <div style="width: 44px; height: 44px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;"></div>
 <div>
 <h3 style="font-size: 16px; font-weight: 700; color: #991b1b; margin-bottom: 6px;">Are you sure you want to cancel this session?</h3>
 <p style="font-size: 13px; color: #b91c1c; line-height: 1.6; margin: 0;">
 Cancelling this session will <strong>automatically refund all 3 registered students</strong> via their original payment method (PayHere). 
 This action cannot be undone. Registered customers get an in-app notification and a full refund.
 </p>
 </div>
 </div>
 </div>

 <!-- Session Details Being Cancelled -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Session Being Cancelled</h3>
 <span class="badge badge-confirmed">Currently Active</span>
 </div>
 <div class="card-body">
 <div class="grid grid-cols-2 gap-4">
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Session</div>
 <div style="font-weight: 600;">Footwork Drills Intensive</div>
 <div class="text-xs text-muted">Session #3</div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Venue & Court</div>
 <div style="font-weight: 600;">CR&FC Badminton Complex</div>
 <div class="text-xs text-muted">Court 1 (Synthetic)</div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Date & Time</div>
 <div style="font-weight: 600;">September 24, 2026</div>
 <div class="text-xs text-muted">06:00 PM - 07:00 PM</div>
 </div>
 <div>
 <div class="text-xs text-muted" style="margin-bottom: 2px;">Fee / Person</div>
 <div style="font-weight: 600;">LKR 1,500</div>
 </div>
 </div>
 </div>
 </div>

 <!-- Affected Students -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Affected Students (3 refunds will be issued)</h3>
 </div>
 <div class="table-responsive">
 <table class="data-table">
 <thead>
 <tr>
 <th>Student</th>
 <th>Registered On</th>
 <th>Payment Method</th>
 <th>Refund Amount</th>
 <th>Refund Status</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td>
 <strong>Kasun De Silva</strong>
 <div class="text-xs text-muted">kasun.ds@email.com</div>
 </td>
 <td>Sept 20, 2026</td>
 <td><span class="badge badge-paid">PayHere Online</span></td>
 <td><strong>LKR 1,500</strong></td>
 <td><span class="badge badge-pending">Pending (Auto)</span></td>
 </tr>
 <tr>
 <td>
 <strong>Tharindu Fernando</strong>
 <div class="text-xs text-muted">tharindu.f@email.com</div>
 </td>
 <td>Sept 19, 2026</td>
 <td><span class="badge badge-paid">PayHere Online</span></td>
 <td><strong>LKR 1,500</strong></td>
 <td><span class="badge badge-pending">Pending (Auto)</span></td>
 </tr>
 <tr>
 <td>
 <strong>Ishara Bandara</strong>
 <div class="text-xs text-muted">ishara.b@email.com</div>
 </td>
 <td>Sept 18, 2026</td>
 <td><span class="badge badge-paid">PayHere Online</span></td>
 <td><strong>LKR 1,500</strong></td>
 <td><span class="badge badge-pending">Pending (Auto)</span></td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <!-- Cancellation Reason -->
 <div class="card" style="margin-bottom: var(--space-6);">
 <div class="card-header">
 <h3 style="font-size: 15px; margin-bottom: 0;">Cancellation Reason *</h3>
 </div>
 <div class="card-body">
 <div class="form-group">
 <select class="form-input" id="cancelReason" required onchange="toggleCustomReason()">
 <option value="">— Select reason —</option>
 <option value="personal">Personal / Health Reasons</option>
 <option value="weather">Unfavorable Weather</option>
 <option value="venue">Venue Unavailable</option>
 <option value="insufficient">Insufficient Registrations</option>
 <option value="other">Other</option>
 </select>
 </div>
 <div class="form-group" id="customReasonGroup" style="display: none;">
 <label class="form-label">Please specify</label>
 <textarea class="form-input" rows="3" placeholder="Provide additional details..." id="customReasonText"></textarea>
 </div>
 </div>
 </div>

 </div>

 <!-- Right: Impact Summary -->
 <div>
 <div class="card" style="position: sticky; top: 80px; border: 2px solid #fecaca;">
 <div class="card-header" style="background: #fef2f2;">
 <h3 style="font-size: 15px; margin-bottom: 0; color: #991b1b;"> Cancellation Impact</h3>
 </div>
 <div class="card-body">
 <div style="font-size: 13px;">
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Students Affected</span>
 <strong style="color: var(--color-danger);">3</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Total Refund Amount</span>
 <strong style="color: var(--color-danger);">LKR 4,500</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--color-border-light);">
 <span class="text-muted">Revenue Lost</span>
 <strong style="color: var(--color-danger);">LKR 4,050</strong>
 </div>
 <div style="display: flex; justify-content: space-between; padding: 8px 0;">
 <span class="text-muted">Court Slot</span>
 <strong>Released</strong>
 </div>
 </div>

 <div style="margin-top: var(--space-4); display: flex; flex-direction: column; gap: 8px;">
 <button type="button" class="btn" style="width: 100%; background: var(--color-danger); color: white; border: none;" onclick="confirmCancelSession()">
 Confirm Cancellation & Refund
 </button>
 <a href="<?= url('/coach/sessions/' . $sessionId) ?>" class="btn btn-secondary" style="width: 100%; text-align: center;">
 ← Keep Session Active
 </a>
 </div>
 </div>
 </div>
 </div>

 </div>

 
 


<script>
function toggleCustomReason() {
 const reason = document.getElementById('cancelReason').value;
 document.getElementById('customReasonGroup').style.display = reason === 'other' ? 'block' : 'none';
}

function confirmCancelSession() {
    const select = document.getElementById('cancelReason');
    if (!select.value) {
        CourtPassApp.showToast('error', 'Reason Required', 'Please select a cancellation reason.');
        return;
    }
    const custom = document.getElementById('customReasonText');
    const reason = select.value === 'other' && custom ? custom.value.trim() : select.selectedOptions[0].text;
    CourtPassApp.confirmDialog('Cancel Session', 'Every registration is refunded in full and the court slot is released.', 'Cancel Session',
        () => CourtPass.post('/coach/sessions/<?= (int) $sessionId ?>/cancel', { reason: reason }));
}
</script>
