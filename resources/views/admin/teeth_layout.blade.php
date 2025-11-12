@extends('layouts.admin')

@section('title', 'Teeth Layout Management')

@section('content')
<style>
    .page-header {
        background-color: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    
    .content-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 24px;
        margin-bottom: 24px;
    }

    .search-section {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #4a4a4a;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-control:focus {
        outline: none;
        border-color: #0084ff;
    }

    .list-group {
        margin-top: 12px;
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        display: none;
        background: #fff;
    }

    .list-group-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .patient-info {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
    }

    .patient-name {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .patient-id {
        font-size: 14px;
        color: #6b7280;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 6px;
        text-align: center;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
        font-weight: 500;
    }

    .chart-container {
        background: #fff;
        padding: 24px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .chart-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .dental-chart {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        background: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
    }

    .tooth-group {
        cursor: pointer;
    }

    .tooth-group:hover .tooth-shape {
        opacity: 0.8;
    }

    .tooth-shape {
        transition: opacity 0.2s;
    }

    .tooth-label {
        font-weight: 600;
        pointer-events: none;
        user-select: none;
    }

    .quadrant-label {
        font-size: 14px;
        font-weight: 600;
        fill: #9ca3af;
    }

    .legend-section {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
    }

    .legend-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .legend-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 8px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        border-radius: 4px;
        font-size: 13px;
    }

    .legend-color {
        width: 20px;
        height: 20px;
        border-radius: 3px;
        border: 1px solid #e0e0e0;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 20px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: #0084ff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0073e6;
    }

    .btn-success {
        background-color: #10b981;
        color: #fff;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-danger {
        background-color: #ef4444;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #dc2626;
    }

    .d-none {
        display: none !important;
    }

    /* Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    .modal.show {
        display: flex !important;
    }

    .modal-dialog {
        background: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .modal-body {
        padding: 24px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .detail-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .detail-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 16px;
        color: #1a1a1a;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-select:focus {
        outline: none;
        border-color: #0084ff;
    }

    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    .notes-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 12px;
    }

    .note-item {
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .note-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .note-type {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 3px;
        font-weight: 600;
        text-transform: uppercase;
        background: #e0e0e0;
        color: #4a4a4a;
    }

    .note-date {
        font-size: 12px;
        color: #6b7280;
    }

    .note-content {
        font-size: 13px;
        color: #374151;
        line-height: 1.5;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .close {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .close:hover {
        background: #f0f0f0;
        color: #4a4a4a;
    }
</style>

<div class="page-header">
    <h2 class="page-title">Teeth Layout Management</h2>
</div>

<!-- Search Patient -->
<div class="content-card search-section">
    <label for="user-search" class="form-label">Search Patient:</label>
    <input type="text" id="user-search" class="form-control" placeholder="Search patient by name or ID..." oninput="filterUsers()">
    <ul id="user-list" class="list-group">
        @foreach($users as $user)
            <li class="list-group-item" onclick="selectUser({{ $user->id }}, '{{ $user->name }}')">
                <strong>{{ $user->name }}</strong> <span style="color: #6b7280;">(ID: {{ $user->id }})</span>
            </li>
        @endforeach
    </ul>
</div>

<div id="teeth-layout-container" class="d-none">
    <!-- Patient Info -->
    <div class="patient-info">
        <div class="patient-name" id="patient-name">Patient Name</div>
        <div class="patient-id" id="patient-id">Patient ID: #000</div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="stat-total">32</div>
            <div class="stat-label">Total Teeth</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-healthy" style="color: #10b981;">0</div>
            <div class="stat-label">Healthy</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-treatment" style="color: #ef4444;">0</div>
            <div class="stat-label">Need Treatment</div>
        </div>
    </div>

    <!-- Legend -->
    <div class="legend-section">
        <div class="legend-title">Condition Legend</div>
        <div class="legend-grid">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #10b981;"></div>
                <span>Healthy</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #fbbf24;"></div>
                <span>Watch</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #f59e0b;"></div>
                <span>Cavity</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ef4444;"></div>
                <span>Treatment</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #8b5cf6;"></div>
                <span>Crown</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #3b82f6;"></div>
                <span>Implant</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #ec4899;"></div>
                <span>Root Canal</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #6b7280;"></div>
                <span>Missing</span>
            </div>
        </div>
    </div>

    <!-- Dental Chart -->
    <div class="content-card chart-container">
        <div class="chart-title">Interactive Dental Chart</div>
        <div class="dental-chart">
            <svg id="teeth-chart" viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 600px;">
                <!-- Quadrant lines -->
                <line x1="400" y1="50" x2="400" y2="550" stroke="#d1d5db" stroke-width="1" stroke-dasharray="5,5"/>
                <line x1="100" y1="300" x2="700" y2="300" stroke="#d1d5db" stroke-width="1" stroke-dasharray="5,5"/>
                
                <!-- Quadrant Labels -->
                <text x="250" y="80" class="quadrant-label">Upper Right (1-8)</text>
                <text x="480" y="80" class="quadrant-label">Upper Left (9-16)</text>
                <text x="480" y="540" class="quadrant-label">Lower Left (17-24)</text>
                <text x="230" y="540" class="quadrant-label">Lower Right (25-32)</text>
            </svg>
        </div>
        <div class="action-buttons">
            <button type="button" class="btn btn-success" onclick="initializeDefaultLayout()">
                Initialize Default Layout
            </button>
        </div>
    </div>
</div>

<!-- Tooth Detail Modal -->
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

<script>
let selectedUserId = null;
let selectedUserName = null;
let currentToothId = null;
let currentToothNumber = null;
let teethRecords = [];

// Color mapping
const conditionColors = {
    'healthy': '#10b981',
    'watch': '#fbbf24',
    'cavity': '#f59e0b',
    'treatment_needed': '#ef4444',
    'crown': '#8b5cf6',
    'implant': '#3b82f6',
    'root_canal': '#ec4899',
    'missing': '#6b7280'
};

function filterUsers() {
    const searchValue = document.getElementById('user-search').value.toLowerCase();
    const userList = document.getElementById('user-list');
    const users = userList.getElementsByTagName('li');

    for (let user of users) {
        const text = user.textContent.toLowerCase();
        user.style.display = text.includes(searchValue) ? '' : 'none';
    }

    userList.style.display = searchValue ? 'block' : 'none';
}

function selectUser(userId, userName) {
    selectedUserId = userId;
    selectedUserName = userName;
    document.getElementById('user-search').value = userName;
    document.getElementById('user-list').style.display = 'none';
    
    document.getElementById('patient-name').textContent = userName;
    document.getElementById('patient-id').textContent = `Patient ID: #${userId}`;
    
    loadTeethLayout(userId);
}

function loadTeethLayout(userId) {
    if (!userId) {
        document.getElementById('teeth-layout-container').classList.add('d-none');
        return;
    }
    
    document.getElementById('teeth-layout-container').classList.remove('d-none');
    
    fetch(`/admin/teeth-layout/records/${userId}`)
        .then(response => response.json())
        .then(data => {
            teethRecords = data.records || [];
            renderTeethChart();
            updateStatistics();
        })
        .catch(error => {
            console.error('Error loading teeth layout:', error);
            teethRecords = [];
            renderTeethChart();
        });
}

function renderTeethChart() {
    const chart = document.getElementById('teeth-chart');
    
    chart.querySelectorAll('.tooth-group').forEach(e => e.remove());
    
    const positions = calculateToothPositions();
    
    positions.forEach(pos => {
        drawTooth(chart, pos);
    });
}

function calculateToothPositions() {
    const positions = [];
    const radiusX = 250, radiusY = 200;
    const centerX = 400, centerY = 300;
    
    // Upper teeth (1-16) - Arc from right to left
    for (let i = 0; i < 16; i++) {
        const angle = Math.PI * (1 - i / 15);
        positions.push({
            number: i + 1,
            x: centerX + radiusX * Math.cos(angle),
            y: centerY - Math.abs(radiusY * Math.sin(angle)) - 80,
            type: getToothType(i + 1),
            quadrant: i < 8 ? 'upper_right' : 'upper_left'
        });
    }
    
    // Lower teeth (17-32) - Arc from left to right
    for (let i = 0; i < 16; i++) {
        const angle = Math.PI * (i / 15);
        positions.push({
            number: i + 17,
            x: centerX + radiusX * Math.cos(angle),
            y: centerY + Math.abs(radiusY * Math.sin(angle)) + 80,
            type: getToothType(i + 17),
            quadrant: i < 8 ? 'lower_left' : 'lower_right'
        });
    }
    
    return positions;
}

function getToothType(number) {
    const mod = (number - 1) % 8;
    if (mod === 0 || mod === 1) return 'incisor';
    if (mod === 2) return 'canine';
    if (mod === 3 || mod === 4) return 'premolar';
    return 'molar';
}

function getToothPath(type) {
    const toothShapes = {
        'incisor': 'M -10,-15 Q 0,-20 10,-15 L 8,20 Q 0,25 -8,20 Z',
        'canine': 'M -8,-18 Q 0,-25 8,-18 L 6,22 Q 0,28 -6,22 Z',
        'premolar': 'M -12,-12 Q -15,-18 -8,-20 Q 0,-22 8,-20 Q 15,-18 12,-12 L 10,15 Q 0,20 -10,15 Z',
        'molar': 'M -18,-15 Q -20,-20 -10,-22 Q 0,-24 10,-22 Q 20,-20 18,-15 L 15,18 Q 0,25 -15,18 Z'
    };
    return toothShapes[type] || toothShapes['incisor'];
}

function drawTooth(chart, position) {
    const record = teethRecords.find(r => r.tooth_number === position.number);
    const condition = record?.condition || 'healthy';
    const isMissing = record?.is_missing || false;
    const color = conditionColors[condition];
    
    if (isMissing) return;
    
    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    group.setAttribute('class', 'tooth-group');
    group.setAttribute('transform', `translate(${position.x},${position.y})`);
    group.setAttribute('data-tooth-number', position.number);
    group.style.cursor = 'pointer';
    
    // Tooth shape
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', getToothPath(position.type));
    path.setAttribute('class', 'tooth-shape');
    path.setAttribute('fill', color);
    path.setAttribute('stroke', '#1f2937');
    path.setAttribute('stroke-width', '1.5');
    group.appendChild(path);
    
    // Tooth number label
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0);
    text.setAttribute('y', 5);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '11');
    text.setAttribute('font-weight', 'bold');
    text.setAttribute('fill', '#fff');
    text.setAttribute('class', 'tooth-label');
    text.textContent = position.number;
    group.appendChild(text);
    
    group.addEventListener('click', () => showToothDetails(position.number, record));
    
    chart.appendChild(group);
}

function showToothDetails(toothNumber, record) {
    currentToothId = record?.id;
    currentToothNumber = toothNumber;
    
    document.getElementById('modal-tooth-title').textContent = `Tooth #${toothNumber}`;
    document.getElementById('detail-number').textContent = toothNumber;
    document.getElementById('detail-quadrant').textContent = getQuadrantName(toothNumber);
    document.getElementById('detail-type').textContent = capitalizeFirst(getToothType(toothNumber));
    document.getElementById('detail-condition').textContent = capitalizeFirst(record?.condition?.replace('_', ' ') || 'healthy');
    document.getElementById('condition-select').value = record?.condition || 'healthy';
    
    loadToothNotes(record?.id);
    
    document.getElementById('tooth-detail-modal').classList.add('show');
}

function getQuadrantName(number) {
    if (number <= 8) return 'Upper Right';
    if (number <= 16) return 'Upper Left';
    if (number <= 24) return 'Lower Left';
    return 'Lower Right';
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function loadToothNotes(toothRecordId) {
    const container = document.getElementById('notes-container');
    
    if (!toothRecordId) {
        container.innerHTML = '<p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>';
        return;
    }
    
    fetch(`/admin/tooth-records/${toothRecordId}/notes`)
        .then(response => response.json())
        .then(data => {
            if (data.notes && data.notes.length > 0) {
                container.innerHTML = data.notes.map(note => `
                    <div class="note-item">
                        <div class="note-header">
                            <span class="note-type">${note.note_type}</span>
                            <span class="note-date">${note.note_date}</span>
                        </div>
                        <div class="note-content">${note.content}</div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>';
            }
        })
        .catch(() => {
            container.innerHTML = '<p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>';
        });
}

function closeToothModal() {
    console.log('Closing modal');
    document.getElementById('tooth-detail-modal').classList.remove('show');
    document.getElementById('note-content').value = '';
    document.getElementById('note-type-select').value = 'treatment';
}

function saveToothChanges() {
    const condition = document.getElementById('condition-select').value;
    const noteContent = document.getElementById('note-content').value.trim();
    const noteType = document.getElementById('note-type-select').value;
    
    console.log('Save button clicked');
    console.log('Selected User ID:', selectedUserId);
    console.log('Current Tooth Number:', currentToothNumber);
    console.log('Condition:', condition);
    console.log('Note Content:', noteContent);
    
    if (!selectedUserId || !currentToothNumber) {
        alert('Please select a patient and tooth first');
        return;
    }
    
    const data = {
        user_id: selectedUserId,
        tooth_number: currentToothNumber,
        condition: condition,
        quadrant: getQuadrantValue(currentToothNumber),
        tooth_type: getToothType(currentToothNumber),
        color_code: conditionColors[condition],
        is_missing: condition === 'missing',
        note_content: noteContent,
        note_type: noteType
    };
    
    console.log('Sending data:', data);
    
    fetch('/admin/tooth-records/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Success result:', result);
        alert(result.message || 'Changes saved successfully!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        console.error('Save error:', error);
        alert('Error saving changes: ' + error.message);
    });
}

function markToothAsMissing() {
    console.log('Mark as Missing clicked');
    console.log('Current Tooth ID:', currentToothId);
    
    if (!currentToothId) {
        // If no record exists yet, change condition to missing and save
        console.log('No tooth record exists, setting condition to missing');
        document.getElementById('condition-select').value = 'missing';
        saveToothChanges();
        return;
    }
    
    if (!confirm('Are you sure you want to mark this tooth as missing?')) return;
    
    fetch(`/admin/tooth-records/${currentToothId}/mark-missing`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Mark missing response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Mark missing success:', result);
        alert(result.message || 'Tooth marked as missing!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        console.error('Mark missing error:', error);
        alert('Error marking tooth as missing: ' + error.message);
    });
}

function getQuadrantValue(number) {
    if (number <= 8) return 'upper_right';
    if (number <= 16) return 'upper_left';
    if (number <= 24) return 'lower_left';
    return 'lower_right';
}

function initializeDefaultLayout() {
    if (!selectedUserId) {
        alert('Please select a patient first.');
        return;
    }
    
    if (!confirm('Initialize default 32-tooth layout for this patient?')) return;
    
    fetch(`/admin/tooth-records/initialize/${selectedUserId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        alert('Error initializing layout');
        console.error(error);
    });
}

function updateStatistics() {
    const total = teethRecords.filter(r => !r.is_missing).length;
    const healthy = teethRecords.filter(r => r.condition === 'healthy').length;
    const treatment = teethRecords.filter(r => ['cavity', 'treatment_needed', 'root_canal'].includes(r.condition)).length;
    
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-healthy').textContent = healthy;
    document.getElementById('stat-treatment').textContent = treatment;
}

// Close modal when clicking outside of it
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tooth-detail-modal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeToothModal();
        }
    });
});
</script>
@endsection
