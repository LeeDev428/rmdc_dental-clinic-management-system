<div id="tooth-detail-modal" class="tooth-modal-overlay" onclick="handleModalClick(event)">
    <div class="tooth-modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h5 class="modal-title" id="modal-tooth-title">Tooth Details</h5>
            <button type="button" class="close" onclick="closeToothModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Number</div>
                    <div class="detail-value" id="detail-number">1</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Quadrant</div>
                    <div class="detail-value" id="detail-quadrant">Upper Right</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Type</div>
                    <div class="detail-value" id="detail-type">Incisor</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Condition</div>
                    <div class="detail-value" id="detail-condition">Healthy</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Change Condition:</label>
                <select id="condition-select" class="form-select">
                    <option value="healthy">Healthy</option>
                    <option value="watch">Watch/Monitor</option>
                    <option value="cavity">Cavity</option>
                    <option value="treatment_needed">Treatment Needed</option>
                    <option value="crown">Crown</option>
                    <option value="filling">Filling</option>
                    <option value="root_canal">Root Canal</option>
                    <option value="missing">Missing</option>
                </select>
            </div>

            <div class="notes-section">
                <div class="section-title">Notes History</div>
                <div id="notes-container">
                    <p class="text-muted text-center">No notes yet</p>
                </div>
                
                <div class="form-group" style="margin-top:12px;">
                    <label class="form-label">Add Note:</label>
                    <select id="note-type-select" class="form-select" style="margin-bottom:8px;">
                        <option value="treatment">Treatment</option>
                        <option value="observation">Observation</option>
                        <option value="plan">Plan</option>
                        <option value="follow-up">Follow-up</option>
                    </select>
                    <textarea id="note-content" class="form-control" placeholder="Enter note..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" onclick="markToothAsMissing()">
                <i class="fas fa-times"></i> Mark Missing
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeToothModal()">Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" onclick="saveToothChanges()">
                <i class="fas fa-save"></i> Save
            </button>
        </div>
    </div>
</div>

<style>
/* Tooth Modal Overlay - Separate from other modal styles */
.tooth-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 99999;
    padding: 20px;
}
.tooth-modal-overlay.show {
    display: flex !important;
}
.tooth-modal-content {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 500px;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: toothModalIn 0.2s ease-out;
}
@keyframes toothModalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>
