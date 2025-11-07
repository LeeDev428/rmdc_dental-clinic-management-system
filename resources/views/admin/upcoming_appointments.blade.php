@extends('layouts.admin')

@section('title', 'Pending Appointments')

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
    
    .search-input {
        flex: 1;
        min-width: 250px;
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
        background-color: #0084ff;
        color: #fff;
        border-color: #0084ff;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .btn-nav {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-nav.info {
        background-color: #0ea5e9;
        color: #fff;
    }
    
    .btn-nav.info:hover {
        background-color: #0284c7;
        color: #fff;
    }
    
    .btn-nav.primary {
        background-color: #0084ff;
        color: #fff;
    }
    
    .btn-nav.primary:hover {
        background-color: #0073e6;
        color: #fff;
    }
    
    .btn-nav.danger {
        background-color: #ef4444;
        color: #fff;
    }
    
    .btn-nav.danger:hover {
        background-color: #dc2626;
        color: #fff;
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
    
    .id-image {
        width: 60px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid #e0e0e0;
    }
    
    .action-btn-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-accept {
        background-color: #10b981;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-accept:hover {
        background-color: #059669;
    }
    
    .btn-decline {
        background-color: #ef4444;
        color: #fff;
        border: none;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-decline:hover {
        background-color: #dc2626;
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
    
    .modal-body label {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 8px;
        display: block;
    }
    
    .modal-body textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        resize: vertical;
        min-height: 100px;
    }
    
    .modal-body textarea:focus {
        outline: none;
        border-color: #0084ff;
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
    }
    
    .modal-footer .btn-secondary {
        background-color: #f0f0f0;
        color: #1a1a1a;
    }
    
    .modal-footer .btn-secondary:hover {
        background-color: #e0e0e0;
    }
    
    .modal-footer .btn-danger {
        background-color: #ef4444;
        color: #fff;
    }
    
    .modal-footer .btn-danger:hover {
        background-color: #dc2626;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-top: 24px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Pending Appointments</h1>
</div>

<div class="content-card">

<div class="content-card">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Date Filter -->
    <div class="search-section">
        <form method="GET" action="{{ route('admin.upcoming_appointments') }}" id="searchForm" style="flex: 1; min-width: 250px;">
            <input type="text" name="search" class="search-input" placeholder="Search by procedure, status, or username" value="{{ request('search') }}">
            <input type="hidden" name="date" id="hiddenDateInput" value="{{ request('date') }}">
        </form>
        <input type="date" id="dateFilter" class="date-input" value="{{ request('date') }}" placeholder="Filter by date">
    </div>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
        <a href="{{ route('admin.upcoming_appointments', ['filter' => 'today']) }}" 
           class="filter-btn {{ request('filter') == 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('admin.upcoming_appointments', ['filter' => 'week']) }}" 
           class="filter-btn {{ request('filter') == 'week' ? 'active' : '' }}">
            This Week
        </a>
        <a href="{{ route('admin.upcoming_appointments', ['filter' => 'month']) }}" 
           class="filter-btn {{ request('filter') == 'month' ? 'active' : '' }}">
            This Month
        </a>
        <a href="{{ route('admin.upcoming_appointments') }}" 
           class="filter-btn {{ !request('filter') ? 'active' : '' }}">
            All
        </a>
    </div>

    <!-- Navigation Buttons -->
    <div class="action-buttons">
        <a href="{{ route('admin.patient_information') }}" class="btn-nav info">Patient Information</a>
        <a href="{{ route('admin.appointments') }}" class="btn-nav primary">Upcoming Appointments</a>
        <a href="{{ route('admin.declined_appointments') }}" class="btn-nav danger">Declined Appointments</a>
    </div>

    <!-- Appointments Table -->
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Valid ID</th>
                    <th>Status</th>
                    <th>Procedure</th>
                    <th>Start Time</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Filter out accepted and declined appointments and sort by creation date --}}
                @php
                    $filteredAppointments = $appointments->filter(function ($appointment) {
                        return !in_array($appointment->status, ['accepted', 'declined']);
                    })->sortByDesc('created_at');
                @endphp

                @foreach($filteredAppointments as $index => $appointment)
                <tr>
                    <td style="font-size: 12px;">{{ $appointment->user_id }}</td>
                    <td style="font-size: 12px;">{{ $appointment->username }}</td>
                    <td style="font-size: 12px;">
                        @if($appointment->image_path)
                            <img src="{{ Storage::url($appointment->image_path) }}" alt="Valid ID" class="id-image" onclick="zoomImage(this)">
                        @else
                            <img src="https://via.placeholder.com/100" alt="Default ID Image" class="id-image">
                        @endif
                    </td>
                    <td style="font-size: 12px;">{{ $appointment->status }}</td>
                    <td style="font-size: 12px;">{{ $appointment->procedure }}</td>
                    <td style="font-size: 12px;">{{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y g:i A') }}</td>
                    <td style="font-size: 12px;">{{ $appointment->created_at }}</td>
                    <td style="font-size: 12px;">{{ $appointment->updated_at }}</td>
                    <td>
                        <div class="action-btn-group">
                            <button class="btn-view-details-pending" onclick="showPendingAppointmentDetails({{ $appointment->id }})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-accept accept-action" data-appointment-id="{{ $appointment->id }}">
                                Accept
                            </button>
                            <button class="btn-decline decline-action" data-appointment-id="{{ $appointment->id }}">
                                Decline
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination">
            {{ $appointments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="declineForm" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Decline Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="appointment_id" id="appointment_id">
          <label for="message">Reason for Declining:</label>
          <textarea name="message" id="message" class="form-control" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Decline</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Date filter functionality
        $('#dateFilter').on('change', function () {
            const selectedDate = $(this).val();
            $('#hiddenDateInput').val(selectedDate);
            $('#searchForm').submit();
        });

        // Accept action
        $('.accept-action').on('click', function (e) {
            e.preventDefault();
            const appointmentId = $(this).data('appointment-id');
            const url = '{{ route("appointment.messageFromAdmin", ["id" => ":id", "action" => "accept"]) }}'.replace(':id', appointmentId);

            Swal.fire({
                title: "Accept Appointment?",
                text: "Are you sure you want to accept this appointment?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Accept",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Processing...",
                        text: "Please wait...",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Decline action
        $('.decline-action').on('click', function (e) {
            e.preventDefault();
            const appointmentId = $(this).data('appointment-id');
            $('#appointment_id').val(appointmentId);
            $('#declineForm').attr('action', '{{ route("appointment.messageFromAdmin", ["id" => ":id", "action" => "decline"]) }}'.replace(':id', appointmentId));
            $('#declineModal').modal('show');
        });

        // Decline Form Submit
        $('#declineForm').on('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: "Processing...",
                text: "Please wait...",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(this);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = $(this).attr('action');
            form.style.display = 'none';

            for (const [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        });
    });

    // Image Zoom
    function zoomImage(img) {
        let overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
        overlay.style.display = "flex";
        overlay.style.justifyContent = "center";
        overlay.style.alignItems = "center";
        overlay.style.zIndex = "9999";

        let zoomedImg = document.createElement("img");
        zoomedImg.src = img.src;
        zoomedImg.style.maxWidth = "90%";
        zoomedImg.style.maxHeight = "90%";
        zoomedImg.style.borderRadius = "5px";
        zoomedImg.style.boxShadow = "0px 0px 20px rgba(255, 255, 255, 0.5)";
        overlay.onclick = function () {
            document.body.removeChild(overlay);
        };

        overlay.appendChild(zoomedImg);
        document.body.appendChild(overlay);
    }
    
    // Show pending appointment details modal
    function showPendingAppointmentDetails(appointmentId) {
        const modal = document.getElementById('pendingDetailsModal');
        const content = document.getElementById('pendingDetailsContent');
        
        modal.style.display = 'flex';
        content.innerHTML = '<div class="loading-details">Loading appointment details...</div>';
        
        // Fetch appointment details
        fetch(`/admin/appointments/${appointmentId}/details`)
            .then(response => response.json())
            .then(data => {
                content.innerHTML = generatePendingDetailsHTML(data);
            })
            .catch(error => {
                content.innerHTML = '<div class="loading-details" style="color: #ef4444;">Error loading details. Please try again.</div>';
                console.error('Error:', error);
            });
    }
    
    // Generate pending appointment details HTML
    function generatePendingDetailsHTML(appointment) {
        const paymentStatus = appointment.payment_status || 'unpaid';
        const paymentBadge = {
            'unpaid': '<span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">UNPAID</span>',
            'pending': '<span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">PENDING</span>',
            'partially_paid': '<span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">PARTIALLY PAID</span>',
            'paid': '<span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">PAID</span>',
            'fully_paid': '<span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">FULLY PAID</span>'
        };
        
        let html = '<div class="details-grid">';
        
        // Patient Information
        html += `
            <div class="detail-box">
                <div class="detail-label-pending">Patient Name</div>
                <div class="detail-value-pending">${appointment.user?.name || 'N/A'}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Patient Email</div>
                <div class="detail-value-pending">${appointment.user?.email || 'N/A'}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Contact Number</div>
                <div class="detail-value-pending">${appointment.user?.phone || 'N/A'}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Appointment ID</div>
                <div class="detail-value-pending">#${appointment.id}</div>
            </div>
            <div class="detail-box full">
                <div class="detail-label-pending">Title</div>
                <div class="detail-value-pending">${appointment.title}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Procedure</div>
                <div class="detail-value-pending">${appointment.procedure}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Duration</div>
                <div class="detail-value-pending">${appointment.duration} minutes</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Start Time</div>
                <div class="detail-value-pending">${formatDateTime(appointment.start)}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">End Time</div>
                <div class="detail-value-pending">${formatDateTime(appointment.end)}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Status</div>
                <div class="detail-value-pending status ${appointment.status}">${appointment.status.toUpperCase()}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label-pending">Created At</div>
                <div class="detail-value-pending">${formatDateTime(appointment.created_at)}</div>
            </div>
        `;
        
        // Payment Information
        if (appointment.requires_payment || appointment.total_price) {
            html += `
                <div class="detail-box">
                    <div class="detail-label-pending">Total Price</div>
                    <div class="detail-value-pending">₱${parseFloat(appointment.total_price || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label-pending">Down Payment</div>
                    <div class="detail-value-pending">₱${parseFloat(appointment.down_payment || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label-pending">Remaining Balance</div>
                    <div class="detail-value-pending">₱${parseFloat((appointment.total_price || 0) - (appointment.down_payment || 0)).toLocaleString('en-US', {minimumFractionDigits: 2})}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label-pending">Payment Method</div>
                    <div class="detail-value-pending">${appointment.payment_method ? appointment.payment_method.toUpperCase() : 'N/A'}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label-pending">Payment Status</div>
                    <div class="detail-value-pending">${paymentBadge[paymentStatus]}</div>
                </div>
                <div class="detail-box">
                    <div class="detail-label-pending">Payment Reference</div>
                    <div class="detail-value-pending">${appointment.payment_reference || 'N/A'}</div>
                </div>
            `;
        }
        
        // Valid ID Image
        if (appointment.image_path) {
            html += `
                <div class="detail-box full">
                    <div class="detail-label-pending">Valid ID / Supporting Document</div>
                    <img src="/storage/${appointment.image_path}" alt="Valid ID" class="detail-image-pending">
                </div>
            `;
        }
        
        // Teeth Layout
        if (appointment.teeth_layout) {
            html += `
                <div class="detail-box full">
                    <div class="detail-label-pending">Teeth Layout Information</div>
                    <div class="detail-value-pending">${appointment.teeth_layout}</div>
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
    
    // Close pending details modal
    function closePendingDetailsModal() {
        document.getElementById('pendingDetailsModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('pendingDetailsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePendingDetailsModal();
        }
    });
</script>

<!-- Pending Appointment Details Modal -->
<div id="pendingDetailsModal" class="details-modal" style="display: none;">
    <div class="details-modal-content">
        <div class="details-modal-header">
            <h2>Appointment Details</h2>
            <span class="close-details-modal" onclick="closePendingDetailsModal()">&times;</span>
        </div>
        <div class="details-modal-body" id="pendingDetailsContent">
            <div class="loading-details">Loading appointment details...</div>
        </div>
    </div>
</div>

<style>
    .btn-view-details-pending {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
        margin-right: 4px;
    }
    
    .btn-view-details-pending:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .details-modal {
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
    
    .details-modal-content {
        background: white;
        border-radius: 12px;
        max-width: 800px;
        width: 90%;
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
    
    .details-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .details-modal-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .close-details-modal {
        font-size: 28px;
        color: #9ca3af;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }
    
    .close-details-modal:hover {
        color: #ef4444;
    }
    
    .details-modal-body {
        padding: 24px;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .detail-box {
        background: #f9fafb;
        padding: 16px;
        border-radius: 8px;
        border-left: 3px solid #3b82f6;
    }
    
    .detail-box.full {
        grid-column: 1 / -1;
    }
    
    .detail-label-pending {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    
    .detail-value-pending {
        font-size: 15px;
        color: #1a1a1a;
        font-weight: 500;
    }
    
    .detail-value-pending.status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .detail-value-pending.status.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .detail-value-pending.status.accepted {
        background: #d1fae5;
        color: #065f46;
    }
    
    .detail-value-pending.status.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .detail-image-pending {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .loading-details {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
        font-size: 14px;
    }
</style>

@endsection
