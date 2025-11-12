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

// Close modal when clicking outside of it
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tooth-detail-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeToothModal();
            }
        });
    }
});
</script>
