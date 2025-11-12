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
    const positions = [], radiusX = 250, radiusY = 200, centerX = 400, centerY = 300;
    for (let i = 0; i < 16; i++) {
        const angle = Math.PI * (1 - i / 15);
        positions.push({ number: i + 1, x: centerX + radiusX * Math.cos(angle), y: centerY - Math.abs(radiusY * Math.sin(angle)) - 80, type: getToothType(i + 1), quadrant: i < 8 ? 'upper_right' : 'upper_left' });
    }
    for (let i = 0; i < 16; i++) {
        const angle = Math.PI * (i / 15);
        positions.push({ number: i + 17, x: centerX + radiusX * Math.cos(angle), y: centerY + Math.abs(radiusY * Math.sin(angle)) + 80, type: getToothType(i + 17), quadrant: i < 8 ? 'lower_left' : 'lower_right' });
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
        'incisor': 'M -10,-15 Q 0,-20 10,-15 L 8,20 Q 0,25 -8,20 Z',
        'canine': 'M -8,-18 Q 0,-25 8,-18 L 6,22 Q 0,28 -6,22 Z',
        'premolar': 'M -12,-12 Q -15,-18 -8,-20 Q 0,-22 8,-20 Q 15,-18 12,-12 L 10,15 Q 0,20 -10,15 Z',
        'molar': 'M -18,-15 Q -20,-20 -10,-22 Q 0,-24 10,-22 Q 20,-20 18,-15 L 15,18 Q 0,25 -15,18 Z'
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
    group.style.cursor = 'pointer';
    
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', getToothPath(position.type));
    path.setAttribute('class', 'tooth-shape');
    path.setAttribute('fill', conditionColors[condition]);
    path.setAttribute('stroke', '#1f2937');
    path.setAttribute('stroke-width', '1.5');
    group.appendChild(path);
    
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0); text.setAttribute('y', 5); text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '11'); text.setAttribute('font-weight', 'bold'); text.setAttribute('fill', '#fff');
    text.setAttribute('class', 'tooth-label'); text.textContent = position.number;
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
    document.getElementById('tooth-detail-modal').classList.remove('show');
    document.getElementById('note-content').value = '';
    document.getElementById('note-type-select').value = 'treatment';
}

function saveToothChanges() {
    const condition = document.getElementById('condition-select').value;
    const noteContent = document.getElementById('note-content').value.trim();
    const noteType = document.getElementById('note-type-select').value;
    if (!selectedUserId || !currentToothNumber) return alert('Please select a patient and tooth first');
    
    const data = {
        user_id: selectedUserId, tooth_number: currentToothNumber, condition: condition,
        quadrant: getQuadrantValue(currentToothNumber), tooth_type: getToothType(currentToothNumber),
        color_code: conditionColors[condition], is_missing: condition === 'missing',
        note_content: noteContent, note_type: noteType
    };
    
    fetch('/admin/tooth-records/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(response => { if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); return response.json(); })
    .then(result => { alert(result.message || 'Changes saved successfully!'); closeToothModal(); loadTeethLayout(selectedUserId); })
    .catch(error => { alert('Error saving changes: ' + error.message); console.error(error); });
}

function markToothAsMissing() {
    if (!currentToothId) { document.getElementById('condition-select').value = 'missing'; return saveToothChanges(); }
    if (!confirm('Are you sure you want to mark this tooth as missing?')) return;
    fetch(`/admin/tooth-records/${currentToothId}/mark-missing`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => { if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); return response.json(); })
    .then(result => { alert(result.message || 'Tooth marked as missing!'); closeToothModal(); loadTeethLayout(selectedUserId); })
    .catch(error => { alert('Error marking tooth as missing: ' + error.message); console.error(error); });
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tooth-detail-modal');
    if (modal) modal.addEventListener('click', function(e) { if (e.target === modal) closeToothModal(); });
});
</script>
