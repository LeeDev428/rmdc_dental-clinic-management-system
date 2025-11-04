@extends('layouts.admin')

@section('title', 'Upcoming Appointments')

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
        display: flex;
        gap: 8px;
    }
    
    .search-input {
        flex: 1;
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
    
    .btn-primary {
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-primary:hover {
        background-color: #0073e6;
    }
    
    .btn-secondary {
        background-color: #f0f0f0;
        color: #1a1a1a;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-secondary:hover {
        background-color: #e0e0e0;
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
    
    .priority-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .priority-high {
        background-color: #fee;
        color: #c41e3a;
    }
    
    .priority-medium {
        background-color: #fff4e6;
        color: #d97706;
    }
    
    .priority-low {
        background-color: #f0fdf4;
        color: #16a34a;
    }
    
    .alert-success {
        background-color: #f0fdf4;
        color: #16a34a;
        padding: 12px 16px;
        border-radius: 6px;
        border-left: 4px solid #16a34a;
        margin-bottom: 16px;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 24px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Upcoming Appointments</h1>
</div>

<div class="content-card">

<div class="content-card">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Section -->
    <div class="search-section">
        <form method="GET" action="{{ route('admin.appointments') }}" id="appointmentSearchForm" class="search-input-group">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search by name, title, or procedure" 
                   value="{{ request('search') }}">
            <input type="hidden" name="date" id="hiddenAppointmentDate" value="{{ request('date') }}">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            <button class="btn-primary" type="submit">Search</button>
            @if(request('search') || request('date'))
                <a href="{{ route('admin.appointments') }}" class="btn-secondary">Clear</a>
            @endif
        </form>
        <input type="date" id="appointmentDateFilter" class="date-input" value="{{ request('date') }}" placeholder="Filter by date">
    </div>
    
    <!-- Filter Buttons -->
    <div class="filter-buttons">
        <a href="{{ route('admin.appointments', ['filter' => 'today']) }}" 
           class="filter-btn {{ request('filter') == 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('admin.appointments', ['filter' => 'week']) }}" 
           class="filter-btn {{ request('filter') == 'week' ? 'active' : '' }}">
            This Week
        </a>
        <a href="{{ route('admin.appointments', ['filter' => 'month']) }}" 
           class="filter-btn {{ request('filter') == 'month' ? 'active' : '' }}">
            This Month
        </a>
        <a href="{{ route('admin.appointments') }}" 
           class="filter-btn {{ !request('filter') ? 'active' : '' }}">
            All Upcoming
        </a>
    </div>

    <!-- Appointments Table -->
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Priority</th>
                    <th>Patient Name</th>
                    <th>Title</th>
                    <th>Procedure</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($acceptedAppointments as $index => $appointment)
                @php
                    $now = now();
                    $appointmentTime = \Carbon\Carbon::parse($appointment->start);
                    $hoursUntil = $now->diffInHours($appointmentTime, false);
                    
                    if ($hoursUntil <= 2) {
                        $priorityClass = 'priority-high';
                        $priorityLabel = 'URGENT';
                    } elseif ($hoursUntil <= 24) {
                        $priorityClass = 'priority-medium';
                        $priorityLabel = 'SOON';
                    } else {
                        $priorityClass = 'priority-low';
                        $priorityLabel = 'SCHEDULED';
                    }
                @endphp
                <tr>
                    <td>{{ ($acceptedAppointments->currentPage() - 1) * $acceptedAppointments->perPage() + $index + 1 }}</td>
                    <td>
                        <span class="priority-badge {{ $priorityClass }}">{{ $priorityLabel }}</span>
                    </td>
                    <td>{{ $appointment->username ?? 'N/A' }}</td>
                    <td>{{ $appointment->title }}</td>
                    <td>{{ $appointment->procedure }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->end)->format('M d, Y h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #9ca3af;">No upcoming appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
            {{ $acceptedAppointments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
    // Date filter functionality
    document.getElementById('appointmentDateFilter').addEventListener('change', function() {
        const selectedDate = this.value;
        document.getElementById('hiddenAppointmentDate').value = selectedDate;
        document.getElementById('appointmentSearchForm').submit();
    });
</script>
@endsection
