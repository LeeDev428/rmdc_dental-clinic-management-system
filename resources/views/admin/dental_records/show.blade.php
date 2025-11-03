@extends('layouts.admin')

@section('title', 'Dental Record Details')

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
        padding: 32px;
    }
    
    .record-section {
        margin-bottom: 32px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .info-field {
        padding: 16px;
        background-color: #f8f9fa;
        border-radius: 6px;
    }
    
    .info-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-field .value {
        font-size: 15px;
        color: #1a1a1a;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    .info-field .value.empty {
        color: #9ca3af;
        font-style: italic;
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
        padding: 10px 20px;
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
    
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .attachments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
    }
    
    .attachment-item {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s;
    }
    
    .attachment-item:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .attachment-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .attachment-item.pdf {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 150px;
        background-color: #f8f9fa;
    }
    
    .attachment-item.pdf i {
        font-size: 48px;
        color: #ef4444;
    }
    
    .attachment-link {
        display: block;
        padding: 8px;
        text-align: center;
        font-size: 12px;
        color: #0084ff;
        text-decoration: none;
        background-color: #f8f9fa;
    }
    
    .attachment-link:hover {
        background-color: #e5e7eb;
    }
    
    .patient-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background-color: #f0f9ff;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    
    .patient-badge img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .patient-badge span {
        font-weight: 600;
        color: #0369a1;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Dental Record Details</h1>
</div>

<div class="content-card">
    <div class="patient-badge">
        <img src="{{ $patient->avatar_url }}" 
             alt="{{ $patient->name }}" 
             onerror="this.src='{{ asset('img/default-dp.jpg') }}'">
        <span>Patient: {{ $patient->name }}</span>
    </div>

    <div class="action-buttons">
        <a href="{{ route('admin.dental_records.edit', [$patient->id, $record->id]) }}" class="btn-primary">
            <i class="fas fa-edit"></i> Edit Record
        </a>
        <a href="{{ route('admin.dental_records.index', $patient->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to All Records
        </a>
    </div>

    <!-- Visit Information -->
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-calendar-check"></i> Visit Information
        </h3>
        <div class="info-grid">
            <div class="info-field">
                <label>Visit Date</label>
                <div class="value">{{ $record->visit_date->format('F d, Y') }}</div>
            </div>
            <div class="info-field">
                <label>Chief Complaint</label>
                <div class="value {{ $record->chief_complaint ? '' : 'empty' }}">
                    {{ $record->chief_complaint ?: 'Not specified' }}
                </div>
            </div>
            @if($record->dentist)
            <div class="info-field">
                <label>Attended By</label>
                <div class="value">Dr. {{ $record->dentist->name }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Medical History -->
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-notes-medical"></i> Medical History
        </h3>
        <div class="info-grid">
            <div class="info-field">
                <label>Medical History</label>
                <div class="value {{ $record->medical_history ? '' : 'empty' }}">
                    {{ $record->medical_history ?: 'No medical history recorded' }}
                </div>
            </div>
            <div class="info-field">
                <label>Current Medications</label>
                <div class="value {{ $record->current_medications ? '' : 'empty' }}">
                    {{ $record->current_medications ?: 'No current medications' }}
                </div>
            </div>
            <div class="info-field">
                <label>Allergies</label>
                <div class="value {{ $record->allergies ? '' : 'empty' }}">
                    {{ $record->allergies ?: 'No known allergies' }}
                </div>
            </div>
            <div class="info-field">
                <label>Blood Pressure</label>
                <div class="value {{ $record->blood_pressure ? '' : 'empty' }}">
                    {{ $record->blood_pressure ?: 'Not recorded' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Dental Examination -->
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-teeth"></i> Dental Examination
        </h3>
        <div class="info-grid">
            <div class="info-field">
                <label>Oral Examination</label>
                <div class="value {{ $record->oral_examination ? '' : 'empty' }}">
                    {{ $record->oral_examination ?: 'No findings recorded' }}
                </div>
            </div>
            <div class="info-field">
                <label>Gum Condition</label>
                <div class="value {{ $record->gum_condition ? '' : 'empty' }}">
                    {{ $record->gum_condition ?: 'No findings recorded' }}
                </div>
            </div>
            <div class="info-field">
                <label>Tooth Condition</label>
                <div class="value {{ $record->tooth_condition ? '' : 'empty' }}">
                    {{ $record->tooth_condition ?: 'No findings recorded' }}
                </div>
            </div>
            <div class="info-field">
                <label>X-Ray Findings</label>
                <div class="value {{ $record->xray_findings ? '' : 'empty' }}">
                    {{ $record->xray_findings ?: 'No X-ray findings' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Diagnosis & Treatment -->
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-stethoscope"></i> Diagnosis & Treatment
        </h3>
        <div class="info-grid">
            <div class="info-field">
                <label>Diagnosis</label>
                <div class="value {{ $record->diagnosis ? '' : 'empty' }}">
                    {{ $record->diagnosis ?: 'No diagnosis recorded' }}
                </div>
            </div>
            <div class="info-field">
                <label>Treatment Plan</label>
                <div class="value {{ $record->treatment_plan ? '' : 'empty' }}">
                    {{ $record->treatment_plan ?: 'No treatment plan' }}
                </div>
            </div>
            <div class="info-field">
                <label>Treatment Performed</label>
                <div class="value {{ $record->treatment_performed ? '' : 'empty' }}">
                    {{ $record->treatment_performed ?: 'No treatment performed' }}
                </div>
            </div>
            <div class="info-field">
                <label>Teeth Numbers</label>
                <div class="value {{ $record->teeth_numbers ? '' : 'empty' }}">
                    {{ $record->teeth_numbers ?: 'Not specified' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Prescriptions & Recommendations -->
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-pills"></i> Prescriptions & Recommendations
        </h3>
        <div class="info-grid">
            <div class="info-field">
                <label>Prescription</label>
                <div class="value {{ $record->prescription ? '' : 'empty' }}">
                    {{ $record->prescription ?: 'No prescription given' }}
                </div>
            </div>
            <div class="info-field">
                <label>Recommendations</label>
                <div class="value {{ $record->recommendations ? '' : 'empty' }}">
                    {{ $record->recommendations ?: 'No recommendations' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-up & Notes -->
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-clipboard"></i> Follow-up & Additional Notes
        </h3>
        <div class="info-grid">
            <div class="info-field">
                <label>Next Visit</label>
                <div class="value {{ $record->next_visit ? '' : 'empty' }}">
                    {{ $record->next_visit ? $record->next_visit->format('F d, Y') : 'Not scheduled' }}
                </div>
            </div>
            <div class="info-field">
                <label>Additional Notes</label>
                <div class="value {{ $record->notes ? '' : 'empty' }}">
                    {{ $record->notes ?: 'No additional notes' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Attachments -->
    @if($record->attachments && count($record->attachments) > 0)
    <div class="record-section">
        <h3 class="section-title">
            <i class="fas fa-paperclip"></i> Attachments
        </h3>
        <div class="attachments-grid">
            @foreach($record->attachments as $attachment)
                @php
                    $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                    $isPdf = strtolower($extension) === 'pdf';
                @endphp
                
                <div class="attachment-item {{ $isPdf ? 'pdf' : '' }}">
                    @if($isPdf)
                        <i class="fas fa-file-pdf"></i>
                    @else
                        <img src="{{ asset('storage/' . $attachment) }}" alt="Attachment">
                    @endif
                    <a href="{{ asset('storage/' . $attachment) }}" 
                       target="_blank" 
                       class="attachment-link">
                        <i class="fas fa-external-link-alt"></i> View
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
