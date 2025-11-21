@extends('layouts.admin')

@section('title', 'Cancellation Requests')

@section('content')
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
    
    .info-banner {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
    }
    
    .info-banner p {
        margin: 0;
        font-size: 14px;
        color: #92400e;
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
    
    .content-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .content-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .content-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background: #f9fafb;
        padding: 12px 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .data-table td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }
    
    .data-table tr:hover {
        background: #f9fafb;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state p {
        margin: 8px 0 0 0;
        font-size: 14px;
    }
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>Cancellation Requests</h2>
        <p>Manage patient appointment cancellation requests</p>
    </div>

    <!-- Policy Info Banner -->
    <div class="info-banner">
        <p><strong>Policy:</strong> Cancellations must be made at least 2 days (48 hours) before appointment. Same-day cancellations not permitted. Down payments are non-refundable for late cancellations.</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-label">Pending Cancellations</div>
            <div class="stat-value">{{ $pendingCancellations ?? 0 }}</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-label">Approved Today</div>
            <div class="stat-value">{{ $approvedToday ?? 0 }}</div>
        </div>
        <div class="stat-card weekly">
            <div class="stat-label">Total This Week</div>
            <div class="stat-value">{{ $weeklyTotal ?? 0 }}</div>
        </div>
        <div class="stat-card late">
            <div class="stat-label">Late Cancellations</div>
            <div class="stat-value">{{ $lateCancellations ?? 0 }}</div>
        </div>
    </div>

    <!-- Cancellation Requests Table -->
    <div class="content-card">
        <div class="content-header">
            <h3>Cancellation Requests</h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
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
                    @forelse($cancellations ?? [] as $cancellation)
                    <tr>
                        <td>{{ $cancellation->processed_at->format('M d, Y g:i A') }}</td>
                        <td>{{ $cancellation->user->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($cancellation->appointment->start)->format('M d, Y g:i A') }}</td>
                        <td>{{ $cancellation->appointment->procedure ?? 'N/A' }}</td>
                        <td>
                            @php
                                $appointmentTime = \Carbon\Carbon::parse($cancellation->appointment->start);
                                $daysUntil = (int)$cancellation->processed_at->diffInDays($appointmentTime, false);
                                $hoursNotice = (int)$cancellation->processed_at->diffInHours($appointmentTime, false);
                            @endphp
                            @if($daysUntil >= 0)
                                {{ $daysUntil }} days
                                @if($hoursNotice < 48)
                                    <span class="badge bg-warning">Late</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Past</span>
                            @endif
                        </td>
                        <td>
                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                 title="{{ $cancellation->reason }}">
                                {{ $cancellation->reason }}
                            </div>
                        </td>
                        <td>
                            @if($cancellation->appointment->status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($cancellation->appointment->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewDetails({{ $cancellation->id }}, '{{ $cancellation->user->name }}', '{{ $cancellation->appointment->procedure }}', '{{ \Carbon\Carbon::parse($cancellation->appointment->start)->format('M d, Y g:i A') }}', '{{ $cancellation->reason }}', '{{ $cancellation->type }}')" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p style="margin: 8px 0 0 0; font-weight: 500;">No cancellation requests</p>
                                <p style="font-size: 13px; color: #9ca3af;">Cancelled appointments will appear here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="detailsModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: white; margin: 5% auto; padding: 0; border-radius: 8px; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 20px; font-weight: 600; color: #1f2937;">Cancellation/Reschedule Details</h3>
                <button onclick="closeDetailsModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">&times;</button>
            </div>
        </div>
        <div style="padding: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 5px;">Patient Name:</label>
                <p id="modalPatientName" style="margin: 0; color: #6b7280;"></p>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 5px;">Procedure:</label>
                <p id="modalProcedure" style="margin: 0; color: #6b7280;"></p>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 5px;">Appointment Date & Time:</label>
                <p id="modalDateTime" style="margin: 0; color: #6b7280;"></p>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 5px;">Action Type:</label>
                <p id="modalType" style="margin: 0;"></p>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 5px;">Reason:</label>
                <p id="modalReason" style="margin: 0; color: #6b7280; white-space: pre-wrap;"></p>
            </div>
        </div>
        <div style="padding: 15px 20px; background-color: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 8px 8px;">
            <button onclick="closeDetailsModal()" style="background-color: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 500;">Close</button>
        </div>
    </div>
</div>

<script>
function viewDetails(id, patientName, procedure, dateTime, reason, type) {
    document.getElementById('modalPatientName').textContent = patientName;
    document.getElementById('modalProcedure').textContent = procedure;
    document.getElementById('modalDateTime').textContent = dateTime;
    document.getElementById('modalReason').textContent = reason;
    
    const typeElement = document.getElementById('modalType');
    if (type === 'reschedule') {
        typeElement.innerHTML = '<span style="background-color: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 12px; font-size: 14px; font-weight: 500;">Reschedule</span>';
    } else {
        typeElement.innerHTML = '<span style="background-color: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-size: 14px; font-weight: 500;">Cancel</span>';
    }
    
    document.getElementById('detailsModal').style.display = 'block';
}

function closeDetailsModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target === modal) {
        closeDetailsModal();
    }
}
</script>
@endsection
