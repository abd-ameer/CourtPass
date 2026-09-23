<!-- Mandatory Rejection / Cancellation Reason Modal -->
<div id="mandatoryReasonModal" class="modal-backdrop">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" id="reasonModalTitle">Enter Reason</h3>
            <button type="button" class="modal-close" onclick="CourtPassApp.closeModal('mandatoryReasonModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="reasonModalSubtitle" style="font-size: 13px; color: var(--color-text-muted); margin-bottom: 12px;">
                Please state the mandatory reason for this operational action.
            </p>
            <div class="form-group">
                <label class="form-label">Mandatory Explanation <span class="required-star">*</span></label>
                <textarea id="mandatoryReasonText" class="form-control" rows="3" placeholder="e.g., Unforeseen maintenance or invalid documentation..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="CourtPassApp.closeModal('mandatoryReasonModal')">Dismiss</button>
            <button type="button" class="btn btn-danger" id="mandatoryReasonSubmitBtn">Submit Decision</button>
        </div>
    </div>
</div>

<!-- Toast Notifications Container -->
<div id="toastContainer" class="toast-container"></div>
