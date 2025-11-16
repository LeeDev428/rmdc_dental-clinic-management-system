@extends('layouts.admin')

@section('title', 'Completed Appointments History')

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
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .btn-view {
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-view:hover {
        background-color: #0073e6;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #6c757d;
    }
    
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 0;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-title {
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #6c757d;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .modal-close:hover {
        background-color: #f0f0f0;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .detail-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        font-weight: 600;
        color: #1a1a1a;
        width: 140px;
        flex-shrink: 0;
    }
    
    .detail-value {
        color: #4a4a4a;
        flex: 1;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Completed Appointments History</h1>
</div>

<div class="content-card">
    <!-- Search Section -->
    <form method="GET" action="{{ route('admin.completed_appointments') }}">
        <div class="search-section">
            <div class="search-input-group">
                <input type="text" 
                       name="search" 
                       class="search-input" 
                       placeholder="Search by patient name, procedure..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <input type="date" 
                   name="date" 
                   class="date-input" 
                   value="{{ request('date') }}">
            @if(request('search') || request('date') || request('filter'))
                <a href="{{ route('admin.completed_appointments') }}" class="btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </div>
    </form>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
        <a href="{{ route('admin.completed_appointments') }}" 
           class="filter-btn {{ !request('filter') ? 'active' : '' }}">
            All Time
        </a>
        <a href="{{ route('admin.completed_appointments', ['filter' => 'today']) }}" 
           class="filter-btn {{ request('filter') == 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('admin.completed_appointments', ['filter' => 'week']) }}" 
           class="filter-btn {{ request('filter') == 'week' ? 'active' : '' }}">
            This Week
        </a>
        <a href="{{ route('admin.completed_appointments', ['filter' => 'month']) }}" 
           class="filter-btn {{ request('filter') == 'month' ? 'active' : '' }}">
            This Month
        </a>
    </div>

    <!-- Table -->
    @if($completedAppointments->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Procedure</th>
                    <th>Appointment Date</th>
                    <th>Completed Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedAppointments as $appointment)
                <tr>
                    <td>{{ $appointment->username }}</td>
                    <td>{{ $appointment->procedure }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->updated_at)->format('M d, Y h:i A') }}</td>
                    <td>
                        <span class="status-badge status-completed">
                            <i class="fas fa-check-circle"></i> Completed
                        </span>
                    </td>
                    <td>
                        <button class="btn-view" onclick="showAppointmentDetails({{ $appointment->id }})">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            {{ $completedAppointments->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>No Completed Appointments</h3>
            <p>There are no completed appointments yet.</p>
        </div>
    @endif
</div>

<!-- Appointment Details Modal -->
<div id="appointmentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Appointment Details</h2>
            <button class="modal-close" onclick="closeDetailsModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <p style="text-align: center; color: #6c757d;">Loading...</p>
        </div>
    </div>
</div>

<script>
    function showAppointmentDetails(id) {
        const modal = document.getElementById('appointmentModal');
        const modalBody = document.getElementById('modalBody');
        
        // Show modal with loading state
        modal.style.display = 'block';
        modalBody.innerHTML = '<p style="text-align: center; color: #6c757d;">Loading...</p>';
        
        // Fetch appointment details
        fetch(`/admin/appointments/${id}/details`)
            .then(response => response.json())
            .then(appointment => {
                const startDate = new Date(appointment.start);
                const endDate = new Date(appointment.end);
                const completedDate = new Date(appointment.updated_at);
                
                modalBody.innerHTML = `
                    <div class="detail-row">
                        <div class="detail-label">Patient Name:</div>
                        <div class="detail-value">${appointment.user.name}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Procedure:</div>
                        <div class="detail-value">${appointment.procedure}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Appointment Date:</div>
                        <div class="detail-value">${startDate.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Time:</div>
                        <div class="detail-value">${startDate.toLocaleTimeString('en-US', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        })} - ${endDate.toLocaleTimeString('en-US', { 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        })}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Completed Date:</div>
                        <div class="detail-value">${completedDate.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric',
                            hour: '2-digit', 
                            minute: '2-digit'
                        })}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Status:</div>
                        <div class="detail-value">
                            <span class="status-badge status-completed">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                        </div>
                    </div>
                    ${appointment.description ? `
                        <div class="detail-row">
                            <div class="detail-label">Description:</div>
                            <div class="detail-value">${appointment.description}</div>
                        </div>
                    ` : ''}
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = '<p style="text-align: center; color: #dc3545;">Failed to load appointment details.</p>';
            });
    }
    
    function closeDetailsModal() {
        document.getElementById('appointmentModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('appointmentModal');
        if (event.target === modal) {
            closeDetailsModal();
        }
    };
</script>
@endsection
