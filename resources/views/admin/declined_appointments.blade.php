@extends('layouts.admin')

@section('title', 'Declined Appointments')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0" style="background-color: #ffffff; border-radius: 12px;">
        <div class="card-body">
            <h2 class="card-title mb-4 text-danger">Declined Appointments</h2>

            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by patient name, title, or procedure">
                </div>
                <div class="col-md-6">
                    <input type="date" id="datePicker" class="form-control">
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle" style="background-color: #fff; border-radius: 8px;">
                    <thead style="background-color: #FF4C4C; color: white;">
                        <tr>
                            <th class="px-3 py-2">Patient Name</th>
                            <th class="px-3 py-2">Title</th>
                            <th class="px-3 py-2">Procedure</th>
                            <th class="px-3 py-2">Reason</th>
                            <th class="px-3 py-2">Declined At</th>
                            <th class="px-3 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointment-data">
                        @foreach($declinedAppointments as $index => $appointment)
                        <tr>
                            <td class="px-3 py-2">{{ $appointment->patient_name }}</td>
                            <td class="px-3 py-2">{{ $appointment->title }}</td>
                            <td class="px-3 py-2" style="word-wrap: break-word; max-width: 200px;">{{ $appointment->procedure }}</td>
                            <td class="px-3 py-2" style="word-wrap: break-word; max-width: 200px;">
                                <span class="short-reason" onclick="showReasonModal('{{ $appointment->decline_reason }}')" style="cursor: pointer;">
                                    {{ Str::limit($appointment->decline_reason, 50, '...') }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $appointment->updated_at }}</td>
                            <td class="px-3 py-2">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown{{ $appointment->user_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $appointment->user_id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.patient_information', ['id' => $appointment->user_id]) }}">View Patient</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reason Modal -->
<div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reasonModalLabel">Full Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reasonModalBody">
                <!-- Full reason will be dynamically inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Filtering Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const datePicker = document.getElementById('datePicker');
        const tableRows = document.querySelectorAll('#appointment-data tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedDate = datePicker.value;

            tableRows.forEach(row => {
                const patientName = row.cells[1].textContent.toLowerCase();
                const title = row.cells[2].textContent.toLowerCase();
                const procedure = row.cells[3].textContent.toLowerCase();
                const declinedAt = row.cells[5].textContent;

                const matchesSearch = patientName.includes(searchValue) || title.includes(searchValue) || procedure.includes(searchValue);
                const matchesDate = !selectedDate || declinedAt.startsWith(selectedDate);

                row.style.display = matchesSearch && matchesDate ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        datePicker.addEventListener('change', filterTable);
    });

    function showReasonModal(reason) {
        const modalBody = document.getElementById('reasonModalBody');
        modalBody.textContent = reason;
        const reasonModal = new bootstrap.Modal(document.getElementById('reasonModal'));
        reasonModal.show();
    }
</script>
@endsection
