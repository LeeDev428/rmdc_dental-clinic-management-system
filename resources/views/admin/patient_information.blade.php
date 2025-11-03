@extends('layouts.admin')

@section('title', 'Patient Information')

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

    .table img {
        max-width: 100%;
        height: auto;
        cursor: pointer;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    .badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 5px;
    }

    @media (max-width: 768px) {
        .table th,
        .table td {
            font-size: 14px;
        }
    }
</style>

<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="card-title mb-4">User's Information</h2>

            <!-- Search and Date Filter -->
            <form method="GET" action="{{ route('admin.patient_information') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-start" name="search" placeholder="Search by Name, Email, Usertype..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="date" id="datePicker" class="form-control" name="date" value="{{ request('date') }}">
                            <button class="btn btn-outline-secondary" type="button" id="clearDate">Clear Date</button>
                        </div>
                    </div>
                </div>
            </form>

            @if($user->isEmpty())
                <div class="alert alert-warning text-center">
                    No Information was found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Usertype</th>
                                <th>Created</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user as $u)
                                <tr>
                                    <td>
                                        <img src="{{ $u->avatar_url }}" 
                                             alt="{{ $u->name }}" 
                                             onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                                             style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border border-secondary">
                                            {{ $u->usertype }}
                                        </span>
                                    </td>
                                    <td>{{ $u->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $u->updated_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const datePicker = document.getElementById('datePicker');
        const clearDateButton = document.getElementById('clearDate');

        // Redirect with selected date
        datePicker.addEventListener('change', function () {
            const selectedDate = datePicker.value;
            const url = new URL(window.location.href);
            url.searchParams.set('date', selectedDate);
            window.location.href = url.toString();
        });

        // Clear date filter
        clearDateButton.addEventListener('click', function () {
            const url = new URL(window.location.href);
            url.searchParams.delete('date');
            window.location.href = url.toString();
        });
    });
</script>
@endsection
