<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;
use App\Models\Appointment; // Ensure the Appointment model is imported

class DashboardController extends Controller
{
    public function index()
    {
        $procedures = ProcedurePrice::all(); // Fetch all dental procedures
        $appointments = Appointment::where('user_id', auth()->id())->latest()->first(); // Fetch the latest appointment for the logged-in user
        return view('dashboard', compact('procedures', 'appointments'));
    }
}
