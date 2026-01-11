@extends('layouts.admin')

@section('title', 'Teeth Layout Management')

@section('content')

@include('admin.teeth_layout.styles')

<div class="page-header">
    <h2 class="page-title"><i class="fas fa-tooth"></i> Teeth Layout Management</h2>
</div>

<!-- Patient List Table -->
<div class="content-card">
    <div class="table-header">
        <h4><i class="fas fa-users"></i> Select a Patient</h4>
        <input type="text" id="patient-filter" class="form-control" placeholder="Search patients..." oninput="filterPatientTable()" style="max-width:300px;">
    </div>
    <div class="table-responsive">
        <table class="patient-table" id="patient-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr data-name="{{ strtolower($user->name) }}" data-id="{{ $user->id }}">
                    <td>#{{ $user->id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="selectUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                            <i class="fas fa-tooth"></i> View Teeth
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('admin.teeth_layout.patient-section')

@include('admin.teeth_layout.modal')

@include('admin.teeth_layout.scripts')

@endsection
