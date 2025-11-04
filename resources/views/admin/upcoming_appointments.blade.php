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
                    <td>{{ $appointment->user_id }}</td>
                    <td>{{ $appointment->username }}</td>
                    <td>
                        @if($appointment->image_path)
                            <img src="{{ Storage::url($appointment->image_path) }}" alt="Valid ID" class="id-image" onclick="zoomImage(this)">
                        @else
                            <img src="https://via.placeholder.com/100" alt="Default ID Image" class="id-image">
                        @endif
                    </td>
                    <td>{{ $appointment->status }}</td>
                    <td>{{ $appointment->procedure }}</td>
                    <td style="font-size: 12px;">{{ \Carbon\Carbon::parse($appointment->start)->format('M d, Y g:i A') }}</td>
                    <td style="font-size: 12px;">{{ $appointment->created_at }}</td>
                    <td style="font-size: 12px;">{{ $appointment->updated_at }}</td>
                    <td>
                        <div class="action-btn-group">
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
</script>
@endsection
