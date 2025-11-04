<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAppointmentHistoryController extends Controller
{
    /**
     * Display appointment history for a specific user
     */
    public function show($userId)
    {
        // Get the user
        $user = User::findOrFail($userId);
        
        // Get all appointments for this user with pagination
        $appointments = Appointment::where('user_id', $userId)
            ->orderBy('start', 'desc')
            ->paginate(10);
        
        // Get appointment statistics
        $totalAppointments = Appointment::where('user_id', $userId)->count();
        $pendingAppointments = Appointment::where('user_id', $userId)->where('status', 'pending')->count();
        $acceptedAppointments = Appointment::where('user_id', $userId)->where('status', 'accepted')->count();
        $completedAppointments = Appointment::where('user_id', $userId)->where('status', 'completed')->count();
        $declinedAppointments = Appointment::where('user_id', $userId)->where('status', 'declined')->count();
        
        return view('admin.user-appointment-history', compact(
            'user',
            'appointments',
            'totalAppointments',
            'pendingAppointments',
            'acceptedAppointments',
            'completedAppointments',
            'declinedAppointments'
        ));
    }
}
