<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DentalRecordController extends Controller
{
    // Show all dental records for a specific patient
    public function index($userId)
    {
        $patient = User::findOrFail($userId);
        $records = DentalRecord::where('user_id', $userId)
            ->with('dentist')
            ->orderBy('visit_date', 'desc')
            ->get();
        
        return view('admin.dental_records.index', compact('patient', 'records'));
    }

    // Show form to create new dental record
    public function create($userId)
    {
        $patient = User::findOrFail($userId);
        return view('admin.dental_records.create', compact('patient'));
    }

    // Store new dental record
    public function store(Request $request, $userId)
    {
        $validated = $request->validate([
            'visit_date' => 'required|date',
            'chief_complaint' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'oral_examination' => 'nullable|string',
            'gum_condition' => 'nullable|string',
            'tooth_condition' => 'nullable|string',
            'xray_findings' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'treatment_performed' => 'nullable|string',
            'teeth_numbers' => 'nullable|string',
            'prescription' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'next_visit' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $validated['user_id'] = $userId;
        $validated['dentist_id'] = Auth::id();

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('dental_records', 'public');
                $attachments[] = $path;
            }
            $validated['attachments'] = $attachments;
        }

        DentalRecord::create($validated);

        return redirect()->route('admin.dental_records.index', $userId)
            ->with('success', 'Dental record added successfully');
    }

    // Show specific dental record
    public function show($userId, $recordId)
    {
        $patient = User::findOrFail($userId);
        $record = DentalRecord::where('user_id', $userId)
            ->where('id', $recordId)
            ->with('dentist')
            ->firstOrFail();
        
        return view('admin.dental_records.show', compact('patient', 'record'));
    }

    // Show edit form
    public function edit($userId, $recordId)
    {
        $patient = User::findOrFail($userId);
        $record = DentalRecord::where('user_id', $userId)
            ->where('id', $recordId)
            ->firstOrFail();
        
        return view('admin.dental_records.edit', compact('patient', 'record'));
    }

    // Update dental record
    public function update(Request $request, $userId, $recordId)
    {
        $record = DentalRecord::where('user_id', $userId)
            ->where('id', $recordId)
            ->firstOrFail();

        $validated = $request->validate([
            'visit_date' => 'required|date',
            'chief_complaint' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_pressure' => 'nullable|string',
            'oral_examination' => 'nullable|string',
            'gum_condition' => 'nullable|string',
            'tooth_condition' => 'nullable|string',
            'xray_findings' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'treatment_performed' => 'nullable|string',
            'teeth_numbers' => 'nullable|string',
            'prescription' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'next_visit' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            $attachments = $record->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('dental_records', 'public');
                $attachments[] = $path;
            }
            $validated['attachments'] = $attachments;
        }

        $record->update($validated);

        return redirect()->route('admin.dental_records.index', $userId)
            ->with('success', 'Dental record updated successfully');
    }

    // Delete dental record
    public function destroy($userId, $recordId)
    {
        $record = DentalRecord::where('user_id', $userId)
            ->where('id', $recordId)
            ->firstOrFail();

        // Delete attachments
        if ($record->attachments) {
            foreach ($record->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $record->delete();

        return redirect()->route('admin.dental_records.index', $userId)
            ->with('success', 'Dental record deleted successfully');
    }
}
