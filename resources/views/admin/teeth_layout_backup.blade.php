@extends('layouts.admin')

@section('title', 'Professional Teeth Layout Management')

@section('content')
<style>
    :root {
        --color-healthy: #10b981;
        --color-watch: #fbbf24;
        --color-cavity: #f59e0b;
        --color-treatment: #ef4444;
        --color-crown: #8b5cf6;
        --color-implant: #3b82f6;
        --color-missing: #6b7280;
        --color-root-canal: #ec4899;
        --color-extraction: #dc2626;
    }

    body {
        background-color: #f8f9fa;
        color: #1a1a1a;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 14px;
    }

    .content-wrapper {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        background-color: #fff;
        padding: 24px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: #1a1a1a;
    }

    .page-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .search-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 24px;
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .list-group {
        margin-top: 12px;
        max-height: 250px;
        overflow-y: auto;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        display: none;
    }

    .list-group-item {
        padding: 14px 16px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: all 0.2s;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item:hover {
        background-color: #f3f4f6;
    }

    .main-layout-container {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
    }

    .teeth-layout-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 24px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .svg-dental-chart {
        width: 100%;
        max-width: 600px;
        height: auto;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        position: relative;
        padding: 20px;
    }

    .tooth-group {
        cursor: move;
        transition: transform 0.2s;
    }

    .tooth-group:hover {
        transform: scale(1.1);
    }

    .tooth-group.dragging {
        opacity: 0.5;
    }

    .tooth-group path {
        transition: all 0.3s;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    .tooth-group:hover path {
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
    }

    .tooth-label {
        font-weight: 600;
        pointer-events: none;
        user-select: none;
    }

    .quadrant-label {
        font-size: 18px;
        font-weight: 700;
        fill: #6b7280;
        opacity: 0.5;
    }

    .legend-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 24px;
    }

    .legend-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f3f4f6;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .legend-item:hover {
        background-color: #f3f4f6;
    }

    .legend-color {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .legend-text {
        font-size: 13px;
        color: #374151;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 20px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #2563eb;
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

    /* Tooth Detail Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1050;
        animation: fadeIn 0.2s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal.show {
        display: flex !important;
    }

    .modal-dialog {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        animation: slideUp 0.3s;
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        padding: 24px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .modal-body {
        padding: 24px;
        max-height: 60vh;
        overflow-y: auto;
    }

    .tooth-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .detail-item {
        padding: 16px;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
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

    .notes-section {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 2px solid #f3f4f6;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
    }

    .note-item {
        padding: 16px;
        background: #fff;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .note-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .note-type {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .note-type.treatment {
        background: #fef3c7;
        color: #92400e;
    }

    .note-type.observation {
        background: #dbeafe;
        color: #1e40af;
    }

    .note-date {
        font-size: 12px;
        color: #6b7280;
    }

    .note-content {
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
    }

    .modal-footer {
        padding: 20px 24px;
        background: #f9fafb;
        border-top: 2px solid #f3f4f6;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .close {
        background: rgba(255,255,255,0.2);
        border: none;
        font-size: 28px;
        color: #fff;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .close:hover {
        background: rgba(255,255,255,0.3);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        width: 100%;
        transition: all 0.2s;
    }

    .form-select:focus {
        border-color: #3b82f6;
        outline: none;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .patient-info-card {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .patient-name {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .patient-id {
        font-size: 14px;
        opacity: 0.9;
    }

    @media (max-width: 1024px) {
        .main-layout-container {
            grid-template-columns: 1fr;
        }
    }

    .tooltip-custom {
        position: absolute;
        background: rgba(0,0,0,0.9);
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        pointer-events: none;
        z-index: 1000;
        white-space: nowrap;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        padding: 16px;
        background: #f9fafb;
        border-radius: 8px;
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
        font-weight: 600;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h2 class="page-title">🦷 Professional Teeth Layout Management</h2>
        <p class="page-subtitle">Interactive dental charting system with comprehensive tooth tracking</p>
    </div>

    <!-- User Search -->
    <div class="search-card">
        <label for="user-search" class="form-label">🔍 Search Patient:</label>
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
        <div class="patient-info-card">
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

        <div class="main-layout-container">
            <!-- Teeth Chart -->
            <div class="teeth-layout-card">
                <div class="card-header">
                    <h4 class="card-title">Interactive Dental Chart</h4>
                </div>
                <div class="svg-dental-chart">
                    <svg id="teeth-chart" viewBox="0 0 600 800" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 800px;">
                        <!-- Quadrant lines -->
                        <line x1="300" y1="80" x2="300" y2="720" stroke="#d1d5db" stroke-width="2" stroke-dasharray="10,8"/>
                        <line x1="50" y1="400" x2="550" y2="400" stroke="#d1d5db" stroke-width="2" stroke-dasharray="10,8"/>
                        
                        <!-- Quadrant Labels -->
                        <text x="150" y="120" class="quadrant-label">UR (1-8)</text>
                        <text x="400" y="120" class="quadrant-label">UL (9-16)</text>
                        <text x="400" y="680" class="quadrant-label">LL (17-24)</text>
                        <text x="150" y="680" class="quadrant-label">LR (25-32)</text>
                    </svg>
                </div>
                <div class="action-buttons">
                    <button type="button" class="btn btn-success" onclick="initializeDefaultLayout()">
                        🔧 Initialize Default Layout
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveAllChanges()">
                        💾 Save All Changes
                    </button>
                </div>
            </div>

            <!-- Legend & Tools -->
            <div>
                <div class="legend-container">
                    <div class="legend-title">📊 Condition Legend</div>
                    <div class="legend-item" onclick="filterByCondition('healthy')">
                        <div class="legend-color" style="background-color: var(--color-healthy);"></div>
                        <div class="legend-text">Healthy</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('watch')">
                        <div class="legend-color" style="background-color: var(--color-watch);"></div>
                        <div class="legend-text">Watch/Monitor</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('cavity')">
                        <div class="legend-color" style="background-color: var(--color-cavity);"></div>
                        <div class="legend-text">Cavity</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('treatment_needed')">
                        <div class="legend-color" style="background-color: var(--color-treatment);"></div>
                        <div class="legend-text">Treatment Needed</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('crown')">
                        <div class="legend-color" style="background-color: var(--color-crown);"></div>
                        <div class="legend-text">Crown</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('implant')">
                        <div class="legend-color" style="background-color: var(--color-implant);"></div>
                        <div class="legend-text">Implant</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('root_canal')">
                        <div class="legend-color" style="background-color: var(--color-root-canal);"></div>
                        <div class="legend-text">Root Canal</div>
                    </div>
                    <div class="legend-item" onclick="filterByCondition('missing')">
                        <div class="legend-color" style="background-color: var(--color-missing);"></div>
                        <div class="legend-text">Missing</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tooth Detail Modal -->
<div id="tooth-detail-modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h5 class="modal-title" id="modal-tooth-title">Tooth #1 Details</h5>
            <button type="button" class="close" onclick="closeToothModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="tooth-detail-grid">
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
                    <option value="healthy">✓ Healthy</option>
                    <option value="watch">⚠️ Watch/Monitor</option>
                    <option value="cavity">🦷 Cavity</option>
                    <option value="treatment_needed">🔴 Treatment Needed</option>
                    <option value="crown">👑 Crown</option>
                    <option value="implant">🔩 Implant</option>
                    <option value="root_canal">💉 Root Canal</option>
                    <option value="missing">❌ Missing</option>
                </select>
            </div>

            <!-- Notes Section -->
            <div class="notes-section">
                <div class="section-title">📝 Clinical Notes</div>
                <div id="notes-container">
                    <p style="color: #6b7280; text-align: center;">No notes available for this tooth</p>
                </div>
                
                <!-- Add Note Form -->
                <div class="form-group" style="margin-top: 16px;">
                    <label class="form-label">Add New Note:</label>
                    <select id="note-type-select" class="form-select" style="margin-bottom: 12px;">
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
            <button type="button" class="btn btn-secondary" onclick="closeToothModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="saveToothChanges()">💾 Save Changes</button>
        </div>
    </div>
</div>

<script>
let selectedUserId = null;
let selectedUserName = null;
let currentToothId = null;
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
    'missing': '#6b7280',
    'extraction': '#dc2626'
};

// Tooth type shapes
const toothShapes = {
    'incisor': 'M -10,-15 Q 0,-20 10,-15 L 8,20 Q 0,25 -8,20 Z',
    'canine': 'M -8,-18 Q 0,-25 8,-18 L 6,22 Q 0,28 -6,22 Z',
    'premolar': 'M -12,-12 Q -15,-18 -8,-20 Q 0,-22 8,-20 Q 15,-18 12,-12 L 10,15 Q 0,20 -10,15 Z',
    'molar': 'M -18,-15 Q -20,-20 -10,-22 Q 0,-24 10,-22 Q 20,-20 18,-15 L 15,18 Q 0,25 -15,18 Z'
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
    
    // Update patient info
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
            // If no records exist, show empty chart
            teethRecords = [];
            renderTeethChart();
        });
}

function renderTeethChart() {
    const chart = document.getElementById('teeth-chart');
    
    // Remove all previous teeth
    chart.querySelectorAll('.tooth-group').forEach(e => e.remove());
    
    // Define tooth positions in arc
    const positions = calculateToothPositions();
    
    positions.forEach(pos => {
        drawTooth(chart, pos);
    });
}

function calculateToothPositions() {
    const positions = [];
    const rX = 180, rY = 280;
    const cx = 300, cy = 350, cy2 = 450;
    
    // Upper Right (1-8) - right to center
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 - (i / 14));
        positions.push({
            number: i + 1,
            x: cx + rX * Math.cos(angle),
            y: cy - rY * Math.sin(angle),
            type: getToothType(i + 1),
            quadrant: 'upper_right'
        });
    }
    
    // Upper Left (9-16) - center to left
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 + (i / 14));
        positions.push({
            number: i + 9,
            x: cx + rX * Math.cos(angle),
            y: cy - rY * Math.sin(angle),
            type: getToothType(i + 9),
            quadrant: 'upper_left'
        });
    }
    
    // Lower Left (17-24) - left to center
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 + (i / 14));
        positions.push({
            number: i + 17,
            x: cx + rX * Math.cos(angle),
            y: cy2 + rY * Math.sin(angle),
            type: getToothType(i + 17),
            quadrant: 'lower_left'
        });
    }
    
    // Lower Right (25-32) - center to right
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 - (i / 14));
        positions.push({
            number: i + 25,
            x: cx + rX * Math.cos(angle),
            y: cy2 + rY * Math.sin(angle),
            type: getToothType(i + 25),
            quadrant: 'lower_right'
        });
    }
    
    return positions;
}

function getToothType(number) {
    const mod = number % 8;
    if (mod === 1 || mod === 2 || mod === 0) return 'incisor';
    if (mod === 3) return 'canine';
    if (mod === 4 || mod === 5) return 'premolar';
    return 'molar';
}

function drawTooth(chart, position) {
    const record = teethRecords.find(r => r.tooth_number === position.number);
    const condition = record?.condition || 'healthy';
    const isMissing = record?.is_missing || false;
    const color = conditionColors[condition];
    
    if (isMissing) return; // Don't draw missing teeth
    
    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    group.setAttribute('class', 'tooth-group');
    group.setAttribute('transform', `translate(${position.x},${position.y})`);
    group.setAttribute('data-tooth-number', position.number);
    group.style.cursor = 'pointer';
    
    // Tooth shape
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', toothShapes[position.type]);
    path.setAttribute('fill', color);
    path.setAttribute('stroke', '#1f2937');
    path.setAttribute('stroke-width', '2');
    group.appendChild(path);
    
    // Tooth number label
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0);
    text.setAttribute('y', 5);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '14');
    text.setAttribute('font-weight', 'bold');
    text.setAttribute('fill', '#fff');
    text.setAttribute('class', 'tooth-label');
    text.textContent = position.number;
    group.appendChild(text);
    
    // Click event to show details
    group.addEventListener('click', () => showToothDetails(position.number, record));
    
    chart.appendChild(group);
}

function showToothDetails(toothNumber, record) {
    currentToothId = record?.id;
    
    document.getElementById('modal-tooth-title').textContent = `Tooth #${toothNumber} Details`;
    document.getElementById('detail-number').textContent = toothNumber;
    document.getElementById('detail-quadrant').textContent = getQuadrantName(toothNumber);
    document.getElementById('detail-type').textContent = capitalizeFirst(getToothType(toothNumber));
    document.getElementById('detail-condition').textContent = capitalizeFirst(record?.condition?.replace('_', ' ') || 'healthy');
    document.getElementById('condition-select').value = record?.condition || 'healthy';
    
    // Load notes (if available)
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
        container.innerHTML = '<p style="color: #6b7280; text-align: center;">No notes available for this tooth</p>';
        return;
    }
    
    // Fetch notes from API (placeholder for now)
    fetch(`/admin/tooth-records/${toothRecordId}/notes`)
        .then(response => response.json())
        .then(data => {
            if (data.notes && data.notes.length > 0) {
                container.innerHTML = data.notes.map(note => `
                    <div class="note-item">
                        <div class="note-header">
                            <span class="note-type ${note.note_type}">${note.note_type}</span>
                            <span class="note-date">${note.note_date}</span>
                        </div>
                        <div class="note-content">${note.content}</div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<p style="color: #6b7280; text-align: center;">No notes available for this tooth</p>';
            }
        })
        .catch(() => {
            container.innerHTML = '<p style="color: #6b7280; text-align: center;">No notes available for this tooth</p>';
        });
}

function closeToothModal() {
    document.getElementById('tooth-detail-modal').classList.remove('show');
    document.getElementById('note-content').value = '';
}

function saveToothChanges() {
    const toothNumber = parseInt(document.getElementById('detail-number').textContent);
    const condition = document.getElementById('condition-select').value;
    const noteContent = document.getElementById('note-content').value.trim();
    const noteType = document.getElementById('note-type-select').value;
    
    if (!selectedUserId) return;
    
    const data = {
        user_id: selectedUserId,
        tooth_number: toothNumber,
        condition: condition,
        quadrant: getQuadrantValue(toothNumber),
        tooth_type: getToothType(toothNumber),
        color_code: conditionColors[condition],
        is_missing: condition === 'missing',
        note_content: noteContent,
        note_type: noteType
    };
    
    fetch('/admin/tooth-records/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        alert(result.message || 'Changes saved successfully!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        alert('Error saving changes');
        console.error(error);
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

function saveAllChanges() {
    alert('All changes are saved automatically when you edit individual teeth.');
}

function updateStatistics() {
    const total = teethRecords.filter(r => !r.is_missing).length;
    const healthy = teethRecords.filter(r => r.condition === 'healthy').length;
    const treatment = teethRecords.filter(r => ['cavity', 'treatment_needed', 'root_canal'].includes(r.condition)).length;
    
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-healthy').textContent = healthy;
    document.getElementById('stat-treatment').textContent = treatment;
}

function filterByCondition(condition) {
    // Highlight teeth with this condition
    const groups = document.querySelectorAll('.tooth-group');
    groups.forEach(group => {
        const number = parseInt(group.getAttribute('data-tooth-number'));
        const record = teethRecords.find(r => r.tooth_number === number);
        if (record?.condition === condition) {
            group.style.transform = 'scale(1.2)';
            setTimeout(() => {
                group.style.transform = '';
            }, 1000);
        }
    });
}
</script>
@endsection
