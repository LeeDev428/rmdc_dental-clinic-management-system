@extends('layouts.admin')

@section('title', 'Add Dental Record - ' . $patient->name)

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
    
    .form-section {
        margin-bottom: 32px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        color: #1a1a1a;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #0084ff;
        box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.1);
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    .btn-primary {
        background-color: #0084ff;
        color: #fff;
        border: none;
        padding: 12px 24px;
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
        background-color: #6b7280;
        color: #fff;
        border: none;
        padding: 12px 24px;
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
    
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
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
    
    .file-input {
        padding: 8px 12px;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Add New Dental Record</h1>
</div>

<div class="content-card">
    <div class="patient-badge">
        <img src="{{ $patient->avatar_url }}" 
             alt="{{ $patient->name }}" 
             onerror="this.src='{{ asset('img/default-dp.jpg') }}'">
        <span>Patient: {{ $patient->name }}</span>
    </div>

    <form action="{{ route('admin.dental_records.store', $patient->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Visit Information -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-calendar-check"></i> Visit Information</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="visit_date">Visit Date *</label>
                    <input type="date" id="visit_date" name="visit_date" class="form-control" required value="{{ old('visit_date', date('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label for="chief_complaint">Chief Complaint</label>
                    <input type="text" id="chief_complaint" name="chief_complaint" class="form-control" value="{{ old('chief_complaint') }}" placeholder="e.g., Toothache, Bleeding gums">
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-notes-medical"></i> Medical History</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="medical_history">Medical History</label>
                    <textarea id="medical_history" name="medical_history" class="form-control" placeholder="Previous illnesses, surgeries, etc.">{{ old('medical_history') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="current_medications">Current Medications</label>
                    <textarea id="current_medications" name="current_medications" class="form-control" placeholder="List all current medications">{{ old('current_medications') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="allergies">Allergies</label>
                    <textarea id="allergies" name="allergies" class="form-control" placeholder="Drug allergies, food allergies, etc.">{{ old('allergies') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="blood_pressure">Blood Pressure</label>
                    <input type="text" id="blood_pressure" name="blood_pressure" class="form-control" value="{{ old('blood_pressure') }}" placeholder="e.g., 120/80">
                </div>
            </div>
        </div>

        <!-- Dental Examination -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-teeth"></i> Dental Examination</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="oral_examination">Oral Examination</label>
                    <textarea id="oral_examination" name="oral_examination" class="form-control" placeholder="General oral condition findings">{{ old('oral_examination') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="gum_condition">Gum Condition</label>
                    <textarea id="gum_condition" name="gum_condition" class="form-control" placeholder="Gingival health, bleeding, inflammation">{{ old('gum_condition') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="tooth_condition">Tooth Condition</label>
                    <textarea id="tooth_condition" name="tooth_condition" class="form-control" placeholder="Cavities, fractures, wear">{{ old('tooth_condition') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="xray_findings">X-Ray Findings</label>
                    <textarea id="xray_findings" name="xray_findings" class="form-control" placeholder="Radiographic observations">{{ old('xray_findings') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Diagnosis & Treatment -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-stethoscope"></i> Diagnosis & Treatment</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="diagnosis">Diagnosis</label>
                    <textarea id="diagnosis" name="diagnosis" class="form-control" placeholder="Clinical diagnosis">{{ old('diagnosis') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="treatment_plan">Treatment Plan</label>
                    <textarea id="treatment_plan" name="treatment_plan" class="form-control" placeholder="Planned procedures and timeline">{{ old('treatment_plan') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="treatment_performed">Treatment Performed</label>
                    <textarea id="treatment_performed" name="treatment_performed" class="form-control" placeholder="Procedures completed today">{{ old('treatment_performed') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="teeth_numbers">Teeth Numbers</label>
                    <input type="text" id="teeth_numbers" name="teeth_numbers" class="form-control" value="{{ old('teeth_numbers') }}" placeholder="e.g., 11, 12, 21, 22">
                </div>
            </div>
        </div>

        <!-- Prescriptions & Recommendations -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-pills"></i> Prescriptions & Recommendations</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="prescription">Prescription</label>
                    <textarea id="prescription" name="prescription" class="form-control" placeholder="Medications prescribed with dosage">{{ old('prescription') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="recommendations">Recommendations</label>
                    <textarea id="recommendations" name="recommendations" class="form-control" placeholder="Home care instructions, dietary advice">{{ old('recommendations') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Follow-up & Notes -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-clipboard"></i> Follow-up & Additional Notes</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="next_visit">Next Visit Date</label>
                    <input type="date" id="next_visit" name="next_visit" class="form-control" value="{{ old('next_visit') }}">
                </div>
                <div class="form-group">
                    <label for="notes">Additional Notes</label>
                    <textarea id="notes" name="notes" class="form-control" placeholder="Any other important information">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Attachments -->
        <div class="form-section">
            <h3 class="section-title"><i class="fas fa-paperclip"></i> Attachments</h3>
            <div class="form-group">
                <label for="attachments">Upload Files (X-rays, Photos, Documents)</label>
                <input type="file" id="attachments" name="attachments[]" class="form-control file-input" multiple accept=".jpg,.jpeg,.png,.pdf">
                <small style="color: #6b7280; display: block; margin-top: 8px;">
                    Accepted formats: JPG, JPEG, PNG, PDF. Max size: 5MB per file.
                </small>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Save Dental Record
            </button>
            <a href="{{ route('admin.dental_records.index', $patient->id) }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection
