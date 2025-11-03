@extends('layouts.admin')

@section('title', 'Declined Appointments')

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
    
    .search-input-group {
        flex: 1;
        min-width: 250px;
    }
    
    .search-input {
        width: 100%;
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
        background-color: #ef4444;
        color: #fff;
        border-color: #ef4444;
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
    
    .reason-cell {
        cursor: pointer;
        color: #0084ff;
        text-decoration: underline;
    }
    
    .reason-cell:hover {
        color: #0073e6;
    }
    
    .btn-view-patient {
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
        display: inline-block;
    }
    
    .btn-view-patient:hover {
        background-color: #0073e6;
        color: #fff;
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
    
    .modal-body p {
        font-size: 14px;
        color: #4a4a4a;
        line-height: 1.6;
        margin: 0;
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
        background-color: #f0f0f0;
        color: #1a1a1a;
    }
    
    .modal-footer .btn:hover {
        background-color: #e0e0e0;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 24px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Declined Appointments</h1>
</div>

<div class="content-card">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Date Filter -->
    <div class="search-section">
        <form method="GET" action="{{ route('admin.declined_appointments') }}" id="declinedSearchForm" class="search-input-group">
            <input type="text" name="search" class="search-input" placeholder="Search by patient name, title, or procedure" value="{{ request('search') }}">
            <input type="hidden" name="date" id="hiddenDeclinedDate" value="{{ request('date') }}">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
        </form>
        <input type="date" id="declinedDateFilter" class="date-input" value="{{ request('date') }}" placeholder="Filter by date">
    </div>

    <!-- Filter Buttons -->
        
    <!-- Filter Buttons -->
    <div class="filter-buttons">
        <a href="{{ route('admin.declined_appointments', ['filter' => 'today']) }}" 
           class="filter-btn {{ request('filter') == 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('admin.declined_appointments', ['filter' => 'week']) }}" 
           class="filter-btn {{ request('filter') == 'week' ? 'active' : '' }}">
            This Week
        </a>
        <a href="{{ route('admin.declined_appointments', ['filter' => 'month']) }}" 
           class="filter-btn {{ request('filter') == 'month' ? 'active' : '' }}">
            This Month
        </a>
        <a href="{{ route('admin.declined_appointments') }}" 
           class="filter-btn {{ !request('filter') ? 'active' : '' }}">
            All
        </a>
    </div>

    <!-- Navigation Buttons -->
    <div class="action-buttons" style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
        <a href="{{ route('admin.patient_information') }}" class="btn-nav info" style="padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; background-color: #0ea5e9; color: #fff;">Patient Information</a>
        <a href="{{ route('admin.upcoming_appointments') }}" class="btn-nav" style="padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; background-color: #f59e0b; color: #fff;">Pending Appointments</a>
        <a href="{{ route('admin.appointments') }}" class="btn-nav primary" style="padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; background-color: #0084ff; color: #fff;">Upcoming Appointments</a>
    </div>

    <!-- Appointments Table -->
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Patient Name</th>
                    <th>Title</th>
                    <th>Procedure</th>
                    <th>Reason</th>
                    <th>Declined At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($declinedAppointments as $index => $appointment)
                <tr>
                    <td>{{ ($declinedAppointments->currentPage() - 1) * $declinedAppointments->perPage() + $index + 1 }}</td>
                    <td>{{ $appointment->patient_name }}</td>
                    <td>{{ $appointment->title }}</td>
                    <td>{{ $appointment->procedure }}</td>
                    <td>
                        <span class="reason-cell" onclick="showReasonModal('{{ addslashes($appointment->decline_reason) }}')">
                            {{ Str::limit($appointment->decline_reason, 50, '...') }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($appointment->updated_at)->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.patient_information') }}?user_id={{ $appointment->user_id }}" class="btn-view-patient">
                            View Patient
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af;">No declined appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
            {{ $declinedAppointments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Reason Modal -->
<div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="reasonModalLabel">Decline Reason</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reasonModalBody">
                <!-- Full reason will be dynamically inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Date filter functionality
    document.getElementById('declinedDateFilter').addEventListener('change', function() {
        const selectedDate = this.value;
        document.getElementById('hiddenDeclinedDate').value = selectedDate;
        document.getElementById('declinedSearchForm').submit();
    });

    // Search input submit on enter
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('declinedSearchForm').submit();
        }
    });

    function showReasonModal(reason) {
        const modalBody = document.getElementById('reasonModalBody');
        modalBody.textContent = reason;
        const reasonModal = new bootstrap.Modal(document.getElementById('reasonModal'));
        reasonModal.show();
    }
</script>
@endsection
