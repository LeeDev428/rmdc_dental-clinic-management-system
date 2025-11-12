<script>
let selectedUserId = null;
let selectedUserName = null;
let currentToothId = null;
let currentToothNumber = null;
let teethRecords = [];

const conditionColors = {
    'healthy': '#10b981', 'watch': '#fbbf24', 'cavity': '#f59e0b', 'treatment_needed': '#ef4444',
    'crown': '#8b5cf6', 'implant': '#3b82f6', 'root_canal': '#ec4899', 'missing': '#6b7280'
};

function filterUsers() {
    const searchValue = document.getElementById('user-search').value.toLowerCase();
    const userList = document.getElementById('user-list');
    const users = userList.getElementsByTagName('li');
    for (let user of users) user.style.display = user.textContent.toLowerCase().includes(searchValue) ? '' : 'none';
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
    if (!userId) return document.getElementById('teeth-layout-container').classList.add('d-none');
    document.getElementById('teeth-layout-container').classList.remove('d-none');
    fetch(`/admin/teeth-layout/records/${userId}`)
        .then(response => response.json())
        .then(data => { teethRecords = data.records || []; renderTeethChart(); updateStatistics(); })
        .catch(error => { console.error('Error:', error); teethRecords = []; renderTeethChart(); });
}

function renderTeethChart() {
    const chart = document.getElementById('teeth-chart');
    chart.querySelectorAll('.tooth-group').forEach(e => e.remove());
    calculateToothPositions().forEach(pos => drawTooth(chart, pos));
}

function calculateToothPositions() {
    const positions = [];
    const radiusX = 280, radiusY = 150;
    const centerX = 400, centerY = 300;
    const upperY = 150, lowerY = 450;
    
    // Upper Right (1-8) - right side arc
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 - (i / 14)); // Angle from right to center
        const x = centerX + radiusX * Math.cos(angle);
        const y = upperY - radiusY * Math.sin(angle);
        positions.push({
            number: i + 1,
            x: x,
            y: y,
            type: getToothType(i + 1),
            quadrant: 'upper_right'
        });
    }
    
    // Upper Left (9-16) - left side arc
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 + (i / 14)); // Angle from center to left
        const x = centerX + radiusX * Math.cos(angle);
        const y = upperY - radiusY * Math.sin(angle);
        positions.push({
            number: i + 9,
            x: x,
            y: y,
            type: getToothType(i + 9),
            quadrant: 'upper_left'
        });
    }
    
    // Lower Left (17-24) - left side arc
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 + (i / 14));
        const x = centerX + radiusX * Math.cos(angle);
        const y = lowerY + radiusY * Math.sin(angle);
        positions.push({
            number: i + 17,
            x: x,
            y: y,
            type: getToothType(i + 17),
            quadrant: 'lower_left'
        });
    }
    
    // Lower Right (25-32) - right side arc
    for (let i = 0; i < 8; i++) {
        const angle = Math.PI * (0.5 - (i / 14));
        const x = centerX + radiusX * Math.cos(angle);
        const y = lowerY + radiusY * Math.sin(angle);
        positions.push({
            number: i + 25,
            x: x,
            y: y,
            type: getToothType(i + 25),
            quadrant: 'lower_right'
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
    const shapes = {
        'incisor': 'M -8,-20 Q -10,-25 -6,-28 Q 0,-30 6,-28 Q 10,-25 8,-20 L 8,-10 Q 8,0 7,10 Q 6,18 4,22 Q 2,25 0,26 Q -2,25 -4,22 Q -6,18 -7,10 Q -8,0 -8,-10 Z',
        'canine': 'M -7,-22 Q -9,-27 -5,-32 Q 0,-35 5,-32 Q 9,-27 7,-22 L 7,-12 Q 7,-2 6,8 Q 5,16 3,22 Q 1,28 0,30 Q -1,28 -3,22 Q -5,16 -6,8 Q -7,-2 -7,-12 Z',
        'premolar': 'M -12,-18 Q -14,-22 -10,-25 Q -5,-27 0,-28 Q 5,-27 10,-25 Q 14,-22 12,-18 L 12,-8 Q 12,2 11,10 Q 9,16 6,20 Q 3,23 0,24 Q -3,23 -6,20 Q -9,16 -11,10 Q -12,2 -12,-8 Z',
        'molar': 'M -15,-18 Q -18,-24 -12,-28 Q -6,-30 0,-31 Q 6,-30 12,-28 Q 18,-24 15,-18 L 15,-8 Q 15,4 13,12 Q 11,18 7,22 Q 4,25 0,26 Q -4,25 -7,22 Q -11,18 -13,12 Q -15,4 -15,-8 Z'
    };
    return shapes[type] || shapes['incisor'];
}

function drawTooth(chart, position) {
    const record = teethRecords.find(r => r.tooth_number === position.number);
    const condition = record?.condition || 'healthy';
    const isMissing = record?.is_missing || false;
    if (isMissing) return;
    
    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    group.setAttribute('class', 'tooth-group');
    group.setAttribute('transform', `translate(${position.x},${position.y})`);
    group.setAttribute('data-tooth-number', position.number);
    group.style.cursor = 'pointer';
    
    // Create gradient for 3D effect
    const gradientId = `gradient-${position.number}`;
    const defs = chart.querySelector('defs') || document.createElementNS('http://www.w3.org/2000/svg', 'defs');
    if (!chart.querySelector('defs')) chart.insertBefore(defs, chart.firstChild);
    
    const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
    gradient.setAttribute('id', gradientId);
    gradient.setAttribute('x1', '0%');
    gradient.setAttribute('y1', '0%');
    gradient.setAttribute('x2', '0%');
    gradient.setAttribute('y2', '100%');
    
    const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop1.setAttribute('offset', '0%');
    stop1.setAttribute('style', `stop-color:${conditionColors[condition]};stop-opacity:1`);
    
    const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop2.setAttribute('offset', '100%');
    stop2.setAttribute('style', `stop-color:${conditionColors[condition]};stop-opacity:0.7`);
    
    gradient.appendChild(stop1);
    gradient.appendChild(stop2);
    defs.appendChild(gradient);
    
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', getToothPath(position.type));
    path.setAttribute('class', 'tooth-shape');
    path.setAttribute('fill', `url(#${gradientId})`);
    path.setAttribute('stroke', '#2c3e50');
    path.setAttribute('stroke-width', '2');
    path.setAttribute('filter', 'drop-shadow(2px 2px 3px rgba(0,0,0,0.3))');
    group.appendChild(path);
    
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0); text.setAttribute('y', 5); text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '12'); text.setAttribute('font-weight', 'bold'); text.setAttribute('fill', '#fff');
    text.setAttribute('class', 'tooth-label'); 
    text.setAttribute('style', 'text-shadow: 1px 1px 2px rgba(0,0,0,0.5);');
    text.textContent = position.number;
    group.appendChild(text);
    
    group.addEventListener('click', () => showToothDetails(position.number, record));
    chart.appendChild(group);
}

function updateStatistics() {
    const total = teethRecords.filter(r => !r.is_missing).length;
    const healthy = teethRecords.filter(r => r.condition === 'healthy').length;
    const treatment = teethRecords.filter(r => ['cavity', 'treatment_needed', 'root_canal'].includes(r.condition)).length;
    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-healthy').textContent = healthy;
    document.getElementById('stat-treatment').textContent = treatment;
}

function initializeDefaultLayout() {
    if (!selectedUserId) return alert('Please select a patient first.');
    if (!confirm('Initialize default 32-tooth layout for this patient?')) return;
    fetch(`/admin/tooth-records/initialize/${selectedUserId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => { alert(data.message); loadTeethLayout(selectedUserId); })
        .catch(error => { alert('Error initializing layout'); console.error(error); });
}

function showToothDetails(toothNumber, record) {
    currentToothId = record?.id; currentToothNumber = toothNumber;
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

function getQuadrantValue(number) {
    if (number <= 8) return 'upper_right';
    if (number <= 16) return 'upper_left';
    if (number <= 24) return 'lower_left';
    return 'lower_right';
}

function capitalizeFirst(str) { return str.charAt(0).toUpperCase() + str.slice(1); }

function loadToothNotes(toothRecordId) {
    const container = document.getElementById('notes-container');
    if (!toothRecordId) return container.innerHTML = '<p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>';
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
            } else container.innerHTML = '<p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>';
        })
        .catch(() => container.innerHTML = '<p style="color: #6b7280; text-align: center; font-size: 13px;">No notes available</p>');
}

function closeToothModal() {
    const modal = document.getElementById('tooth-detail-modal');
    modal.classList.remove('show');
    document.getElementById('note-content').value = '';
    document.getElementById('note-type-select').value = 'treatment';
    currentToothId = null;
    currentToothNumber = null;
}

function saveToothChanges() {
    const condition = document.getElementById('condition-select').value;
    const noteContent = document.getElementById('note-content').value.trim();
    const noteType = document.getElementById('note-type-select').value;
    
    if (!selectedUserId || !currentToothNumber) {
        alert('Please select a patient and tooth first');
        return;
    }
    
    console.log('Saving tooth changes...', {
        userId: selectedUserId,
        toothNumber: currentToothNumber,
        condition: condition
    });
    
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
    
    fetch('/admin/tooth-records/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(result => {
        console.log('Save result:', result);
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
    if (!currentToothId) {
        // If no record exists yet, just set condition to missing and save
        document.getElementById('condition-select').value = 'missing';
        saveToothChanges();
        return;
    }
    
    if (!confirm('Are you sure you want to mark this tooth as missing?')) {
        return;
    }
    
    console.log('Marking tooth as missing...', currentToothId);
    
    fetch(`/admin/tooth-records/${currentToothId}/mark-missing`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(result => {
        console.log('Mark missing result:', result);
        alert(result.message || 'Tooth marked as missing!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        console.error('Mark missing error:', error);
        alert('Error marking tooth as missing: ' + error.message);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Teeth layout page loaded');
    
    // Add keyboard shortcut to close modal (ESC key)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('tooth-detail-modal');
            if (modal && modal.classList.contains('show')) {
                closeToothModal();
            }
        }
    });
});
</script>
