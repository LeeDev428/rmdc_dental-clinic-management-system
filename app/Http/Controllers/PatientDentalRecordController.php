<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
use App\Models\ToothRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientDentalRecordController extends Controller
{
    public function index()
    {
        $records = DentalRecord::where('user_id', Auth::id())
            ->with('dentist')
            ->orderBy('visit_date', 'desc')
            ->get();
        
        return view('patient.dental_records', compact('records'));
    }

    public function show($id)
    {
        $record = DentalRecord::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('dentist')
            ->firstOrFail();
        
        return view('patient.dental_record_detail', compact('record'));
    }

    /**
     * Get teeth chart data for the authenticated patient
     */
    public function getTeethChart($patientId)
    {
        // Ensure patient can only view their own teeth chart
        if (Auth::id() != $patientId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $teeth = ToothRecord::where('user_id', $patientId)
            ->with('notes')
            ->orderBy('tooth_number')
            ->get()
            ->map(function($tooth) {
                return [
                    'tooth_number' => $tooth->tooth_number,
                    'condition' => $tooth->condition,
                    'notes' => $tooth->notes->map(function($note) {
                        return [
                            'note' => $note->note,
                            'created_at' => $note->created_at->format('M d, Y')
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'teeth' => $teeth
        ]);
    }
}
