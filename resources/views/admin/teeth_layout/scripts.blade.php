<script>
let selectedUserId = null;
let selectedUserName = null;
let currentToothId = null;
let currentToothNumber = null;
let teethRecords = [];

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
            console.error('Error:', error);
            teethRecords = [];
            renderTeethChart();
        });
}

function renderTeethChart() {
    const chart = document.getElementById('teeth-chart');
    chart.querySelectorAll('.tooth-group').forEach(e => e.remove());
    calculateToothPositions().forEach(pos => drawTooth(chart, pos));
}

function calculateToothPositions() {
    const positions = [];
    const centerX = 350;
    const upperArchY = 230;
    const lowerArchY = 670;
    const radiusX = 240;
    const radiusY = 110;
    
    // Upper Arch - Teeth 1-16 (right to left, only upper semicircle)
    for (let i = 0; i < 16; i++) {
        const t = i / 15; // 0 to 1
        const angle = Math.PI - (t * Math.PI); // π to 0 (right to left)
        
        const x = centerX + radiusX * Math.cos(angle);
        const y = upperArchY - Math.abs(radiusY * Math.sin(angle));
        
        // Calculate rotation to face outward from arch
        const rotation = -((angle * 180 / Math.PI) - 90);
        
        positions.push({
            number: i + 1,
            x: x,
            y: y,
            type: getToothType(i + 1),
            quadrant: i < 8 ? 'upper_right' : 'upper_left',
            rotation: rotation
        });
    }
    
    // Lower Arch - Teeth 17-32 (left to right, only lower semicircle)
    for (let i = 0; i < 16; i++) {
        const t = i / 15; // 0 to 1
        const angle = t * Math.PI; // 0 to π (left to right)
        
        const x = centerX + radiusX * Math.cos(angle);
        const y = lowerArchY + Math.abs(radiusY * Math.sin(angle));
        
        // Calculate rotation to face outward from arch
        const rotation = (angle * 180 / Math.PI) - 90;
        
        positions.push({
            number: i + 17,
            x: x,
            y: y,
            type: getToothType(i + 17),
            quadrant: i < 8 ? 'lower_left' : 'lower_right',
            rotation: rotation
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
        'incisor': 'M -8,-20 Q -10,-25 -6,-28 Q 0,-30 6,-28 Q 10,-25 8,-20 L 6,18 Q 4,22 0,24 Q -4,22 -6,18 Z',
        'canine': 'M -6,-22 Q -8,-28 -4,-32 Q 0,-35 4,-32 Q 8,-28 6,-22 L 5,20 Q 3,25 0,28 Q -3,25 -5,20 Z',
        'premolar': 'M -12,-18 Q -14,-22 -10,-25 Q -5,-27 0,-27 Q 5,-27 10,-25 Q 14,-22 12,-18 L 10,15 Q 8,20 4,22 Q 0,23 -4,22 Q -8,20 -10,15 Z',
        'molar': 'M -15,-16 Q -18,-20 -14,-24 Q -8,-27 0,-28 Q 8,-27 14,-24 Q 18,-20 15,-16 L 13,18 Q 10,23 5,26 Q 0,28 -5,26 Q -10,23 -13,18 Z'
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
    group.setAttribute('transform', `translate(${position.x},${position.y}) rotate(${position.rotation})`);
    group.style.cursor = 'pointer';
    
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', getToothPath(position.type));
    path.setAttribute('class', 'tooth-shape');
    path.setAttribute('fill', conditionColors[condition]);
    path.setAttribute('stroke', '#ffffff');
    path.setAttribute('stroke-width', '2');
    group.appendChild(path);
    
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0);
    text.setAttribute('y', 5);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '10');
    text.setAttribute('font-weight', 'bold');
    text.setAttribute('fill', '#fff');
    text.setAttribute('class', 'tooth-label');
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
    if (!selectedUserId) {
        alert('Please select a patient first.');
        return;
    }
    if (!confirm('Initialize default 32-tooth layout for this patient?')) return;
    
    fetch(`/admin/tooth-records/initialize/${selectedUserId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
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

function getQuadrantValue(number) {
    if (number <= 8) return 'upper_right';
    if (number <= 16) return 'upper_left';
    if (number <= 24) return 'lower_left';
    return 'lower_right';
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
    document.getElementById('tooth-detail-modal').classList.remove('show');
    document.getElementById('note-content').value = '';
    document.getElementById('note-type-select').value = 'treatment';
}

function saveToothChanges() {
    const condition = document.getElementById('condition-select').value;
    const noteContent = document.getElementById('note-content').value.trim();
    const noteType = document.getElementById('note-type-select').value;
    
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
    
    fetch('/admin/tooth-records/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(result => {
        alert(result.message || 'Changes saved successfully!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        alert('Error saving changes: ' + error.message);
        console.error(error);
    });
}

function markToothAsMissing() {
    if (!currentToothId) {
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
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(result => {
        alert(result.message || 'Tooth marked as missing!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        alert('Error marking tooth as missing: ' + error.message);
        console.error(error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tooth-detail-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeToothModal();
        });
    }
});
</script>
