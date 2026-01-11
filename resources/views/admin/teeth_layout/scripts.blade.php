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
    'filling': '#3b82f6',
    'root_canal': '#ec4899',
    'missing': '#6b7280'
};

// Search and select user
function filterUsers() {
    const searchValue = document.getElementById('user-search').value.toLowerCase();
    const userList = document.getElementById('user-list');
    const users = userList.getElementsByTagName('li');
    for (let user of users) {
        user.style.display = user.textContent.toLowerCase().includes(searchValue) ? '' : 'none';
    }
    userList.style.display = searchValue ? 'block' : 'none';
}

function selectUser(userId, userName) {
    selectedUserId = userId;
    selectedUserName = userName;
    document.getElementById('user-search').value = userName;
    document.getElementById('user-list').style.display = 'none';
    document.getElementById('patient-name').textContent = userName;
    document.getElementById('patient-id').textContent = `ID: #${userId}`;
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
            renderSimpleChart();
            updateStatistics();
        })
        .catch(error => {
            console.error('Error:', error);
            teethRecords = [];
            renderSimpleChart();
        });
}

// Render simple chart with div-based teeth
function renderSimpleChart() {
    // Clear all quadrants
    ['upper-right', 'upper-left', 'lower-right', 'lower-left'].forEach(id => {
        document.getElementById(id).innerHTML = '';
    });
    
    // Upper Right: teeth 1-8 (displayed 8 to 1, right to left)
    const upperRight = document.getElementById('upper-right');
    for (let i = 8; i >= 1; i--) {
        upperRight.appendChild(createTooth(i));
    }
    
    // Upper Left: teeth 9-16 (displayed 9 to 16, left to right)
    const upperLeft = document.getElementById('upper-left');
    for (let i = 9; i <= 16; i++) {
        upperLeft.appendChild(createTooth(i));
    }
    
    // Lower Left: teeth 17-24 (displayed 17 to 24, left to right)
    const lowerLeft = document.getElementById('lower-left');
    for (let i = 17; i <= 24; i++) {
        lowerLeft.appendChild(createTooth(i));
    }
    
    // Lower Right: teeth 25-32 (displayed 32 to 25, right to left)
    const lowerRight = document.getElementById('lower-right');
    for (let i = 32; i >= 25; i--) {
        lowerRight.appendChild(createTooth(i));
    }
}

function createTooth(number) {
    const record = teethRecords.find(r => r.tooth_number === number);
    const condition = record?.condition || 'healthy';
    const isMissing = record?.is_missing || condition === 'missing';
    const hasNotes = record?.notes_count > 0;
    
    const tooth = document.createElement('div');
    tooth.className = 'tooth' + (isMissing ? ' missing' : '') + (hasNotes ? ' has-notes' : '');
    tooth.style.backgroundColor = conditionColors[condition] || conditionColors['healthy'];
    tooth.setAttribute('data-tooth', number);
    tooth.innerHTML = `<span class="tooth-num">${number}</span>`;
    tooth.onclick = () => showToothDetails(number, record);
    
    return tooth;
}

function updateStatistics() {
    const total = teethRecords.filter(r => !r.is_missing && r.condition !== 'missing').length;
    const healthy = teethRecords.filter(r => r.condition === 'healthy').length;
    const treatment = teethRecords.filter(r => ['cavity', 'treatment_needed', 'root_canal'].includes(r.condition)).length;
    
    document.getElementById('stat-total').textContent = total || 0;
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
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
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

// Modal functions
function showToothDetails(toothNumber, record) {
    currentToothId = record?.id;
    currentToothNumber = toothNumber;
    
    document.getElementById('modal-tooth-title').textContent = `Tooth #${toothNumber}`;
    document.getElementById('detail-number').textContent = toothNumber;
    document.getElementById('detail-quadrant').textContent = getQuadrantName(toothNumber);
    document.getElementById('detail-type').textContent = getToothTypeName(toothNumber);
    document.getElementById('detail-condition').textContent = formatCondition(record?.condition || 'healthy');
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

function getToothType(number) {
    const position = ((number - 1) % 8) + 1;
    if (position <= 2) return 'incisor';
    if (position === 3) return 'canine';
    if (position <= 5) return 'premolar';
    return 'molar';
}

function getToothTypeName(number) {
    const types = { 'incisor': 'Incisor', 'canine': 'Canine', 'premolar': 'Premolar', 'molar': 'Molar' };
    return types[getToothType(number)];
}

function formatCondition(condition) {
    return condition.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
}

function loadToothNotes(toothRecordId) {
    const container = document.getElementById('notes-container');
    if (!toothRecordId) {
        container.innerHTML = '<p class="text-muted text-center">No notes yet</p>';
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
                container.innerHTML = '<p class="text-muted text-center">No notes yet</p>';
            }
        })
        .catch(() => {
            container.innerHTML = '<p class="text-muted text-center">No notes yet</p>';
        });
}

function closeToothModal() {
    document.getElementById('tooth-detail-modal').classList.remove('show');
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
        if (!response.ok) throw new Error('Failed to save');
        return response.json();
    })
    .then(result => {
        alert(result.message || 'Saved!');
        closeToothModal();
        loadTeethLayout(selectedUserId);
    })
    .catch(error => {
        alert('Error saving: ' + error.message);
    });
}

function markToothAsMissing() {
    document.getElementById('condition-select').value = 'missing';
    saveToothChanges();
}

// Keyboard shortcut
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('tooth-detail-modal');
        if (modal && modal.classList.contains('show')) {
            closeToothModal();
        }
    }
});
</script>
