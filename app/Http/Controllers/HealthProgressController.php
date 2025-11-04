<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Tooth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HealthProgressController extends Controller
{
    /**
     * Display the user's health progress
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get appointments with their procedures over time
        $appointments = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['accepted', 'completed'])
            ->orderBy('start', 'asc')
            ->get();
        
        // Get dental records (tooth numbers recorded) over time
        $dentalRecords = Tooth::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get treatment statistics
        $totalAppointments = Appointment::where('user_id', $user->id)->count();
        $completedTreatments = Appointment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $pendingTreatments = Appointment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $acceptedTreatments = Appointment::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->count();
        
        // Get monthly appointment trends (last 6 months)
        $monthlyData = Appointment::where('user_id', $user->id)
            ->where('start', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(start) as month'),
                DB::raw('YEAR(start) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Get total teeth recorded
        $totalTeethRecorded = Tooth::where('user_id', $user->id)->count();
        
        // Get appointment status distribution
        $statusDistribution = Appointment::where('user_id', $user->id)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        return view('health-progress', compact(
            'appointments',
            'dentalRecords',
            'totalAppointments',
            'completedTreatments',
            'pendingTreatments',
            'acceptedTreatments',
            'monthlyData',
            'totalTeethRecorded',
            'statusDistribution'
        ));
    }
    
    /**
     * Display the admin view of a patient's health progress
     */
    public function adminView($userId)
    {
        // Get appointments with their procedures over time
        $appointments = Appointment::where('user_id', $userId)
            ->whereIn('status', ['accepted', 'completed'])
            ->orderBy('start', 'asc')
            ->get();
        
        // Get dental records (tooth numbers recorded) over time
        $dentalRecords = Tooth::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get treatment statistics
        $totalAppointments = Appointment::where('user_id', $userId)->count();
        $completedTreatments = Appointment::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();
        $pendingTreatments = Appointment::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
        $acceptedTreatments = Appointment::where('user_id', $userId)
            ->where('status', 'accepted')
            ->count();
        
        // Get monthly appointment trends (last 6 months)
        $monthlyData = Appointment::where('user_id', $userId)
            ->where('start', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(start) as month'),
                DB::raw('YEAR(start) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Get total teeth recorded
        $totalTeethRecorded = Tooth::where('user_id', $userId)->count();
        
        // Get appointment status distribution
        $statusDistribution = Appointment::where('user_id', $userId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Get user info
        $patient = \App\Models\User::findOrFail($userId);
        
        return view('admin.health-progress', compact(
            'appointments',
            'dentalRecords',
            'totalAppointments',
            'completedTreatments',
            'pendingTreatments',
            'acceptedTreatments',
            'monthlyData',
            'totalTeethRecorded',
            'statusDistribution',
            'patient'
        ));
    }
}
