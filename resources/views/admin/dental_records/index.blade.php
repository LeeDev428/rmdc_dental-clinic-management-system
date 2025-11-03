@extends('layouts.admin')

@section('title', 'Dental Records - ' . $patient->name)

@section('content')
<style>
    .page-header {
        background-color: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }
    
    .patient-info {
        background-color: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .patient-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
    }
    
    .patient-details h3 {
        margin: 0 0 4px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .patient-details p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }
    
    .content-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }
    
    .btn-primary {
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary:hover {
        background-color: #0073e6;
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
    }
    
    .btn-success {
        background-color: #16a34a;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-success:hover {
        background-color: #15803d;
    }
    
    .btn-danger {
        background-color: #ef4444;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-danger:hover {
        background-color: #dc2626;
    }
    
    .records-grid {
        display: grid;
        gap: 16px;
    }
    
    .record-card {
        background-color: #f8f9fa;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.2s;
    }
    
    .record-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .record-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }
    
    .record-date {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }
    
    .record-actions {
        display: flex;
        gap: 8px;
    }
    
    .record-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .record-field {
        font-size: 14px;
    }
    
    .record-field strong {
        display: block;
        color: #1a1a1a;
        margin-bottom: 4px;
    }
    
    .record-field span {
        color: #6b7280;
    }
    
    .alert-info {
        background-color: #e0f2fe;
        color: #0369a1;
        padding: 16px;
        border-radius: 6px;
        border-left: 4px solid #0284c7;
        font-size: 14px;
    }
    
    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        padding: 16px;
        border-radius: 6px;
        border-left: 4px solid #10b981;
        font-size: 14px;
        margin-bottom: 24px;
    }
    
    .dentist-info {
        font-size: 13px;
        color: #6b7280;
        font-style: italic;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Dental Records - {{ $patient->name }}</h1>
    <div>
        <a href="{{ route('admin.dental_records.create', $patient->id) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Add New Record
        </a>
        <a href="{{ route('admin.patient_information') }}" class="btn-secondary" style="margin-left: 8px;">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>
</div>

<div class="patient-info">
    <img src="{{ $patient->avatar_url }}" 
         alt="{{ $patient->name }}" 
         onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
         class="patient-avatar">
    <div class="patient-details">
        <h3>{{ $patient->name }}</h3>
        <p><i class="fas fa-envelope"></i> {{ $patient->email }}</p>
        <p><i class="fas fa-user"></i> {{ ucfirst($patient->usertype) }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="content-card">
    @if($records->isEmpty())
        <div class="alert-info">
            <i class="fas fa-info-circle"></i> No dental records found for this patient. Click "Add New Record" to create one.
        </div>
    @else
        <div class="records-grid">
            @foreach($records as $record)
                <div class="record-card">
                    <div class="record-header">
                        <div>
                            <div class="record-date">
                                <i class="fas fa-calendar"></i> {{ $record->visit_date->format('F d, Y') }}
                            </div>
                            @if($record->dentist)
                                <p class="dentist-info">by Dr. {{ $record->dentist->name }}</p>
                            @endif
                        </div>
                        <div class="record-actions">
                            <a href="{{ route('admin.dental_records.show', [$patient->id, $record->id]) }}" 
                               class="btn-success" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.dental_records.edit', [$patient->id, $record->id]) }}" 
                               class="btn-secondary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.dental_records.destroy', [$patient->id, $record->id]) }}" 
                                  method="POST" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="record-content">
                        @if($record->chief_complaint)
                            <div class="record-field">
                                <strong>Chief Complaint:</strong>
                                <span>{{ \Illuminate\Support\Str::limit($record->chief_complaint, 50) }}</span>
                            </div>
                        @endif
                        
                        @if($record->diagnosis)
                            <div class="record-field">
                                <strong>Diagnosis:</strong>
                                <span>{{ \Illuminate\Support\Str::limit($record->diagnosis, 50) }}</span>
                            </div>
                        @endif
                        
                        @if($record->treatment_performed)
                            <div class="record-field">
                                <strong>Treatment:</strong>
                                <span>{{ \Illuminate\Support\Str::limit($record->treatment_performed, 50) }}</span>
                            </div>
                        @endif
                        
                        @if($record->next_visit)
                            <div class="record-field">
                                <strong>Next Visit:</strong>
                                <span>{{ $record->next_visit->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
