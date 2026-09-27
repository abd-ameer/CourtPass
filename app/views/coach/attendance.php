<?php
/** @var int $sessionId @var array $session the coach's session with its registrations @var array $sessions all the coach's sessions */
$regs = array_values(array_filter($session['registrations'], fn (array $r) => in_array($r['status'], ['registered', 'attended', 'absent'], true)));
$marked = fn (string $status) => count(array_filter($regs, fn (array $r) => $r['status'] === $status));
$started = strtotime($session['starts_at']) <= time();
$on = 'border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700; color: #fff; background: ';
$off = 'background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;';
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions') ?>">Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <span>Attendance Desk</span>
        </div>
        <h1 class="page-title">Attendance Desk</h1>
        <div class="page-subtitle">Mark who attended after the session starts. Marks can be corrected for 24 hours.</div>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-primary" onclick="saveAllAttendance()"<?= $regs === [] ? ' disabled' : '' ?>>Save All Attendance</button>
    </div>
</div>

<div class="card" style="margin-bottom: var(--space-6); border-left: 4px solid var(--color-primary-active);">
    <div class="card-body">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="flex: 1; min-width: 280px;">
                <label class="form-label" for="session-select" style="font-weight: 700; color: var(--color-text-subtle); margin-bottom: 6px;">SESSION</label>
                <select class="form-control" id="session-select" onchange="switchSession(this.value)" style="font-weight: 600;">
                    <?php foreach ($sessions as $opt): ?>
                        <option value="<?= (int) $opt['id'] ?>" <?= (int) $opt['id'] === (int) $sessionId ? 'selected' : '' ?>><?= e($opt['title']) ?> · <?= e(format_datetime($opt['starts_at'])) ?> (<?= e($opt['court_name']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <?php if (!$started): ?>
                    <div class="text-xs text-muted" style="margin-top: 6px;">This session has not started yet. Attendance can be marked from <?= e(format_datetime($session['starts_at'])) ?>.</div>
                <?php endif; ?>
            </div>
            <div style="display: flex; gap: 20px; align-items: center;">
                <div style="text-align: center; padding: 0 12px; border-right: 1px solid var(--color-border);">
                    <div style="font-size: 20px; font-weight: 900; color: var(--color-text-heading);" id="stat-total"><?= count($regs) ?></div>
                    <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Registered</div>
                </div>
                <div style="text-align: center; padding: 0 12px; border-right: 1px solid var(--color-border);">
                    <div style="font-size: 20px; font-weight: 900; color: var(--color-success);" id="stat-present"><?= $marked('attended') ?></div>
                    <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Present</div>
                </div>
                <div style="text-align: center; padding: 0 12px; border-right: 1px solid var(--color-border);">
                    <div style="font-size: 20px; font-weight: 900; color: var(--color-danger);" id="stat-absent"><?= $marked('absent') ?></div>
                    <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Absent</div>
                </div>
                <div style="text-align: center; padding: 0 12px;">
                    <div style="font-size: 20px; font-weight: 900; color: var(--color-warning);" id="stat-pending"><?= $marked('registered') ?></div>
                    <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Unmarked</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 12px;">
    <div style="display: flex; gap: 8px;">
        <button class="btn btn-sm btn-outline" onclick="markAll('attended')">Mark All Attended</button>
        <button class="btn btn-sm btn-outline" onclick="markAll('absent')">Mark All Absent</button>
        <button class="btn btn-sm btn-outline" onclick="resetAttendance()">Reset</button>
    </div>
    <input type="text" class="form-control" id="student-search" placeholder="Search student by name or email" onkeyup="filterStudents(this.value)" style="max-width: 260px;">
</div>

<div class="card">
    <?php if ($regs === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No students to mark</div>
            <div class="empty-state-desc">Students who register for this session appear here.</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table" id="attendance-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Student</th>
                        <th>Payment</th>
                        <th style="text-align: center; width: 260px;">Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regs as $i => $r): ?>
                        <tr data-registration-id="<?= (int) $r['id'] ?>">
                            <td><strong><?= sprintf('%02d', $i + 1) ?></strong></td>
                            <td>
                                <div style="font-weight: 700; color: var(--color-text-heading);"><?= e($r['customer_name']) ?></div>
                                <div style="font-size: 11px; color: var(--color-text-subtle);"><?= e($r['customer_email']) ?></div>
                            </td>
                            <td><?= $r['paid_amount'] === null ? '<span class="badge badge-unpaid">Not paid</span>' : '<span class="badge badge-paid">Paid ' . e(lkr($r['paid_amount'])) . '</span>' ?></td>
                            <td style="text-align: center;">
                                <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
                                    <button type="button" class="btn btn-sm att-btn<?= $r['status'] === 'attended' ? ' active-att' : '' ?>" onclick="setAttendance(this, 'attended')" style="<?= $r['status'] === 'attended' ? $on . 'var(--color-primary-active);' : $off ?>">Attended</button>
                                    <button type="button" class="btn btn-sm att-btn<?= $r['status'] === 'absent' ? ' active-att' : '' ?>" onclick="setAttendance(this, 'absent')" style="<?= $r['status'] === 'absent' ? $on . 'var(--color-danger);' : $off ?>">Absent</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function setAttendance(btn, status) {
 const group = btn.closest('.btn-group');
 const buttons = group.querySelectorAll('.att-btn');
 
 buttons.forEach(b => {
 b.classList.remove('active-att');
 b.style.background = 'transparent';
 b.style.color = 'var(--color-text-subtle)';
 b.style.fontWeight = '400';
 });

 btn.classList.add('active-att');
 btn.style.fontWeight = '700';

 if (status === 'attended') {
 btn.style.background = 'var(--color-primary-active)';
 btn.style.color = '#ffffff';
 } else if (status === 'absent') {
 btn.style.background = 'var(--color-danger)';
 btn.style.color = '#ffffff';
 }

 updateLiveCounts();
}

function updateLiveCounts() {
 let present = 0, absent = 0, pending = 0;
 const rows = document.querySelectorAll('#attendance-table tbody tr');
 
 rows.forEach(row => {
 const active = row.querySelector('.active-att');
 if (!active) {
 pending++;
 } else {
 const txt = active.textContent.trim().toLowerCase();
 if (txt === 'attended') present++;
 else if (txt === 'absent') absent++;
 }
 });

 document.getElementById('stat-present').textContent = present;
 document.getElementById('stat-absent').textContent = absent;
 document.getElementById('stat-pending').textContent = pending;
}

function markAll(type) {
 const rows = document.querySelectorAll('#attendance-table tbody tr');
 rows.forEach(row => {
 const btns = row.querySelectorAll('.att-btn');
 if (type === 'attended') {
 setAttendance(btns[0], 'attended');
 } else if (type === 'absent') {
 setAttendance(btns[1], 'absent');
 }
 });
 CourtPassApp.showToast('info', 'Attendance', `All students marked as ${type}. Save to keep the changes.`);
}

function resetAttendance() {
 const rows = document.querySelectorAll('#attendance-table tbody tr');
 rows.forEach(row => {
 const buttons = row.querySelectorAll('.att-btn');
 buttons.forEach(b => {
 b.classList.remove('active-att');
 b.style.background = 'transparent';
 b.style.color = 'var(--color-text-subtle)';
 b.style.fontWeight = '400';
 });
 });
 updateLiveCounts();
 CourtPassApp.showToast('info', 'Attendance', 'All marks cleared.');
}

function filterStudents(query) {
 const q = query.toLowerCase();
 const rows = document.querySelectorAll('#attendance-table tbody tr');
 rows.forEach(row => {
 const text = row.textContent.toLowerCase();
 row.style.display = text.includes(q) ? '' : 'none';
 });
}




function saveAllAttendance() {
    const fields = {};
    document.querySelectorAll('#attendance-table tbody tr[data-registration-id]').forEach((row) => {
        const active = row.querySelector('.active-att');
        if (active) fields['attendance[' + row.dataset.registrationId + ']'] = active.textContent.trim().toLowerCase();
    });
    CourtPass.post('/coach/sessions/<?= (int) $sessionId ?>/attendance', fields, 'PUT');
}
function switchSession(id) {
    window.location.href = CourtPass.base + '/coach/sessions/' + encodeURIComponent(id) + '/attendance';
}
</script>
