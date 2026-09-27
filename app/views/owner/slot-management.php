<?php
/** @var array $courts the owner's courts (id, name, venue) @var int $courtId selected court, 0 when there are none @var string $date */
?>
<div class="page-header">
 <div>
 <div class="breadcrumb">
 <a href="<?= url('/owner/dashboard') ?>">Dashboard</a>
 <span class="breadcrumb-separator">/</span>
 <span>Slot Management</span>
 </div>
 <h1 class="page-title">Court Slot Schedule</h1>
 <div class="page-subtitle">Block slots for walk-ins/maintenance, release blocks, or mark unsold slots as Flash Deals.</div>
 </div>

 <a href="<?= url('/owner/flash-slots') ?>" class="btn btn-outline">
 Manage Active Flash Deals &rarr;
 </a>
 </div>

 <?php if ($courts === []): ?>
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-title">No courts yet</div>
            <div class="empty-state-desc">Slots appear here once you add a court to an approved venue.</div>
            <a href="<?= url('/owner/courts') ?>" class="btn btn-primary" style="margin-top: 12px;">Go to Courts</a>
        </div>
    </div>
<?php else: ?>
<!-- Court Switcher Tabs -->
<div class="court-tabs-nav">
    <?php foreach ($courts as $c): ?>
        <a href="<?= url('/owner/slots?court=' . $c['id']) ?>" class="court-tab-btn <?= $courtId === $c['id'] ? 'active' : '' ?>"><?= e($c['name']) ?><span class="text-xs text-muted"> · <?= e($c['venue']) ?></span></a>
    <?php endforeach; ?>
</div>

<!-- Availability Grid Container -->
 <div class="availability-container">
 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4); flex-wrap: wrap; gap: 8px;">
 <h3 style="font-size: 16px; margin-bottom: 0;">Click any slot to Block, Unblock, or Convert to Flash Deal</h3>
 <span class="badge tier-standard">Owner Admin Mode</span>
 </div>

 <!-- Date Tabs -->
 <div class="date-selector-bar" id="dateSelectorBar"></div>

 <!-- Slot Grid -->
 <div class="slots-grid" id="slotsGrid"></div>

 <!-- Legend -->
 <div class="availability-legend">
            <div class="legend-item"><div class="legend-swatch available"></div><span>Available</span></div>
            <div class="legend-item"><div class="legend-swatch booked"></div><span>Booked</span></div>
            <div class="legend-item"><div class="legend-swatch blocked"></div><span>Blocked (owner or coaching session)</span></div>
            <div class="legend-item"><div class="legend-swatch unavailable"></div><span>Unavailable / Past</span></div>
        </div>
 </div>
 </div>

 
 


<!-- Owner Slot Override Action Modal -->
<div id="ownerSlotActionModal" class="modal-backdrop">
 <div class="modal-dialog">
 <div class="modal-header">
 <h3 class="modal-title" id="slotModalTitle">Slot Action</h3>
 <button class="modal-close" onclick="CourtPassApp.closeModal('ownerSlotActionModal')">&times;</button>
 </div>
 <div class="modal-body">
 <div style="margin-bottom: 16px;">
 <div class="text-xs text-muted">Selected Slot Time</div>
 <div style="font-size: 16px; font-weight: 800;" id="slotModalTime"></div>
 </div>

 <div style="display: flex; flex-direction: column; gap: 10px;">
 <button type="button" class="btn btn-outline" style="justify-content: flex-start; text-align: left; padding: 12px;" onclick="executeSlotAction('block')">
 <div style="font-size: 20px; width: 32px;"></div>
 <div>
 <strong>Block Slot for Walk-ins or Maintenance</strong>
 <div class="text-xs text-muted">Prevents customers and coaches from reserving this slot.</div>
 </div>
 </button>

 <button type="button" class="btn btn-outline" style="justify-content: flex-start; text-align: left; padding: 12px; border-color: #fdba74;" onclick="executeSlotAction('flash')">
 <div style="font-size: 20px; width: 32px;"></div>
 <div>
 <strong>Convert to Flash Deal</strong>
 <div class="text-xs text-muted">Offer the slot at a discount until it starts.</div>
 </div>
 </button>

 <button type="button" class="btn btn-outline" style="justify-content: flex-start; text-align: left; padding: 12px;" onclick="executeSlotAction('unblock')">
 <div style="font-size: 20px; width: 32px;"></div>
 <div>
 <strong>Release Block / Make Available</strong>
 <div class="text-xs text-muted">Restores slot to regular online booking status.</div>
 </div>
 </button>
 </div>
 </div>
 </div>
</div>

<script src="<?= asset('js/availability.js') ?>"></script>
<script>
let currentSlot = null;

document.addEventListener('DOMContentLoaded', () => {
    CourtPassAvailability.init({ courtId: <?= (int) $courtId ?>, isGuest: false });

    // Owner mode: clicking any slot opens the action modal instead of selecting it.
    CourtPassAvailability.handleSlotClick = function (slot) {
        currentSlot = slot;
        document.getElementById('slotModalTitle').textContent = 'Manage Slot: ' + slot.start;
        document.getElementById('slotModalTime').textContent = CourtPassAvailability.selectedDate + ' · ' + slot.start + ' (1 hour)';
        CourtPassApp.openModal('ownerSlotActionModal');
    };
});

function executeSlotAction(action) {
    CourtPassApp.closeModal('ownerSlotActionModal');
    if (!currentSlot) return;
    if (action === 'block') {
        CourtPass.post('/owner/blocks', {
            court_id: CourtPassAvailability.courtId,
            block_date: CourtPassAvailability.selectedDate,
            start_time: currentSlot.start,
        });
    } else if (action === 'flash') {
        window.location.href = CourtPass.base + '/owner/flash-slots';
    } else if (currentSlot.block_id) {
        CourtPass.post('/owner/blocks/' + currentSlot.block_id, {}, 'DELETE');
    } else {
        CourtPassApp.showToast('warning', 'Not Blocked', 'Only an owner block can be released here.');
    }
}
</script>
<?php endif; ?>
