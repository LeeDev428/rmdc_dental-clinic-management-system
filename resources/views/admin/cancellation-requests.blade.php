@extends('layouts.admin')

@section('title', 'Pending Appointments')

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
    }
    
    .search-section {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #0084ff;
    }
    
    .date-input {
        flex: 1;
        min-width: 200px;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .filter-buttons {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .filter-btn {
        padding: 8px 16px;
        border: 1px solid #e0e0e0;
        background-color: #fff;
        color: #1a1a1a;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .filter-btn:hover {
        background-color: #f5f5f5;
        color: #1a1a1a;
    }
    
    .filter-btn.active {
        background-color: #0084ff;
        color: #fff;
        border-color: #0084ff;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .btn-nav {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-nav.info {
        background-color: #0ea5e9;
        color: #fff;
    }
    
    .btn-nav.info:hover {
        background-color: #0284c7;
        color: #fff;
    }
    
    .btn-nav.primary {
        background-color: #0084ff;
        color: #fff;
    }
    
    .btn-nav.primary:hover {
        background-color: #0073e6;
        color: #fff;
    }
    
    .btn-nav.danger {
        background-color: #ef4444;
        color: #fff;
    }
    
    .btn-nav.danger:hover {
        background-color: #dc2626;
        color: #fff;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead th {
        background-color: #f8f9fa;
        color: #1a1a1a;
        font-weight: 600;
        font-size: 14px;
        text-align: left;
        padding: 12px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .data-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        color: #4a4a4a;
    }
    
    .data-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .id-image {
        width: 60px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid #e0e0e0;
    }
    
    .action-btn-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-accept {
        background-color: #10b981;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-accept:hover {
        background-color: #059669;
    }
    
    .btn-decline {
        background-color: #ef4444;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-decline:hover {
        background-color: #dc2626;
    }
    
    .alert-success {
        background-color: #f0fdf4;
        color: #16a34a;
        padding: 12px 16px;
        border-radius: 6px;
        border-left: 4px solid #16a34a;
        margin-bottom: 16px;
    }
    
    .modal-content {
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid #e0e0e0;
        padding: 16px 24px;
    }
    
    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-body label {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 8px;
        display: block;
    }
    
    .modal-body textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
    }
    
    .modal-body textarea:focus {
        outline: none;
        border-color: #0084ff;
    }
    
    .modal-footer {
        border-top: 1px solid #e0e0e0;
        padding: 16px 24px;
    }
    
    .modal-footer .btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
    }
    
    .modal-footer .btn-secondary {
        background-color: #f0f0f0;
        color: #1a1a1a;
    }
    
    .modal-footer .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    .modal-footer .btn-danger {
        background-color: #ef4444;
        color: #fff;
    }
    
    .modal-footer .btn-danger:hover {
        background-color: #dc2626;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 24px;
    }
</style>

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid">
    <!-- Page Header -->
    {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cancellation & Reschedule Requests</h1>
    </div> --}}
    
  
    <div class="page-header">
    <h1 class="page-title">Cancellation Requests</h1>
</div>
    <!-- Statistics Grid -->
    <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Cancellations</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelTotal }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelToday }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">This Week</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelWeekly }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Late Cancellations</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelLate }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    
    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.cancellation.requests') }}">
                <div class="row">
                    <!-- Search -->
                    <div class="col-md-3 mb-3">
                        <label for="search">Search</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               class="form-control form-control-sm" 
                               placeholder="Patient name, email..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <!-- Date From -->
                    <div class="col-md-2 mb-3">
                        <label for="date_from">From Date</label>
                        <input type="text" 
                               id="date_from" 
                               name="date_from" 
                               class="form-control form-control-sm datepicker" 
                               placeholder="Start date" 
                               value="{{ request('date_from') }}">
                    </div>
                    
                    <!-- Date To -->
                    <div class="col-md-2 mb-3">
                        <label for="date_to">To Date</label>
                        <input type="text" 
                               id="date_to" 
                               name="date_to" 
                               class="form-control form-control-sm datepicker" 
                               placeholder="End date" 
                               value="{{ request('date_to') }}">
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-2 mb-3">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control form-control-sm">
                            <option value="">All Statuses</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="col-md-3 mb-3">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.cancellation.requests') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Cancellation Requests</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
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
                                <div class="font-weight-bold">{{ $cancellation->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $cancellation->user->email ?? '' }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($cancellation->appointment->start)->format('M d, Y') }}<br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($cancellation->appointment->start)->format('g:i A') }}</small>
                            </td>
                            <td>{{ $cancellation->appointment->procedure ?? 'N/A' }}</td>
                            <td>
                                {{ $cancellation->processed_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $cancellation->processed_at->format('g:i A') }}</small>
                            </td>
                            <td>
                                @php
                                    $appointmentTime = \Carbon\Carbon::parse($cancellation->appointment->start);
                                    $daysUntil = (int)$cancellation->processed_at->diffInDays($appointmentTime, false);
                                    $hoursNotice = (int)$cancellation->processed_at->diffInHours($appointmentTime, false);
                                @endphp
                                @if($daysUntil >= 0)
                                    <div class="font-weight-bold">{{ $daysUntil }} days</div>
                                    <small class="text-muted">({{ $hoursNotice }} hours)</small>
                                    @if($hoursNotice < 48)
                                        <br><span class="badge bg-warning text-dark">Late</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger text-white">Past Due</span>
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
                                    <span class="badge bg-danger text-white">Cancelled</span>
                                @elseif($cancellation->appointment->status === 'completed')
                                    <span class="badge bg-success text-white">Completed</span>
                                @elseif($cancellation->appointment->status === 'accepted')
                                    <span class="badge bg-info text-white">Accepted</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ ucfirst($cancellation->appointment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" 
                                        onclick='viewDetails(@json($cancellation))'
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="font-weight-bold">No cancellation requests found</p>
                                <p class="text-muted">Cancelled appointments will appear here</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($cancellations->hasPages())
            <div class="mt-3">
                {{ $cancellations->appends(request()->query())->links('vendor.pagination.compact-bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">
                    <i class="fas fa-info-circle"></i> Request Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Patient Name:</strong>
                        <p id="modalPatientName" class="text-muted"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p id="modalEmail" class="text-muted"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Procedure:</strong>
                        <p id="modalProcedure" class="text-muted"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Appointment Date & Time:</strong>
                        <p id="modalDateTime" class="text-muted"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Request Submitted:</strong>
                        <p id="modalRequestDate" class="text-muted"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Notice Period:</strong>
                        <p id="modalNotice" class="text-muted"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Type:</strong>
                        <p id="modalType"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        <p id="modalStatus"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Reason:</strong>
                        <p id="modalReason" class="text-muted" style="white-space: pre-wrap;"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
        ? `<strong>${diffDays} days</strong> (${diffHours} hours) ${diffHours < 48 ? '<span class="badge bg-warning text-dark">Late Notice</span>' : ''}`
        : '<span class="badge bg-danger text-white">Past Due</span>';
    document.getElementById('modalReason').textContent = cancellation.reason;
    
    // Type badge
    const typeElement = document.getElementById('modalType');
    if (cancellation.type === 'reschedule') {
        typeElement.innerHTML = '<span class="badge bg-info text-white">Reschedule Request</span>';
    } else {
        typeElement.innerHTML = '<span class="badge bg-danger text-white">Cancellation</span>';
    }
    
    // Status badge
    const statusElement = document.getElementById('modalStatus');
    const status = appointment.status;
    let statusBadge = '';
    
    if (status === 'cancelled') {
        statusBadge = '<span class="badge bg-danger text-white">Cancelled</span>';
    } else if (status === 'completed') {
        statusBadge = '<span class="badge bg-success text-white">Completed</span>';
    } else if (status === 'accepted') {
        statusBadge = '<span class="badge bg-info text-white">Accepted</span>';
    } else {
        statusBadge = `<span class="badge bg-secondary text-white">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
    }
    statusElement.innerHTML = statusBadge;
    
    // Show modal using Bootstrap
    bootstrap.Modal.getOrCreateInstance(document.getElementById('detailsModal')).show();
}
</script>
@endsection


