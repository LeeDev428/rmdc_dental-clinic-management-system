@extends('layouts.admin')

@section('title', 'Upcoming Appointments')

@section('content')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

    .table-responsive {
        overflow-x: auto;
    }

    .container {
        background-color: #f0f8ff;
        padding: 30px;
        border-radius: 10px;
    }

    h2 {
        color: #007BFF;
        font-weight: bold;
    }

    .btn {
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 5px;
    }

    .input-group {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 14px;
        }
    }
</style>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="card-title mb-4">Upcoming Appointments</h2>

            <!-- Search Bar and Date Picker -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control rounded-start" placeholder="Search by title or procedure">
                        <button class="btn btn-primary" type="button" id="clearSearch">Clear</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="date" id="datePicker" class="form-control">
                        <button class="btn btn-outline-secondary" type="button" id="clearDate">Clear Date</button>
                    </div>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Procedure</th>
                            <th>Start Time</th>
                            <th>End Time</th> <!-- Added End Time column -->
                        </tr>
                    </thead>
                    <tbody id="appointment-data">
                        @foreach($acceptedAppointments as $index => $appointment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $appointment->title }}</td>
                            <td>{{ $appointment->procedure }}</td>
                            <td>{{ $appointment->start }}</td>
                            <td>{{ $appointment->end }}</td> <!-- Display End Time -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Filtering Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const datePicker = document.getElementById('datePicker');
        const clearSearchButton = document.getElementById('clearSearch');
        const clearDateButton = document.getElementById('clearDate');
        const tableRows = document.querySelectorAll('#appointment-data tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedDate = datePicker.value;

            tableRows.forEach(row => {
                const title = row.cells[1].textContent.toLowerCase();
                const procedure = row.cells[2].textContent.toLowerCase();
                const startTime = row.cells[3].textContent;

                const matchesSearch = title.includes(searchValue) || procedure.includes(searchValue);
                const matchesDate = !selectedDate || startTime.startsWith(selectedDate);

                row.style.display = matchesSearch && matchesDate ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        datePicker.addEventListener('change', filterTable);

        clearSearchButton.addEventListener('click', function () {
            searchInput.value = '';
            filterTable();
        });

        clearDateButton.addEventListener('click', function () {
            datePicker.value = '';
            filterTable();
        });
    });
</script>
@endsection
