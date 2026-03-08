@extends('layouts.admin')

@section('title', 'Reschedule Requests')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid">
    <div class="page-header mb-4" style="background:#fff;padding:24px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);">
        <h1 style="font-size:24px;font-weight:600;margin:0;color:#1a1a1a;">Reschedule Requests</h1>
    </div>

    <!-- Policy Info Banner -->
    <div class="alert alert-info" style="border-left:4px solid #3b82f6;">
        <strong>Policy:</strong> Reschedules must be made at least 48 hours before the appointment. Same-day requests are not permitted.
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Reschedules</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rescheduleTotal }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar-alt fa-2x text-gray-300"></i></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rescheduleToday }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rescheduleWeekly }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar-week fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Late Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rescheduleLate }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reschedule.requests') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search">Search</label>
                        <input type="text" id="search" name="search"
                               class="form-control form-control-sm"
                               placeholder="Patient name, email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from">From Date</label>
                        <input type="text" id="date_from" name="date_from"
                               class="form-control form-control-sm datepicker"
                               placeholder="Start date"
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_to">To Date</label>
                        <input type="text" id="date_to" name="date_to"
                               class="form-control form-control-sm datepicker"
                               placeholder="End date"
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control form-control-sm">
                            <option value="">All Statuses</option>
                            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="accepted"  {{ request('status') === 'accepted'  ? 'selected' : '' }}>Accepted</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm mr-2">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.reschedule.requests') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reschedule Requests</h6>
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
                        @forelse($reschedules as $reschedule)
                        <tr>
                            <td>#{{ $reschedule->id }}</td>
                            <td>
                                <div class="font-weight-bold">{{ $reschedule->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $reschedule->user->email ?? '' }}</small>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($reschedule->appointment->start)->format('M d, Y') }}<br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($reschedule->appointment->start)->format('g:i A') }}</small>
                            </td>
                            <td>{{ $reschedule->appointment->procedure ?? 'N/A' }}</td>
                            <td>
                                {{ $reschedule->processed_at->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $reschedule->processed_at->format('g:i A') }}</small>
                            </td>
                            <td>
                                @php
                                    $apptTime    = \Carbon\Carbon::parse($reschedule->appointment->start);
                                    $daysUntil   = (int) $reschedule->processed_at->diffInDays($apptTime, false);
                                    $hoursNotice = (int) $reschedule->processed_at->diffInHours($apptTime, false);
                                @endphp
                                @if($daysUntil >= 0)
                                    <div class="font-weight-bold">{{ $daysUntil }} days</div>
                                    <small class="text-muted">({{ $hoursNotice }} hrs)</small>
                                    @if($hoursNotice < 48)
                                        <br><span class="badge bg-warning text-dark">Late</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger text-white">Past Due</span>
                                @endif
                            </td>
                            <td>
                                <div style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                     title="{{ $reschedule->reason }}">
                                    {{ Str::limit($reschedule->reason, 50) }}
                                </div>
                            </td>
                            <td>
                                @if($reschedule->appointment->status === 'cancelled')
                                    <span class="badge bg-danger text-white">Cancelled</span>
                                @elseif($reschedule->appointment->status === 'completed')
                                    <span class="badge bg-success text-white">Completed</span>
                                @elseif($reschedule->appointment->status === 'accepted')
                                    <span class="badge bg-info text-white">Accepted</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ ucfirst($reschedule->appointment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                        onclick='viewDetails(@json($reschedule))'
                                        title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="font-weight-bold">No reschedule requests found</p>
                                <p class="text-muted">Rescheduled appointments will appear here</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reschedules->hasPages())
            <div class="mt-3">
                {{ $reschedules->appends(request()->query())->links('vendor.pagination.compact-bootstrap-5') }}
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
                    <i class="fas fa-info-circle"></i> Reschedule Request Details
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
                        <strong>Appointment Date &amp; Time:</strong>
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
                        <strong>Status:</strong>
                        <p id="modalStatus"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Reason:</strong>
                        <p id="modalReason" class="text-muted" style="white-space:pre-wrap;"></p>
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
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('.datepicker', { dateFormat: 'Y-m-d', allowInput: true });
});

function viewDetails(reschedule) {
    const appointment = reschedule.appointment;
    const user        = reschedule.user;

    const apptTime    = new Date(appointment.start);
    const reqTime     = new Date(reschedule.processed_at);
    const diffMs      = Math.abs(apptTime - reqTime);
    const diffDays    = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    const diffHours   = Math.floor(diffMs / (1000 * 60 * 60));

    document.getElementById('modalPatientName').textContent  = user.name  || 'N/A';
    document.getElementById('modalEmail').textContent        = user.email || 'N/A';
    document.getElementById('modalProcedure').textContent    = appointment.procedure || 'N/A';
    document.getElementById('modalDateTime').textContent     = new Date(appointment.start).toLocaleString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    document.getElementById('modalRequestDate').textContent  = new Date(reschedule.processed_at).toLocaleString('en-US', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    document.getElementById('modalNotice').innerHTML = diffDays >= 0
        ? `<strong>${diffDays} days</strong> (${diffHours} hours) ${diffHours < 48 ? '<span class="badge bg-warning text-dark">Late Notice</span>' : ''}`
        : '<span class="badge bg-danger text-white">Past Due</span>';
    document.getElementById('modalReason').textContent = reschedule.reason;

    const statusEl  = document.getElementById('modalStatus');
    const statusMap = {
        cancelled: '<span class="badge bg-danger text-white">Cancelled</span>',
        completed:  '<span class="badge bg-success text-white">Completed</span>',
        accepted:   '<span class="badge bg-info text-white">Accepted</span>',
    };
    statusEl.innerHTML = statusMap[appointment.status]
        || `<span class="badge bg-secondary text-white">${appointment.status}</span>`;

    bootstrap.Modal.getOrCreateInstance(document.getElementById('detailsModal')).show();
}
</script>
@endsection


