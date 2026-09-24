<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
 <span class="breadcrumb-separator">/</span>
 <a href="<?= url('/coach/sessions') ?>">Sessions</a>
 <span class="breadcrumb-separator">/</span>
 <span>Attendance Desk</span>
 </div>
 <h1 class="page-title">Attendance Desk </h1>
 <div class="page-subtitle">Mark student check-ins and track session participation in real time.</div>
 </div>

 <div style="display: flex; gap: 10px;">
 <button class="btn btn-primary" onclick="saveAllAttendance()">
 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
 Save All Attendance
 </button>
 </div>
 </div>

 <!-- Active Session Selector Card -->
 <div class="card" style="margin-bottom: var(--space-6); border-left: 4px solid var(--color-primary-active);">
 <div class="card-body">
 <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
 <div style="flex: 1; min-width: 280px;">
 <label class="form-label" style="font-weight: 700; color: var(--color-text-subtle); margin-bottom: 6px;">SELECT SESSION TO MARK</label>
 <select class="form-control" id="session-select" onchange="switchSession(this.value)" style="font-weight: 600; font-size: var(--font-size-md);">
 <option value="1" <?= $sessionId === 1 ? 'selected' : '' ?>>Beginner Badminton Basics · Tue 22 Sep, 08:00 (Badminton Court 2)</option>
 <option value="2" <?= $sessionId === 2 ? 'selected' : '' ?>>Intermediate Rally Drills · Tue 29 Sep, 08:00 (Badminton Court 2)</option>
 <option value="3" <?= $sessionId === 3 ? 'selected' : '' ?>>Private Family Session · Wed 30 Sep, 07:00 (Badminton Court 1)</option>
 </select>
 </div>

 <div style="display: flex; gap: 20px; align-items: center;">
 <div style="text-align: center; padding: 0 12px; border-right: 1px solid var(--color-border);">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-text-heading);" id="stat-total">8</div>
 <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Enrolled</div>
 </div>
 <div style="text-align: center; padding: 0 12px; border-right: 1px solid var(--color-border);">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-success);" id="stat-present">6</div>
 <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Present</div>
 </div>
 <div style="text-align: center; padding: 0 12px; border-right: 1px solid var(--color-border);">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-danger);" id="stat-absent">1</div>
 <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Absent</div>
 </div>
 <div style="text-align: center; padding: 0 12px;">
 <div style="font-size: 20px; font-weight: 900; color: var(--color-warning);" id="stat-pending">1</div>
 <div style="font-size: 11px; color: var(--color-text-subtle); text-transform: uppercase;">Unmarked</div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <!-- Quick Action Toolbar -->
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 12px;">
 <div style="display: flex; gap: 8px;">
 <button class="btn btn-sm btn-outline" onclick="markAll('attended')">Mark All Attended</button>
 <button class="btn btn-sm btn-outline" onclick="markAll('absent')">Mark All Absent</button>
 <button class="btn btn-sm btn-outline" onclick="resetAttendance()">Reset</button>
 </div>

 <div style="position: relative; width: 260px;">
 <input type="text" class="form-control" id="student-search" placeholder="Search student by name or phone..." onkeyup="filterStudents(this.value)" style="padding-left: 34px; font-size: var(--font-size-xs);">
 <svg style="position: absolute; left: 10px; top: 10px; color: var(--color-text-subtle);" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
 </div>
 </div>

 <!-- Attendance Table Card -->
 <div class="card">
 <div class="table-container">
 <table class="table" id="attendance-table">
 <thead>
 <tr>
 <th style="width: 60px;">#</th>
 <th>Student</th>
 <th>Contact</th>
 <th>Registration Type</th>
 <th>Payment</th>
 <th style="text-align: center; width: 260px;">Attendance Status</th>
  </tr>
 </thead>
 <tbody>
 <!-- Row 1 -->
 <tr data-registration-id="1">
 <td><strong>01</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #e0f2fe; color: #0284c7;">KM</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Kasun Mendis</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Joined via Public Search</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 123 4567</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">kasun.m@gmail.com</div>
 </td>
 <td><span class="badge badge-primary">Public Slot</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'attended')" style="background: var(--color-primary-active); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 2 -->
 <tr data-registration-id="2">
 <td><strong>02</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #fef3c7; color: #d97706;">ND</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Naveen Dias</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Invited Student</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 71 889 2234</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">naveen.d@yahoo.com</div>
 </td>
 <td><span class="badge badge-warning">Private Link</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'attended')" style="background: var(--color-primary-active); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 3 -->
 <tr data-registration-id="3">
 <td><strong>03</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #fce7f3; color: #db2777;">AP</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Anura Perera</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Joined via Public Search</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 70 445 1109</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">anura.perera@gmail.com</div>
 </td>
 <td><span class="badge badge-primary">Public Slot</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'attended')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Attended</button>
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'absent')" style="background: var(--color-danger); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 4 -->
 <tr data-registration-id="4">
 <td><strong>04</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #ede9fe; color: #7c3aed;">TW</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Tharindu Wickrama</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Repeat Student</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 982 3411</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">tharindu.w@hotmail.com</div>
 </td>
 <td><span class="badge badge-primary">Public Slot</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'attended')" style="background: var(--color-primary-active); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 5 -->
 <tr data-registration-id="5">
 <td><strong>05</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #e0f2fe; color: #0284c7;">SF</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Sajith Fernando</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Joined via Public Search</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 72 312 9087</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">sajith.f@gmail.com</div>
 </td>
 <td><span class="badge badge-primary">Public Slot</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'attended')" style="background: var(--color-primary-active); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 6 -->
 <tr data-registration-id="6">
 <td><strong>06</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #dcfce7; color: #16a34a;">DL</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Dinuka Liyanage</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Joined via Public Search</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 654 3210</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">dinuka.l@gmail.com</div>
 </td>
 <td><span class="badge badge-primary">Public Slot</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'attended')" style="background: var(--color-primary-active); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 7 -->
 <tr data-registration-id="7">
 <td><strong>07</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #fee2e2; color: #dc2626;">RR</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Rashmika Rodrigo</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Invited Student</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 75 221 8890</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">rashmika.r@outlook.com</div>
 </td>
 <td><span class="badge badge-warning">Private Link</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn active-att" onclick="setAttendance(this, 'attended')" style="background: var(--color-primary-active); color: #fff; border-radius: 9999px; padding: 4px 12px; font-size: 11px; font-weight: 700;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>

 <!-- Row 8 (Pending/Unmarked) -->
 <tr data-registration-id="8">
 <td><strong>08</strong></td>
 <td>
 <div style="display: flex; align-items: center; gap: 10px;">
 <div class="user-avatar" style="width: 34px; height: 34px; font-size: 12px; background: #f3f4f6; color: #4b5563;">CS</div>
 <div>
 <div style="font-weight: 700; color: var(--color-text-heading);">Chamath Silva</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">Joined via Public Search</div>
 </div>
 </div>
 </td>
 <td>
 <div style="font-size: 12px; font-weight: 500;">+94 77 443 2190</div>
 <div style="font-size: 11px; color: var(--color-text-subtle);">chamath.s@gmail.com</div>
 </td>
 <td><span class="badge badge-primary">Public Slot</span></td>
 <td><span class="badge badge-confirmed"> Paid (PayHere)</span></td>
 <td style="text-align: center;">
 <div class="btn-group" style="display: inline-flex; border-radius: 9999px; padding: 2px; background: var(--color-bg);">
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'attended')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Attended</button>
 <button type="button" class="btn btn-sm att-btn" onclick="setAttendance(this, 'absent')" style="background: transparent; color: var(--color-text-subtle); border-radius: 9999px; padding: 4px 12px; font-size: 11px;">Absent</button>
 </div>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
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
