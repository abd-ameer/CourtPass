<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
 <span class="breadcrumb-separator">/</span>
 <span>Notifications</span>
 </div>
 <h1 class="page-title">Notifications </h1>
 <div class="page-subtitle">Stay updated on student session enrollments, venue approvals, and earnings payouts.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-outline" onclick="markAllRead()">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
 Mark All as Read
 </button>
 </div>
 </div>

 <!-- Notification Filter Tabs -->
 <div style="display: flex; gap: 8px; margin-bottom: var(--space-6); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-3);">
 <button class="btn btn-sm btn-primary notif-tab active" onclick="filterNotifs('all', this)">All (6)</button>
 <button class="btn btn-sm btn-outline notif-tab" onclick="filterNotifs('enrollment', this)">Student Enrollments (3)</button>
 <button class="btn btn-sm btn-outline notif-tab" onclick="filterNotifs('venue', this)">Venue Approvals (1)</button>
 <button class="btn btn-sm btn-outline notif-tab" onclick="filterNotifs('payout', this)">Payouts (2)</button>
 </div>

 <!-- Notifications List -->
 <div style="display: flex; flex-direction: column; gap: var(--space-3);" id="notifs-container">

 <!-- Notif 1: New student enrollment -->
 <div class="card notif-item unread" data-type="enrollment" style="background: #ffffff; border-left: 4px solid var(--color-primary-active);">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #e6f8f0; color: var(--color-primary-active); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">New Student Enrolled: Chamath Silva</strong>
 <span class="badge badge-primary">New</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Chamath registered for <strong>Advanced Smash & Footwork Drills</strong> on 22 Sep (18:00 - 19:00). Fee of LKR 3,500 collected via PayHere.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">15 minutes ago</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/coach/session-details') ?>?id=ses-1" class="btn btn-sm btn-outline">View Session</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 2: Venue Approval Received -->
 <div class="card notif-item unread" data-type="venue" style="background: #ffffff; border-left: 4px solid #3b82f6;">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">Venue Approval Granted: SpinMaster Sports Academy</strong>
 <span class="badge badge-confirmed">Approved</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 The owner approved your coaching request for Badminton Courts 1 & 2. You can now schedule public/private coaching sessions at this venue!
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">2 hours ago</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/coach/create-session') ?>" class="btn btn-sm btn-primary">+ Create Session</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 3: Payout Disbursed -->
 <div class="card notif-item" data-type="payout">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">Mid-Month Payout Disbursed: LKR 59,500</strong>
 <span class="badge badge-confirmed">Completed</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Bi-weekly coaching disbursement sent to your Commercial Bank account (*0021). Ref: CP-PY-8829.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">15 Sep 2026, 14:30</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/coach/earnings') ?>" class="btn btn-sm btn-outline">View Statement</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 4: New Student Enrollment -->
 <div class="card notif-item" data-type="enrollment">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #e6f8f0; color: var(--color-primary-active); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">New Student Enrolled: Dinuka Liyanage</strong>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Dinuka registered for <strong>Advanced Smash & Footwork Drills</strong> on 22 Sep.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">21 Sep 2026, 19:10</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/coach/session-details') ?>?id=ses-1" class="btn btn-sm btn-outline">View Session</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 5: New Student Enrollment -->
 <div class="card notif-item" data-type="enrollment">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #e6f8f0; color: var(--color-primary-active); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">New Student Enrolled: Sajith Fernando</strong>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Sajith registered for <strong>Advanced Smash & Footwork Drills</strong> on 22 Sep.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">20 Sep 2026, 21:05</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/coach/session-details') ?>?id=ses-1" class="btn btn-sm btn-outline">View Session</a>
 </div>
 </div>
 </div>
 </div>

 <!-- Notif 6: Platform Notice -->
 <div class="card notif-item" data-type="payout">
 <div class="card-body" style="padding: var(--space-4);">
 <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
 <div style="display: flex; gap: 14px;">
 <div style="width: 40px; height: 40px; border-radius: var(--radius-full); background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
 <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
 </div>
 <div>
 <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
 <strong style="color: var(--color-text-heading); font-size: var(--font-size-sm);">Verified Coach Badge Active!</strong>
 <span class="badge badge-confirmed">Platform</span>
 </div>
 <p style="font-size: var(--font-size-xs); color: var(--color-text-muted); margin-bottom: 6px;">
 Congratulations! Your SLBA Level 2 coaching certification was verified by the platform admin team.
 </p>
 <div style="font-size: 11px; color: var(--color-text-subtle);">10 Sep 2026</div>
 </div>
 </div>

 <div style="display: flex; gap: 6px; flex-shrink: 0;">
 <a href="<?= url('/coach/profile') ?>" class="btn btn-sm btn-outline">View Badge</a>
 </div>
 </div>
 </div>
 </div>

 </div>

 </div>
 </div>
</div>

<script>
function filterNotifs(type, btn) {
 document.querySelectorAll('.notif-tab').forEach(t => {
 t.classList.remove('btn-primary', 'active');
 t.classList.add('btn-outline');
 });
 btn.classList.remove('btn-outline');
 btn.classList.add('btn-primary', 'active');

 const items = document.querySelectorAll('.notif-item');
 items.forEach(item => {
 if (type === 'all' || item.dataset.type === type) {
 item.style.display = '';
 } else {
 item.style.display = 'none';
 }
 });
}

function markAllRead() {
 document.querySelectorAll('.notif-item').forEach(item => {
 item.classList.remove('unread');
 item.style.borderLeft = '';
 const badge = item.querySelector('.badge-primary');
 if (badge) badge.remove();
 });
 CourtPassApp.showToast('All notifications marked as read', 'success');
}
</script>
