@extends('layouts.admin')

@section('title', 'Teeth Layout Management')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Teeth Layout Management</h2>

    <!-- User Search -->
    <div class="mb-4">
        <label for="user-search" class="form-label">Search User:</label>
        <input type="text" id="user-search" class="form-control" placeholder="Search user by name or ID" oninput="filterUsers()">
        <ul id="user-list" class="list-group mt-2" style="max-height: 150px; overflow-y: auto;">
            @foreach($users as $user)
                <li class="list-group-item" onclick="selectUser({{ $user->id }}, '{{ $user->name }}')">
                    {{ $user->name }} (ID: {{ $user->id }})
                </li>
            @endforeach
        </ul>
    </div>

    <div id="teeth-layout-container" class="d-none">
        <h4 class="mb-3">Teeth Layout</h4>
        <form id="teeth-layout-form" onsubmit="saveTeethLayout(event)">
            <div class="svg-dental-chart">
                <svg id="teeth-chart" viewBox="0 0 400 700" xmlns="http://www.w3.org/2000/svg">
                    <!-- Quadrant lines only -->
                    <line x1="200" y1="60" x2="200" y2="640" stroke="#a084ca" stroke-width="3" stroke-dasharray="8,6"/>
                    <line x1="40" y1="350" x2="360" y2="350" stroke="#a084ca" stroke-width="3" stroke-dasharray="8,6"/>
                </svg>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
        </form>
        <button class="btn btn-success mt-3" onclick="showInitializeConfirmation()">Initialize Default Layout</button>
        <button class="btn btn-secondary mt-3" onclick="addNewTooth()">Add Tooth</button>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Initialization</h5>
                <button type="button" class="close" onclick="closeConfirmationModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to initialize the default teeth layout for this user? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmationModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="initializeTeethLayout()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedUserId = null;

function filterUsers() {
    const searchValue = document.getElementById('user-search').value.toLowerCase();
    const userList = document.getElementById('user-list');
    const users = userList.getElementsByTagName('li');

    for (let user of users) {
        const text = user.textContent.toLowerCase();
        user.style.display = text.includes(searchValue) ? '' : 'none';
    }

    // Ensure the user list is visible after each search
    userList.style.display = searchValue ? 'block' : 'none';
}

function selectUser(userId, userName) {
    selectedUserId = userId;
    document.getElementById('user-search').value = userName;
    document.getElementById('user-list').style.display = 'none';
    loadTeethLayout(userId);
}

function showInitializeConfirmation() {
    if (!selectedUserId) {
        alert('Please select a user first.');
        return;
    }
    document.getElementById('confirmation-modal').style.display = 'block';
}

function closeConfirmationModal() {
    document.getElementById('confirmation-modal').style.display = 'none';
}

function initializeTeethLayout() {
    if (!selectedUserId) {
        alert('Please select a user first.');
        return;
    }

    fetch(`/admin/teeth-layout/initialize/${selectedUserId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        closeConfirmationModal();
        loadTeethLayout(selectedUserId); // Reload layout after initialization
    });
}

function loadTeethLayout(userId) {
    if (!userId) {
        document.getElementById('teeth-layout-container').classList.add('d-none');
        return;
    }
    document.getElementById('teeth-layout-container').classList.remove('d-none');
    fetch(`/admin/teeth-layout/${userId}`)
        .then(response => response.json())
        .then(data => {
            const chart = document.getElementById('teeth-chart');
            // Remove all previous teeth
            [...chart.querySelectorAll('.tooth-group')].forEach(e => e.remove());
            // Check if user has any teeth layout
            if (!data.teeth || data.teeth.length === 0) {
                // Display "No Layout for this user" message
                let msg = document.getElementById('no-layout-msg');
                if (!msg) {
                    msg = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    msg.setAttribute('id', 'no-layout-msg');
                    msg.setAttribute('x', 200);
                    msg.setAttribute('y', 350);
                    msg.setAttribute('text-anchor', 'middle');
                    msg.setAttribute('font-size', '22');
                    msg.setAttribute('fill', '#a084ca');
                    chart.appendChild(msg);
                }
                msg.textContent = 'No Layout for this user';
                return;
            } else {
                // Remove any previous message
                const msg = document.getElementById('no-layout-msg');
                if (msg) msg.remove();
            }

            // Filter out removed teeth
            const activeTeeth = data.teeth.filter(tooth => !tooth.removed);

            // Teeth positions (upper and lower arcs)
            const upperArc = [];
            const lowerArc = [];
            const rX = 120, rY = 220, cx = 200, cy = 280, cy2 = 420;

            // Upper teeth: 1-16 (right to left)
            for (let i = 0; i < 16; i++) {
                const angle = Math.PI * (1 - i / 15); // right to left
                upperArc.push({
                    x: cx + rX * Math.cos(angle),
                    y: cy - rY * Math.sin(angle),
                    number: i + 1, // Teeth numbered 1-16
                    idx: i + 1
                });
            }

            // Lower teeth: 17-32 (left to right)
            for (let i = 0; i < 16; i++) {
                const angle = Math.PI * (i / 15); // left to right
                lowerArc.push({
                    x: cx + rX * Math.cos(angle),
                    y: cy2 + rY * Math.sin(angle),
                    number: i + 17, // Teeth numbered 17-32
                    idx: i + 17
                });
            }

            // Draw upper teeth
            upperArc.forEach((pos) => {
                drawTooth(chart, pos.x, pos.y, pos.idx, pos.number, activeTeeth, userId);
            });

            // Draw lower teeth
            lowerArc.forEach((pos) => {
                drawTooth(chart, pos.x, pos.y, pos.idx, pos.number, activeTeeth, userId);
            });
        });
}

function drawTooth(chart, x, y, idx, label, teethData, userId) {
    const tooth = teethData.find(t => t.number == idx);
    if (!tooth) return; // Skip if the tooth is not found

    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    group.setAttribute('class', 'tooth-group');
    group.setAttribute('transform', `translate(${x},${y})`);
    group.setAttribute('draggable', 'true');

    // Drag and drop functionality
    group.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', JSON.stringify({ id: tooth.id, number: idx }));
    });
    group.addEventListener('dragend', (e) => {
        const rect = chart.getBoundingClientRect();
        const newX = e.clientX - rect.left;
        const newY = e.clientY - rect.top;
        group.setAttribute('transform', `translate(${newX},${newY})`);
        updateToothPosition(tooth.id, newX, newY);
    });

    // Tooth shape (SVG path)
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', getToothPath('incisor')); // Default shape
    path.setAttribute('fill', '#fff');
    path.setAttribute('stroke', '#333');
    path.setAttribute('stroke-width', '2');
    group.appendChild(path);

    // Tooth number (label)
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0);
    text.setAttribute('y', 6);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '16');
    text.setAttribute('fill', '#333');
    text.textContent = label;
    group.appendChild(text);

    // If the tooth is marked as removed, add a big "X"
    if (tooth.removed) {
        const cross = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        cross.setAttribute('x', 0);
        cross.setAttribute('y', 10);
        cross.setAttribute('text-anchor', 'middle');
        cross.setAttribute('font-size', '30');
        cross.setAttribute('fill', 'red');
        cross.textContent = 'X';
        group.appendChild(cross);
    }

    // Remove button
    const removeBtn = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    removeBtn.setAttribute('x', 0);
    removeBtn.setAttribute('y', 24);
    removeBtn.setAttribute('text-anchor', 'middle');
    removeBtn.setAttribute('font-size', '18');
    removeBtn.setAttribute('fill', 'red');
    removeBtn.setAttribute('cursor', 'pointer');
    removeBtn.textContent = '×';
    removeBtn.addEventListener('click', () => markToothAsRemoved(tooth.id, userId));
    group.appendChild(removeBtn);

    chart.appendChild(group);
}

function addNewTooth() {
    const chart = document.getElementById('teeth-chart');
    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    group.setAttribute('class', 'tooth-group');
    group.setAttribute('transform', 'translate(200, 350)');
    group.setAttribute('draggable', 'true');

    // Drag and drop functionality
    group.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', 'new');
    });
    group.addEventListener('dragend', (e) => {
        const rect = chart.getBoundingClientRect();
        const newX = e.clientX - rect.left;
        const newY = e.clientY - rect.top;
        group.setAttribute('transform', `translate(${newX},${newY})`);
        saveNewToothPosition(newX, newY);
    });

    // Tooth shape (SVG path)
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', getToothPath('incisor'));
    path.setAttribute('fill', '#f0f0f0');
    path.setAttribute('stroke', '#333');
    path.setAttribute('stroke-width', '2');
    group.appendChild(path);

    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', 0);
    text.setAttribute('y', 6);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('font-size', '16');
    text.setAttribute('fill', '#333');
    text.textContent = 'New';
    group.appendChild(text);

    chart.appendChild(group);
}

function saveNewToothPosition(x, y) {
    if (!selectedUserId) {
        alert('Please select a user first.');
        return;
    }

    fetch(`/admin/teeth-layout/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id: selectedUserId,
            x: x,
            y: y,
            number: 'new' // Assign a placeholder number for the new tooth
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadTeethLayout(selectedUserId); // Reload layout to reflect the new tooth
    });
}

function markToothAsRemoved(toothId, userId) {
    if (!toothId) return;
    if (!confirm('Are you sure you want to mark this tooth as removed?')) return;

    fetch(`/admin/teeth-layout/remove/${toothId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadTeethLayout(userId); // Reload layout after marking as removed
    });
}

function updateToothPosition(toothId, x, y) {
    if (!toothId) return;

    fetch(`/admin/teeth-layout/update-position/${toothId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ x, y })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    });
}

function getToothPath(type) {
    switch (type) {
        case 'molar':
            return 'M -15,-10 Q 0,-25 15,-10 Q 20,10 0,25 Q -20,10 -15,-10 Z';
        case 'premolar':
            return 'M -10,-10 Q 0,-20 10,-10 Q 13,10 0,18 Q -13,10 -10,-10 Z';
        case 'canine':
            return 'M -7,-10 Q 0,-22 7,-10 Q 8,10 0,20 Q -8,10 -7,-10 Z';
        case 'incisor':
        default:
            return 'M -8,-10 Q 0,-15 8,-10 Q 8,10 0,15 Q -8,10 -8,-10 Z';
    }
}
</script>

<style>
.svg-dental-chart {
    width: 400px;
    height: 700px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    position: relative;
}
.tooth-group path {
    transition: fill 0.3s;
}
.tooth-group:hover path {
    fill: #e0e0e0;
}
.tooth-group text {
    pointer-events: none;
}
.tooth-group text[font-size="18"] {
    pointer-events: all;
}

/* Modal Styles */
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

.modal-dialog {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    width: 90%;
    max-width: 500px;
}

.modal-header, .modal-footer {
    padding: 10px;
    background: #f1f1f1;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-body {
    padding: 20px;
}
</style>
@endsection
