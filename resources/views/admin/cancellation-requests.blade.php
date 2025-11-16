@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="fas fa-ban text-danger"></i> Cancellation Requests
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Cancellation Requests</li>
    </ol>

    <!-- Policy Reminder -->
    <div class="alert alert-warning shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">Cancellation Policy Reminder</h5>
                <p class="mb-0 small">
                    Patients can only cancel appointments at least <strong>2 days (48 hours)</strong> before the scheduled time. 
                    Same-day cancellations are not permitted. Down payments are non-refundable for late cancellations.
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Pending Cancellations</div>
                            <div class="h2 mb-0">{{ $pendingCancellations ?? 0 }}</div>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Approved Today</div>
                            <div class="h2 mb-0">{{ $approvedToday ?? 0 }}</div>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total This Week</div>
                            <div class="h2 mb-0">{{ $weeklyTotal ?? 0 }}</div>
                        </div>
                        <i class="fas fa-calendar-week fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Late Cancellations</div>
                            <div class="h2 mb-0">{{ $lateCancellations ?? 0 }}</div>
                        </div>
                        <i class="fas fa-exclamation-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancellation Requests Table -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-list me-1"></i>
            Cancellation Requests
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="cancellationTable">
                    <thead class="table-light">
                        <tr>
                            <th>Request Date</th>
                            <th>Patient Name</th>
                            <th>Appointment Date</th>
                            <th>Procedure</th>
                            <th>Days Until Appt</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Placeholder - will be populated with actual data -->
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p>No cancellation requests at the moment</p>
                                <small>Pending cancellation requests will appear here</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .opacity-50 {
        opacity: 0.5;
    }
    .card {
        border: none;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
