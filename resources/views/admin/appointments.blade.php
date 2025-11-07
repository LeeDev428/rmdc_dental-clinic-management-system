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
                    <th>Actions</th>
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
                    <td>
                        <button class="btn-view-details" onclick="showAppointmentDetails({{ $appointment->id }})" title="View Details">
                            <i class="fas fa-eye"></i> Details
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #9ca3af;">No upcoming appointments found.</td>
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

<!-- Appointment Details Modal -->
<div id="appointmentDetailsModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2>Appointment Details</h2>
            <span class="close-modal" onclick="closeDetailsModal()">&times;</span>
        </div>
        <div class="modal-body" id="appointmentDetailsContent">
            <div class="loading">Loading appointment details...</div>
        </div>
    </div>
</div>

<style>
    .btn-view-details {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-view-details:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }
    
    .modal-content {
        background: white;
        border-radius: 12px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .close-modal {
        font-size: 28px;
        color: #9ca3af;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }
    
    .close-modal:hover {
        color: #ef4444;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .detail-item {
        background: #f9fafb;
        padding: 16px;
        border-radius: 8px;
        border-left: 3px solid #3b82f6;
    }
    
    .detail-item.full-width {
        grid-column: 1 / -1;
    }
    
    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .detail-value {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 500;
    }
    
    .detail-value.status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .detail-value.status.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .detail-value.status.accepted {
        background: #d1fae5;
        color: #065f46;
    }
    
    .detail-value.status.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .appointment-image {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
        font-size: 14px;
    }
</style>

<script>
    // Date filter functionality
    document.getElementById('appointmentDateFilter').addEventListener('change', function() {
        const selectedDate = this.value;
        document.getElementById('hiddenAppointmentDate').value = selectedDate;
        document.getElementById('appointmentSearchForm').submit();
    });
    
    // Show appointment details modal
    function showAppointmentDetails(appointmentId) {
        const modal = document.getElementById('appointmentDetailsModal');
        const content = document.getElementById('appointmentDetailsContent');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading">Loading appointment details...</div>';
        
        // Fetch appointment details
        fetch(`/admin/appointments/${appointmentId}/details`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = generateAppointmentDetailsHTML(data);
            })
            .catch(error => {
                content.innerHTML = '<div class="loading" style="color: #ef4444;">Error loading appointment details. Please try again.</div>';
                console.error('Error:', error);
            });
    }
    
    // Generate appointment details HTML
    function generateAppointmentDetailsHTML(appointment) {
        const paymentStatus = appointment.payment_status || 'unpaid';
        const paymentBadge = {
            'unpaid': '<span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">UNPAID</span>',
            'partially_paid': '<span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">PARTIALLY PAID</span>',
            'fully_paid': '<span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">FULLY PAID</span>'
        };
        
        let html = '<div class="detail-grid">';
        
        // Patient Information
        html += `
            <div class="detail-item">
                <div class="detail-label">Patient Name</div>
                <div class="detail-value">${appointment.user?.name || 'N/A'}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Patient Email</div>
                <div class="detail-value">${appointment.user?.email || 'N/A'}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Contact Number</div>
                <div class="detail-value">${appointment.user?.phone || 'N/A'}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Appointment ID</div>
                <div class="detail-value">#${appointment.id}</div>
            </div>
        `;
        
        // Appointment Details
        html += `
            <div class="detail-item full-width">
                <div class="detail-label">Title</div>
                <div class="detail-value">${appointment.title}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Procedure</div>
                <div class="detail-value">${appointment.procedure}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value">${appointment.duration} minutes</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Start Time</div>
                <div class="detail-value">${formatDateTime(appointment.start)}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">End Time</div>
                <div class="detail-value">${formatDateTime(appointment.end)}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value status ${appointment.status}">${appointment.status.toUpperCase()}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created At</div>
                <div class="detail-value">${formatDateTime(appointment.created_at)}</div>
            </div>
        `;
        
        // Payment Information
        if (appointment.requires_payment || appointment.total_price) {
            html += `
                <div class="detail-item">
                    <div class="detail-label">Total Price</div>
                    <div class="detail-value">₱${parseFloat(appointment.total_price || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Down Payment</div>
                    <div class="detail-value">₱${parseFloat(appointment.down_payment || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Remaining Balance</div>
                    <div class="detail-value">₱${parseFloat((appointment.total_price || 0) - (appointment.down_payment || 0)).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Status</div>
                    <div class="detail-value">${paymentBadge[paymentStatus]}</div>
                </div>
            `;
        }
        
        // Valid ID Image
        if (appointment.image_path) {
            html += `
                <div class="detail-item full-width">
                    <div class="detail-label">Valid ID / Supporting Document</div>
                    <img src="/storage/${appointment.image_path}" alt="Valid ID" class="appointment-image">
                </div>
            `;
        }
        
        // Teeth Layout
        if (appointment.teeth_layout) {
            html += `
                <div class="detail-item full-width">
                    <div class="detail-label">Teeth Layout Information</div>
                    <div class="detail-value">${appointment.teeth_layout}</div>
                </div>
            `;
        }
        
        html += '</div>';
        return html;
    }
    
    // Format date time
    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return 'N/A';
        const date = new Date(dateTimeString);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }
    
    // Close modal
    function closeDetailsModal() {
        document.getElementById('appointmentDetailsModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('appointmentDetailsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailsModal();
        }
    });
</script>
@endsection
