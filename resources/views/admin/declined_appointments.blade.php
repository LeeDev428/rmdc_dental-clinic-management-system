@extends('layouts.admin')

@section('title', 'Declined Appointments')

@section('content')
<style>
    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 100%;
        table-layout: auto;
    }

    .table th {
        background-color: #dc3545;
        color: white;
        text-align: center;
        padding: 12px;
        white-space: nowrap;
    }

    .table td {
        background-color: #f9f9f9;
        color: #333;
        padding: 12px;
        vertical-align: middle;
        word-wrap: break-word;
        max-width: 200px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .container {
        background-color: #f0f8ff;
        padding: 30px;
        border-radius: 10px;
    }

    h1 {
        color: #dc3545;
        font-weight: bold;
    }

    .btn {
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 5px;
    }
    
    .filter-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .filter-buttons .btn.active {
        background-color: #bd2130;
        color: white;
        border-color: #bd2130;
    }

    .reason-cell {
        cursor: pointer;
        color: #0d6efd;
        text-decoration: underline;
    }

    .reason-cell:hover {
        color: #0a58ca;
    }

    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 14px;
        }
    }
</style>

<div class="container mb-5">
    <h1 class="mb-4">Declined Appointments</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Date Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.declined_appointments') }}" id="declinedSearchForm">
                <input type="text" name="search" class="form-control" placeholder="Search by patient name, title, or procedure" value="{{ request('search') }}">
                <input type="hidden" name="date" id="hiddenDeclinedDate" value="{{ request('date') }}">
                <input type="hidden" name="filter" value="{{ request('filter') }}">
            </form>
        </div>
        <div class="col-md-6">
            <input type="date" id="declinedDateFilter" class="form-control" value="{{ request('date') }}" placeholder="Filter by date">
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-buttons mb-3">
        <a href="{{ route('admin.declined_appointments', ['filter' => 'today']) }}" 
           class="btn btn-outline-danger {{ request('filter') == 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('admin.declined_appointments', ['filter' => 'week']) }}" 
           class="btn btn-outline-danger {{ request('filter') == 'week' ? 'active' : '' }}">
            This Week
        </a>
        <a href="{{ route('admin.declined_appointments', ['filter' => 'month']) }}" 
           class="btn btn-outline-danger {{ request('filter') == 'month' ? 'active' : '' }}">
            This Month
        </a>
        <a href="{{ route('admin.declined_appointments') }}" 
           class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">
            All
        </a>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex mb-3">
        <a href="{{ route('admin.patient_information') }}" class="btn btn-info me-2">Patient Information</a>
        <a href="{{ route('admin.upcoming_appointments') }}" class="btn btn-warning me-2">Pending Appointments</a>
        <a href="{{ route('admin.appointments') }}" class="btn btn-primary">Upcoming Appointments</a>
    </div>

    <!-- Appointments Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
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
                        <a href="{{ route('admin.patient_information') }}?user_id={{ $appointment->user_id }}" class="btn btn-sm btn-info">
                            View Patient
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No declined appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
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
