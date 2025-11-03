@extends('layouts.admin')

@section('title', 'Pending Appointments')

@section('content')
<style>
    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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

    .table img {
        max-width: 100%;
        height: auto;
        cursor: pointer;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .container {
        background-color: #f0f8ff;
        padding: 30px;
        border-radius: 10px;
    }

    h1 {
        color: #007BFF;
        font-weight: bold;
    }

    .btn {
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .dropdown-menu {
        min-width: 150px;
    }

    .dropdown-item {
        padding: 10px 15px;
        cursor: pointer;
        font-size: 14px;
        white-space: nowrap;
    }

    .dropdown-item:hover {
        background-color: #e0f7fa;
    }
    
    .filter-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .filter-buttons .btn.active {
        background-color: #0056b3;
        color: white;
    }

    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 14px;
        }
    }
</style>

<div class="container mb-5">
    <h1 class="mb-4">Pending Appointments</h1>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Date Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.upcoming_appointments') }}" id="searchForm">
                <input type="text" name="search" class="form-control" placeholder="Search by procedure, status, or username" value="{{ request('search') }}">
                <input type="hidden" name="date" id="hiddenDateInput" value="{{ request('date') }}">
            </form>
        </div>
        <div class="col-md-6">
            <input type="date" id="dateFilter" class="form-control" value="{{ request('date') }}" placeholder="Filter by date">
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-buttons mb-3">
        <a href="{{ route('admin.upcoming_appointments', ['filter' => 'today']) }}" 
           class="btn btn-outline-primary {{ request('filter') == 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('admin.upcoming_appointments', ['filter' => 'week']) }}" 
           class="btn btn-outline-primary {{ request('filter') == 'week' ? 'active' : '' }}">
            This Week
        </a>
        <a href="{{ route('admin.upcoming_appointments', ['filter' => 'month']) }}" 
           class="btn btn-outline-primary {{ request('filter') == 'month' ? 'active' : '' }}">
            This Month
        </a>
        <a href="{{ route('admin.upcoming_appointments') }}" 
           class="btn btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">
            All
        </a>
    </div>

    <!-- Buttons -->
    <div class="d-flex mb-3">
        <a href="{{ route('admin.patient_information') }}" class="btn btn-info me-2">Patient Information</a>
        <a href="{{ route('admin.appointments') }}" class="btn btn-primary me-2">Upcoming Appointments</a>
        <a href="{{ route('admin.declined_appointments') }}" class="btn btn-danger">Declined Appointments</a>
    </div>

    <!-- Appointments Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
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
                            <img src="{{ Storage::url($appointment->image_path) }}" alt="Valid ID" style="width: 60px; height: 50px; border-radius: 5px;" onclick="zoomImage(this)">
                        @else
                            <img src="https://via.placeholder.com/100" alt="Default ID Image" style="width: 60px; height: 50px; border-radius: 5px;">
                        @endif
                    </td>
                    <td>{{ $appointment->status }}</td>
                    <td>{{ $appointment->procedure }}</td>
                    <td>{{ $appointment->start }}</td>
                    <td>{{ $appointment->created_at }}</td>
                    <td>{{ $appointment->updated_at }}</td>
                    <td>
                        <!-- Accept and Decline Buttons (Vertically Aligned) -->
                        <div class="d-flex  gap-2">
                            <button class="btn btn-success btn-sm accept-action" data-appointment-id="{{ $appointment->id }}">
                                Accept
                            </button>
                            <button class="btn btn-danger btn-sm decline-action" data-appointment-id="{{ $appointment->id }}">
                                Decline
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
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
