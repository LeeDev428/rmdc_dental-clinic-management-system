@extends('layouts.admin')

@section('title', 'Upcoming Appointments')

@section('content')
<style>
    .modern-container {
        background-color: #f5f7fa;
        min-height: calc(100vh - 100px);
        padding: 30px 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .page-title {
        color: #fff;
        font-size: 28px;
        font-weight: 600;
        margin: 0;
    }

    .search-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .search-input {
        border: 2px solid #e4e7eb;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-pill {
        padding: 10px 20px;
        border-radius: 25px;
        border: 2px solid #e4e7eb;
        background: #fff;
        color: #64748b;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .filter-pill:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #475569;
    }

    .filter-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #fff;
    }

    .data-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        margin: 0;
    }

    .modern-table thead th {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
        color: #475569;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px 20px;
        border: none;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 16px 20px;
        color: #334155;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-urgent {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .priority-soon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .priority-scheduled {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .alert-modern {
        border-radius: 10px;
        border: none;
        padding: 16px 20px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-modern {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 14px;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-search {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-clear {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-clear:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .pagination {
        margin: 20px 0 0 0;
    }

    .pagination .page-link {
        border: none;
        color: #667eea;
        padding: 10px 16px;
        border-radius: 8px;
        margin: 0 4px;
        font-weight: 500;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 22px;
        }

        .filter-pills {
            flex-direction: column;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 12px;
            font-size: 12px;
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
