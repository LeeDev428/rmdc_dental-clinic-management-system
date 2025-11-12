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
    const radiusX = 260, radiusY = 140;
    const centerX = 400, centerY = 300;
    const upperY = 140, lowerY = 460;
    
    // Spacing adjustments for different tooth types
    const spacing = [0, 0.8, 1.6, 2.5, 3.5, 4.6, 5.9, 7.3]; // Custom spacing for natural arch
    
    // Upper Right (1-8) - right side arc
    for (let i = 0; i < 8; i++) {
        const progress = spacing[i] / 7.3;
        const angle = Math.PI * (0.5 - (progress * 0.45)); // 0.45 for better arc
        const x = centerX + radiusX * Math.cos(angle);
        const y = upperY - radiusY * Math.sin(angle);
        const rotation = -angle * (180 / Math.PI) + 90; // Perpendicular to arc
        
        positions.push({
            number: i + 1,
            x: x,
            y: y,
            rotation: rotation,
            type: getToothType(i + 1),
            quadrant: 'upper_right'
        });
    }
    
    // Upper Left (9-16) - left side arc
    for (let i = 0; i < 8; i++) {
        const progress = spacing[i] / 7.3;
        const angle = Math.PI * (0.5 + (progress * 0.45));
        const x = centerX + radiusX * Math.cos(angle);
        const y = upperY - radiusY * Math.sin(angle);
        const rotation = -angle * (180 / Math.PI) + 90;
        
        positions.push({
            number: i + 9,
            x: x,
            y: y,
            rotation: rotation,
            type: getToothType(i + 9),
            quadrant: 'upper_left'
        });
    }
    
    // Lower Left (17-24) - left side arc
    for (let i = 0; i < 8; i++) {
        const progress = spacing[i] / 7.3;
        const angle = Math.PI * (0.5 + (progress * 0.45));
        const x = centerX + radiusX * Math.cos(angle);
        const y = lowerY + radiusY * Math.sin(angle);
        const rotation = -angle * (180 / Math.PI) - 90;
        
        positions.push({
            number: i + 17,
            x: x,
            y: y,
            rotation: rotation,
            type: getToothType(i + 17),
            quadrant: 'lower_left'
        });
    }
    
    // Lower Right (25-32) - right side arc
    for (let i = 0; i < 8; i++) {
        const progress = spacing[i] / 7.3;
        const angle = Math.PI * (0.5 - (progress * 0.45));
        const x = centerX + radiusX * Math.cos(angle);
        const y = lowerY + radiusY * Math.sin(angle);
        const rotation = -angle * (180 / Math.PI) - 90;
        
        positions.push({
            number: i + 25,
            x: x,
            y: y,
            rotation: rotation,
            type: getToothType(i + 25),
            quadrant: 'lower_right'
        });
    }
    
    return positions;
}

function getToothType(number) {
    // Universal Numbering System (1-32)
    // Each quadrant has 8 teeth: 2 incisors, 1 canine, 2 premolars, 3 molars
    const position = ((number - 1) % 8) + 1;
    
    if (position === 1 || position === 2) return 'incisor';    // Central & Lateral Incisors
    if (position === 3) return 'canine';                        // Canine
    if (position === 4 || position === 5) return 'premolar';    // First & Second Premolars
    return 'molar';                                              // First, Second & Third Molars (6,7,8)
}

function getToothPath(type) {
    const shapes = {
        // Central and Lateral Incisors - narrow and flat
        'incisor': 'M -7,-22 Q -8,-26 -5,-28 Q 0,-29 5,-28 Q 8,-26 7,-22 L 7,-12 Q 7,0 6,10 Q 5,18 3,24 Q 1,28 0,29 Q -1,28 -3,24 Q -5,18 -6,10 Q -7,0 -7,-12 Z',
        
        // Canines - pointed and slightly longer
        'canine': 'M -6,-25 Q -7,-30 -4,-34 Q 0,-37 4,-34 Q 7,-30 6,-25 L 6,-15 Q 6,-3 5,8 Q 4,18 2,26 Q 1,32 0,34 Q -1,32 -2,26 Q -4,18 -5,8 Q -6,-3 -6,-15 Z',
        
        // Premolars - medium width with two cusps
        'premolar': 'M -10,-20 Q -12,-24 -8,-27 Q -4,-29 0,-30 Q 4,-29 8,-27 Q 12,-24 10,-20 Q 10,-18 9,-16 L 9,-8 Q 9,2 8,12 Q 7,20 4,26 Q 2,30 0,31 Q -2,30 -4,26 Q -7,20 -8,12 Q -9,2 -9,-8 L -9,-16 Q -10,-18 -10,-20 Z',
        
        // Molars - large and wide with multiple cusps
        'molar': 'M -14,-20 Q -16,-25 -12,-29 Q -8,-32 -4,-33 Q 0,-34 4,-33 Q 8,-32 12,-29 Q 16,-25 14,-20 Q 14,-18 13,-16 Q 12,-14 11,-12 L 11,-4 Q 11,6 9,16 Q 7,24 4,30 Q 2,34 0,35 Q -2,34 -4,30 Q -7,24 -9,16 Q -11,6 -11,-4 L -11,-12 Q -12,-14 -13,-16 Q -14,-18 -14,-20 Z'
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
