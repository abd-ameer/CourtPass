<?php
/** @var array $approved venues that approved the coach, with the courts they can use @var array $requestable listed venues that have not approved the coach */
?>
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <span>Coach Portal</span>
            <span class="breadcrumb-separator">/</span>
            <span>Venues</span>
        </div>
        <h1 class="page-title">Venue Approvals & Requests</h1>
        <div class="page-subtitle">A venue owner must approve you before you can create sessions on its courts.</div>
    </div>
    <button type="button" class="btn btn-primary" onclick="CourtPassApp.openModal('venueRequestModal')">
        + Request Venue Access
    </button>
</div>

<div class="card" style="margin-bottom: var(--space-6);">
    <div class="card-header">
        <h3 style="font-size: 15px; margin-bottom: 0;">Approved Venues (<?= count($approved) ?>)</h3>
        <span class="badge badge-confirmed">Can Create Sessions</span>
    </div>
    <?php if ($approved === []): ?>
        <div class="empty-state">
            <div class="empty-state-title">No approved venues yet</div>
            <div class="empty-state-desc">Request access to a venue. You can create sessions there once its owner approves you.</div>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Venue</th>
                        <th>Courts You Can Use</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($approved as $v): ?>
                        <tr>
                            <td><strong><?= e($v['name']) ?></strong></td>
                            <td>
                                <?php foreach ($v['courts'] as $c): ?>
                                    <div class="text-sm"><?= e($c['name']) ?> <span class="text-xs text-muted">(<?= e($c['sport']) ?>)</span></div>
                                <?php endforeach; ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= url('/coach/sessions/create') ?>" class="btn btn-sm btn-primary">+ Create Session</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="font-size: 15px; margin-bottom: 0;">Other Listed Venues (<?= count($requestable) ?>)</h3>
    </div>
    <?php if ($requestable === []): ?>
        <div class="card-body text-sm">Every listed venue has approved you.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Venue</th>
                        <th>Sports</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requestable as $v): ?>
                        <tr>
                            <td><strong><?= e($v['name']) ?></strong><div class="text-xs text-muted"><?= e($v['city']) ?></div></td>
                            <td><?= e(implode(', ', array_column($v['sports'], 'name'))) ?></td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-sm btn-outline" onclick="document.getElementById('reqVenue').value = '<?= (int) $v['id'] ?>'; CourtPassApp.openModal('venueRequestModal');">Request Access</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- New Venue Request Modal -->
<div class="modal-backdrop" id="venueRequestModal">
    <div class="modal-dialog" style="max-width: 520px;">
        <form method="POST" action="<?= url('/coach/venue-requests') ?>">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h3 class="modal-title">Request Venue Approval</h3>
                <button type="button" class="modal-close" onclick="CourtPassApp.closeModal('venueRequestModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="reqVenue">Venue <span class="required-star">*</span></label>
                    <select name="venue_id" id="reqVenue" class="form-select" required>
                        <option value="">Choose a venue</option>
                        <?php foreach ($requestable as $v): ?>
                            <option value="<?= (int) $v['id'] ?>"><?= e($v['name']) ?>, <?= e($v['city']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="text-xs text-muted">The venue owner approves or declines your request. Declined requests can be sent again later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="CourtPassApp.closeModal('venueRequestModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>
