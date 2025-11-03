@extends('layouts.admin')

@section('title', 'Upcoming Appointments')

@section('content')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        table-layout: auto;
    }

    .table th {
        background-color: #007BFF;
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

    h2 {
        color: #007BFF;
        font-weight: bold;
    }

    .btn {
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .input-group {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .filter-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .filter-buttons .btn.active {
        background-color: #0056b3;
        color: white;
    }
    
    .priority-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .priority-high {
        background-color: #ff4444;
        color: white;
    }
    
    .priority-medium {
        background-color: #ffa500;
        color: white;
    }
    
    .priority-low {
        background-color: #4CAF50;
        color: white;
    }

    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 14px;
        }
    }
</style>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="card-title mb-4">Upcoming Appointments (Priority by Nearest Time)</h2>
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.appointments') }}" id="appointmentSearchForm">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-start" 
                                   placeholder="Search by title or procedure" 
                                   value="{{ request('search') }}">
                            <input type="hidden" name="date" id="hiddenAppointmentDate" value="{{ request('date') }}">
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                            @if(request('search') || request('date'))
                                <a href="{{ route('admin.appointments') }}" class="btn btn-secondary">Clear</a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <input type="date" id="appointmentDateFilter" class="form-control" value="{{ request('date') }}" placeholder="Filter by date">
                </div>
            </div>
            
            <!-- Filter Buttons -->
            <div class="filter-buttons mb-3">
                <a href="{{ route('admin.appointments', ['filter' => 'today']) }}" 
                   class="btn btn-outline-primary {{ request('filter') == 'today' ? 'active' : '' }}">
                    Today
                </a>
                <a href="{{ route('admin.appointments', ['filter' => 'week']) }}" 
                   class="btn btn-outline-primary {{ request('filter') == 'week' ? 'active' : '' }}">
                    This Week
                </a>
                <a href="{{ route('admin.appointments', ['filter' => 'month']) }}" 
                   class="btn btn-outline-primary {{ request('filter') == 'month' ? 'active' : '' }}">
                    This Month
                </a>
                <a href="{{ route('admin.appointments') }}" 
                   class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">
                    All Upcoming
                </a>
            </div>

            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Priority</th>
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
                            <td>{{ $appointment->title }}</td>
                            <td>{{ $appointment->procedure }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y h:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->end)->format('M d, Y h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No upcoming appointments found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $acceptedAppointments->appends(request()->query())->links() }}
                </div>
            </div>
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
