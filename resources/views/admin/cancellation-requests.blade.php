@extends('layouts.admin')

@section('title', 'Cancellation & Reschedule Requests')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .page-container {
        padding: 24px;
    }
    
    .page-header {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .page-header p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    .nav-tabs {
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 24px;
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 500;
        padding: 12px 24px;
        margin-bottom: -2px;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: #1f2937;
        border-color: #d1d5db;
    }
    
    .nav-tabs .nav-link.active {
        color: #3b82f6;
        border-color: #3b82f6;
        background: transparent;
    }
    
    .tab-badge {
        display: inline-block;
        background: #f3f4f6;
        color: #6b7280;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
    
    .nav-tabs .nav-link.active .tab-badge {
        background: #dbeafe;
        color: #3b82f6;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid #e5e7eb;
    }
    
    .stat-card.pending { border-left-color: #ef4444; }
    .stat-card.approved { border-left-color: #10b981; }
    .stat-card.weekly { border-left-color: #6b7280; }
    .stat-card.late { border-left-color: #f59e0b; }
    
    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .filters-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .content-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #1f2937;
    }
    
    .table tbody tr:hover {
        background: #f9fafb;
    }
    
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .badge.bg-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .badge.bg-warning {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge.bg-success {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge.bg-info {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge.bg-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .pagination-wrapper {
        padding: 20px;
        border-top: 1px solid #e5e7eb;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 0;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 15px 20px;
        background-color: #f9fafb;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 8px 8px;
    }
    
    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
    }
    
    .detail-item {
        margin-bottom: 15px;
    }
    
    .detail-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
        font-size: 13px;
    }
    
    .detail-value {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
    }
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>Cancellation & Reschedule Requests</h2>
        <p>View and manage appointment cancellations and reschedule requests</p>
    </div>
    
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $type === 'cancel' ? 'active' : '' }}" 
               href="{{ route('admin.cancellation.requests', ['type' => 'cancel']) }}">
                <i class="fas fa-ban"></i> Cancellations
                <span class="tab-badge">{{ $cancelPending }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $type === 'reschedule' ? 'active' : '' }}" 
               href="{{ route('admin.cancellation.requests', ['type' => 'reschedule']) }}">
                <i class="fas fa-calendar-alt"></i> Reschedules
                <span class="tab-badge">{{ $reschedulePending }}</span>
            </a>
        </li>
    </ul>
    
    <!-- Statistics Grid -->
    <div class="stats-grid">
        @if($type === 'cancel')
            <div class="stat-card pending">
                <div class="stat-label">Total Cancelled</div>
                <div class="stat-value">{{ $cancelPending }}</div>
            </div>
            <div class="stat-card approved">
                <div class="stat-label">Today</div>
                <div class="stat-value">{{ $cancelToday }}</div>
            </div>
            <div class="stat-card weekly">
                <div class="stat-label">This Week</div>
                <div class="stat-value">{{ $cancelWeekly }}</div>
            </div>
            <div class="stat-card late">
                <div class="stat-label">Late Cancellations</div>
                <div class="stat-value">{{ $cancelLate }}</div>
            </div>
        @else
            <div class="stat-card pending">
                <div class="stat-label">Total Rescheduled</div>
                <div class="stat-value">{{ $reschedulePending }}</div>
            </div>
            <div class="stat-card approved">
                <div class="stat-label">Today</div>
                <div class="stat-value">{{ $rescheduleToday }}</div>
            </div>
            <div class="stat-card weekly">
                <div class="stat-label">This Week</div>
                <div class="stat-value">{{ $rescheduleWeekly }}</div>
            </div>
            <div class="stat-card late">
                <div class="stat-label">Late Requests</div>
                <div class="stat-value">{{ $rescheduleLate }}</div>
            </div>
        @endif
    </div>
    
    <!-- Filters Card -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.cancellation.requests') }}">
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="filters-grid">
                <!-- Search -->
                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           class="form-control" 
                           placeholder="Patient name, email, procedure..." 
                           value="{{ request('search') }}">
                </div>
                
                <!-- Date From -->
                <div class="form-group">
                    <label for="date_from">From Date</label>
                    <input type="text" 
                           id="date_from" 
                           name="date_from" 
                           class="form-control datepicker" 
                           placeholder="Select start date" 
                           value="{{ request('date_from') }}">
                </div>
                
                <!-- Date To -->
                <div class="form-group">
                    <label for="date_to">To Date</label>
                    <input type="text" 
                           id="date_to" 
                           name="date_to" 
                           class="form-control datepicker" 
                           placeholder="Select end date" 
                           value="{{ request('date_to') }}">
                </div>
                
                <!-- Status Filter -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="margin-right: 8px;">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.cancellation.requests', ['type' => $type]) }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Table Card -->
    <div class="content-card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Appointment Date</th>
                        <th>Procedure</th>
                        <th>Request Date</th>
                        <th>Notice Period</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cancellations as $cancellation)
                    <tr>
                        <td>#{{ $cancellation->id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $cancellation->user->name ?? 'N/A' }}</div>
                            <div style="font-size: 12px; color: #6b7280;">{{ $cancellation->user->email ?? '' }}</div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($cancellation->appointment->start)->format('M d, Y') }}<br>
                            <small style="color: #6b7280;">{{ \Carbon\Carbon::parse($cancellation->appointment->start)->format('g:i A') }}</small>
                        </td>
                        <td>{{ $cancellation->appointment->procedure ?? 'N/A' }}</td>
                        <td>{{ $cancellation->processed_at->format('M d, Y') }}<br>
                            <small style="color: #6b7280;">{{ $cancellation->processed_at->format('g:i A') }}</small>
                        </td>
                        <td>
                            @php
                                $appointmentTime = \Carbon\Carbon::parse($cancellation->appointment->start);
                                $daysUntil = (int)$cancellation->processed_at->diffInDays($appointmentTime, false);
                                $hoursNotice = (int)$cancellation->processed_at->diffInHours($appointmentTime, false);
                            @endphp
                            @if($daysUntil >= 0)
                                <div style="font-weight: 500;">{{ $daysUntil }} days</div>
                                <small style="color: #6b7280;">({{ $hoursNotice }} hours)</small>
                                @if($hoursNotice < 48)
                                    <span class="badge bg-warning">Late</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Past Due</span>
                            @endif
                        </td>
                        <td>
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                 title="{{ $cancellation->reason }}">
                                {{ Str::limit($cancellation->reason, 50) }}
                            </div>
                        </td>
                        <td>
                            @if($cancellation->appointment->status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @elseif($cancellation->appointment->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($cancellation->appointment->status === 'accepted')
                                <span class="badge bg-info">Accepted</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($cancellation->appointment->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" 
                                    onclick='viewDetails(@json($cancellation))'
                                    title="View Details">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p style="margin: 8px 0 0 0; font-weight: 500;">No {{ $type === 'cancel' ? 'cancellation' : 'reschedule' }} requests found</p>
                                <p style="font-size: 13px; color: #9ca3af;">{{ $type === 'cancel' ? 'Cancelled' : 'Rescheduled' }} appointments will appear here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($cancellations->hasPages())
        <div class="pagination-wrapper">
            {{ $cancellations->appends(request()->query())->links('vendor.pagination.compact-bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <i class="fas fa-info-circle"></i> 
                <span id="modalTitle">Request Details</span>
            </h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">Patient Name:</span>
                <p class="detail-value" id="modalPatientName"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span>
                <p class="detail-value" id="modalEmail"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Procedure:</span>
                <p class="detail-value" id="modalProcedure"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Appointment Date & Time:</span>
                <p class="detail-value" id="modalDateTime"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Request Submitted:</span>
                <p class="detail-value" id="modalRequestDate"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Notice Period:</span>
                <p class="detail-value" id="modalNotice"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Type:</span>
                <p class="detail-value" id="modalType"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <p class="detail-value" id="modalStatus"></p>
            </div>
            <div class="detail-item">
                <span class="detail-label">Reason:</span>
                <p class="detail-value" id="modalReason" style="white-space: pre-wrap;"></p>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal()" class="btn btn-primary">Close</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Initialize datepickers
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('.datepicker', {
        dateFormat: 'Y-m-d',
        allowInput: true,
        clickOpens: true
    });
});

function viewDetails(cancellation) {
    const appointment = cancellation.appointment;
    const user = cancellation.user;
    
    // Calculate notice period
    const appointmentTime = new Date(appointment.start);
    const requestTime = new Date(cancellation.processed_at);
    const diffTime = Math.abs(appointmentTime - requestTime);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
    
    document.getElementById('modalPatientName').textContent = user.name || 'N/A';
    document.getElementById('modalEmail').textContent = user.email || 'N/A';
    document.getElementById('modalProcedure').textContent = appointment.procedure || 'N/A';
    document.getElementById('modalDateTime').textContent = new Date(appointment.start).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('modalRequestDate').textContent = new Date(cancellation.processed_at).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('modalNotice').innerHTML = diffDays >= 0 
        ? `<strong>${diffDays} days</strong> (${diffHours} hours) ${diffHours < 48 ? '<span class="badge bg-warning">Late Notice</span>' : ''}`
        : '<span class="badge bg-danger">Past Due</span>';
    document.getElementById('modalReason').textContent = cancellation.reason;
    
    // Type badge
    const typeElement = document.getElementById('modalType');
    if (cancellation.type === 'reschedule') {
        typeElement.innerHTML = '<span class="badge bg-info">Reschedule Request</span>';
    } else {
        typeElement.innerHTML = '<span class="badge bg-danger">Cancellation</span>';
    }
    
    // Status badge
    const statusElement = document.getElementById('modalStatus');
    const status = appointment.status;
    let statusBadge = '';
    
    if (status === 'cancelled') {
        statusBadge = '<span class="badge bg-danger">Cancelled</span>';
    } else if (status === 'completed') {
        statusBadge = '<span class="badge bg-success">Completed</span>';
    } else if (status === 'accepted') {
        statusBadge = '<span class="badge bg-info">Accepted</span>';
    } else {
        statusBadge = `<span class="badge bg-secondary">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
    }
    statusElement.innerHTML = statusBadge;
    
    document.getElementById('detailsModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
