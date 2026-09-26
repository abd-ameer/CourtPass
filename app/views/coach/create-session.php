<?php
/** @var array $venues @var array $old @var array $errors @var int $daysAhead @var int $maxCapacity @var int $maxFee */
$invalid = fn (string $f) => isset($errors[$f]) ? ' is-invalid' : '';
$oldCourt = (int) ($old['court_id'] ?? 0);
$oldVenue = 0;
foreach ($venues as $venue) {
    if (in_array($oldCourt, array_column($venue['courts'], 'id'), true)) {
        $oldVenue = $venue['id'];
    }
}
if ($oldVenue === 0 && count($venues) === 1) {
    $oldVenue = $venues[0]['id'];
}
$today = date('Y-m-d');
$lastDay = date('Y-m-d', strtotime('+' . $daysAhead . ' days'));
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="<?= url('/coach/dashboard') ?>">Coach Portal</a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/coach/sessions') ?>">Sessions</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">Create a Coaching Session</h1>
        <div class="page-subtitle">A one-hour session on a court at a venue that has approved you, from today up to <?= (int) $daysAhead ?> days ahead.</div>
    </div>
</div>

<?php if ($venues === []): ?>
    <div class="card" style="max-width: 760px; margin: 0 auto;">
        <div class="empty-state">
            <div class="empty-state-title">No approved venues yet</div>
            <div class="empty-state-desc">You can create sessions once a venue owner approves you, on courts for a sport you coach.</div>
            <a href="<?= url('/coach/venues') ?>" class="btn btn-primary" style="margin-top: 12px;">Go to Venue Approvals</a>
        </div>
    </div>
<?php else: ?>
<form id="createSessionForm" method="POST" action="<?= url('/coach/sessions') ?>" novalidate>
    <?= csrf_field() ?>
    <div class="grid grid-cols-3 gap-6">
        <div style="grid-column: span 2;">
            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Session Details</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label" for="sessionTitle">Title <span class="required-star">*</span></label>
                        <input type="text" name="title" id="sessionTitle" class="form-control<?= $invalid('title') ?>" value="<?= e($old['title'] ?? '') ?>" maxlength="100" placeholder="e.g. Badminton Fundamentals" required>
                        <span class="form-feedback invalid"><?= e($errors['title'] ?? '') ?></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sessionDesc">Description <span class="required-star">*</span></label>
                        <textarea name="description" id="sessionDesc" class="form-control<?= $invalid('description') ?>" rows="4" maxlength="2000" placeholder="What students will practise, the level it suits and what to bring." required><?= e($old['description'] ?? '') ?></textarea>
                        <span class="form-feedback invalid"><?= e($errors['description'] ?? '') ?></span>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Venue, Court and Slot</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="sessionVenue">Approved Venue <span class="required-star">*</span></label>
                            <select id="sessionVenue" class="form-select">
                                <option value="">Choose a venue</option>
                                <?php foreach ($venues as $venue): ?>
                                    <option value="<?= (int) $venue['id'] ?>" <?= $oldVenue === $venue['id'] ? 'selected' : '' ?>><?= e($venue['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="text-xs text-muted">Only venues that approved you are listed.</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sessionCourt">Court <span class="required-star">*</span></label>
                            <select name="court_id" id="sessionCourt" class="form-select<?= $invalid('court_id') ?>" required>
                                <option value="">Choose a venue first</option>
                            </select>
                            <span class="form-feedback invalid"><?= e($errors['court_id'] ?? '') ?></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="sessionDate">Date <span class="required-star">*</span></label>
                            <input type="date" name="session_date" id="sessionDate" class="form-control<?= $invalid('session_date') ?>" value="<?= e($old['session_date'] ?? '') ?>" min="<?= e($today) ?>" max="<?= e($lastDay) ?>" required>
                            <span class="form-feedback invalid"><?= e($errors['session_date'] ?? '') ?></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sessionTime">Free Slot (1 hour) <span class="required-star">*</span></label>
                            <select name="start_time" id="sessionTime" class="form-select<?= $invalid('slot') ?>" required>
                                <option value="">Choose a court and date first</option>
                            </select>
                            <span class="form-feedback invalid" id="slotFeedback"><?= e($errors['slot'] ?? '') ?></span>
                            <span class="text-xs text-muted" id="slotHint"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Capacity and Fee</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label" for="sessionCapacity">Max Participants <span class="required-star">*</span></label>
                            <input type="number" name="capacity" id="sessionCapacity" class="form-control<?= $invalid('capacity') ?>" value="<?= e($old['capacity'] ?? '') ?>" min="1" max="<?= (int) $maxCapacity ?>" required>
                            <span class="form-feedback invalid"><?= e($errors['capacity'] ?? '') ?></span>
                            <span class="text-xs text-muted">From 1 to <?= (int) $maxCapacity ?> students.</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="sessionFee">Fee per Person (LKR) <span class="required-star">*</span></label>
                            <input type="number" name="fee" id="sessionFee" class="form-control<?= $invalid('fee') ?>" value="<?= e($old['fee'] ?? '') ?>" min="0" max="<?= (int) $maxFee ?>" step="0.01" required>
                            <span class="form-feedback invalid"><?= e($errors['fee'] ?? '') ?></span>
                            <span class="text-xs text-muted">Enter 0 for a free session.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom: var(--space-6);">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Visibility</h3>
                </div>
                <div class="card-body">
                    <label style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-lg); margin-bottom: 12px; cursor: pointer;">
                        <input type="radio" name="visibility" value="public" <?= ($old['visibility'] ?? 'public') !== 'private' ? 'checked' : '' ?> style="margin-top: 3px;">
                        <div>
                            <div style="font-weight: 700; font-size: 14px;">Public</div>
                            <div class="text-xs text-muted">Listed on the Coaching page with a shareable link.</div>
                        </div>
                    </label>
                    <label style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 1px solid var(--color-border); border-radius: var(--radius-lg); cursor: pointer;">
                        <input type="radio" name="visibility" value="private" <?= ($old['visibility'] ?? '') === 'private' ? 'checked' : '' ?> style="margin-top: 3px;">
                        <div>
                            <div style="font-weight: 700; font-size: 14px;">Private</div>
                            <div class="text-xs text-muted">Not listed. Only people with its secret link can open it.</div>
                        </div>
                    </label>
                    <span class="form-feedback invalid"><?= e($errors['visibility'] ?? '') ?></span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 15px; margin-bottom: 0;">Summary</h3>
                </div>
                <div class="card-body" style="font-size: 13px;">
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Court</span><strong id="summCourt">Not chosen</strong></div>
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Slot</span><strong id="summSlot">Not chosen</strong></div>
                    <div style="display: flex; justify-content: space-between; padding: 6px 0;"><span class="text-muted">Max revenue if full</span><strong id="summRevenue">LKR 0</strong></div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: var(--space-4);">Create Session</button>
                    <a href="<?= url('/coach/sessions') ?>" class="btn btn-secondary" style="width: 100%; margin-top: var(--space-2); text-align: center;">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
(() => {
    const venues = <?= json_encode($venues, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const oldCourt = <?= (int) $oldCourt ?>;
    const oldTime = <?= json_encode(substr((string) ($old['start_time'] ?? ''), 0, 5), JSON_HEX_TAG) ?>;
    const venueSel = document.getElementById('sessionVenue');
    const courtSel = document.getElementById('sessionCourt');
    const dateInput = document.getElementById('sessionDate');
    const timeSel = document.getElementById('sessionTime');
    const hint = document.getElementById('slotHint');

    function option(value, text) {
        const opt = document.createElement('option');
        opt.value = value;
        opt.textContent = text;
        return opt;
    }

    function fillCourts(selected) {
        const venue = venues.find(v => String(v.id) === venueSel.value);
        courtSel.replaceChildren(option('', venue ? 'Choose a court' : 'Choose a venue first'));
        (venue ? venue.courts : []).forEach(c => {
            const opt = option(c.id, c.name + ' (' + c.sport + ')');
            opt.selected = c.id === selected;
            courtSel.appendChild(opt);
        });
    }

    async function loadSlots(selected) {
        timeSel.replaceChildren(option('', 'Choose a court and date first'));
        hint.textContent = '';
        if (!courtSel.value || !dateInput.value) {
            updateSummary();
            return;
        }
        hint.textContent = 'Loading free slots...';
        try {
            const data = await CourtPass.api('/api/courts/' + courtSel.value + '/slots?date=' + encodeURIComponent(dateInput.value));
            const free = data.slots.filter(s => s.state === 'available');
            timeSel.replaceChildren(option('', free.length ? 'Choose a slot' : 'No free slots on this day'));
            free.forEach(s => {
                const end = String(parseInt(s.start, 10) + 1).padStart(2, '0') + ':00';
                const opt = option(s.start, s.start + ' to ' + end);
                opt.selected = s.start === selected;
                timeSel.appendChild(opt);
            });
            hint.textContent = free.length ? free.length + ' free slot(s).' : 'Try another date or court.';
        } catch (e) {
            hint.textContent = 'Could not load slots for this court and date.';
        }
        updateSummary();
    }

    function updateSummary() {
        const court = courtSel.selectedOptions[0];
        document.getElementById('summCourt').textContent = courtSel.value ? court.textContent : 'Not chosen';
        document.getElementById('summSlot').textContent = timeSel.value && dateInput.value
            ? dateInput.value + ', ' + timeSel.selectedOptions[0].textContent : 'Not chosen';
        const cap = parseInt(document.getElementById('sessionCapacity').value, 10) || 0;
        const fee = parseFloat(document.getElementById('sessionFee').value) || 0;
        document.getElementById('summRevenue').textContent = fee > 0 ? CourtPass.lkr(cap * fee) : 'Free session';
    }

    venueSel.addEventListener('change', () => { fillCourts(0); loadSlots(''); });
    courtSel.addEventListener('change', () => loadSlots(''));
    dateInput.addEventListener('change', () => loadSlots(''));
    timeSel.addEventListener('change', () => {
        document.getElementById('slotFeedback').textContent = '';
        timeSel.classList.remove('is-invalid');
        updateSummary();
    });
    document.getElementById('sessionCapacity').addEventListener('input', updateSummary);
    document.getElementById('sessionFee').addEventListener('input', updateSummary);

    fillCourts(oldCourt);
    loadSlots(oldTime);
    CourtPassApp.setupFormValidation('createSessionForm');
})();
</script>
<?php endif; ?>
