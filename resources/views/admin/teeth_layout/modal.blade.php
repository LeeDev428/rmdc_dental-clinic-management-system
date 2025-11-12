<div id="tooth-detail-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title" id="modal-tooth-title">Tooth Details</h5>
            <button type="button" class="close" onclick="closeToothModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Tooth Number</div>
                    <div class="detail-value" id="detail-number">1</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Quadrant</div>
                    <div class="detail-value" id="detail-quadrant">Upper Right</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tooth Type</div>
                    <div class="detail-value" id="detail-type">Incisor</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Current Condition</div>
                    <div class="detail-value" id="detail-condition">Healthy</div>
                </div>
            </div>

            <!-- Edit Condition Form -->
            <div class="form-group">
                <label class="form-label">Change Condition:</label>
                <select id="condition-select" class="form-select">
                    <option value="healthy">Healthy</option>
                    <option value="watch">Watch/Monitor</option>
                    <option value="cavity">Cavity</option>
                    <option value="treatment_needed">Treatment Needed</option>
                    <option value="crown">Crown</option>
                    <option value="implant">Implant</option>
                    <option value="root_canal">Root Canal</option>
                    <option value="missing">Missing</option>
                </select>
            </div>

            <!-- Notes Section -->
            <div class="notes-section">
                <div class="section-title">Clinical Notes</div>
                <div id="notes-container">
                    <p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>
                </div>
                
                <!-- Add Note Form -->
                <div class="form-group" style="margin-top: 12px;">
                    <label class="form-label">Add New Note:</label>
                    <select id="note-type-select" class="form-select" style="margin-bottom: 8px;">
                        <option value="treatment">Treatment</option>
                        <option value="observation">Observation</option>
                        <option value="plan">Treatment Plan</option>
                        <option value="follow-up">Follow-up</option>
                    </select>
                    <textarea id="note-content" class="form-control" placeholder="Enter note details..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="markToothAsMissing()">Mark as Missing</button>
            <button type="button" class="btn btn-secondary" onclick="closeToothModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="saveToothChanges()">Save Changes</button>
        </div>
    </div>
</div>
