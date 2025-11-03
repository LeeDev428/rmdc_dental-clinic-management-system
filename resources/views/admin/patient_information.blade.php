@extends('layouts.admin')

@section('title', 'Patient Information')

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
    
    .search-group {
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
    
    .date-group {
        flex: 1;
        min-width: 250px;
        display: flex;
        gap: 8px;
    }
    
    .date-input {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
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
    
    .profile-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }
    
    .usertype-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        background-color: #f0f9ff;
        color: #0084ff;
        border: 1px solid #e0f2fe;
    }
    
    .alert-warning {
        background-color: #fff4e6;
        color: #d97706;
        padding: 16px;
        border-radius: 6px;
        border-left: 4px solid #d97706;
        text-align: center;
        font-size: 14px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">User's Information</h1>
</div>

<div class="content-card">
    <!-- Search and Date Filter -->
    <form method="GET" action="{{ route('admin.patient_information') }}">
        <div class="search-section">
            <div class="search-group">
                <input type="text" class="search-input" name="search" placeholder="Search by Name, Email, Usertype..." value="{{ request('search') }}">
                <button class="btn-primary" type="submit">Search</button>
            </div>
            <div class="date-group">
                <input type="date" id="datePicker" class="date-input" name="date" value="{{ request('date') }}">
                <button class="btn-secondary" type="button" id="clearDate">Clear Date</button>
            </div>
        </div>
    </form>

    @if($user->isEmpty())
        <div class="alert-warning">
            No Information was found.
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Usertype</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user as $u)
                        <tr>
                            <td>
                                <img src="{{ $u->avatar_url }}" 
                                     alt="{{ $u->name }}" 
                                     onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                                     class="profile-img">
                            </td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="usertype-badge">
                                    {{ $u->usertype }}
                                </span>
                            </td>
                            <td>{{ $u->created_at->format('Y-m-d') }}</td>
                            <td>{{ $u->updated_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.dental_records.index', $u->id) }}" 
                                   class="btn-primary" 
                                   style="text-decoration: none; display: inline-block;">
                                    <i class="fas fa-file-medical"></i> Dental Records
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
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
