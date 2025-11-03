<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
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
}
