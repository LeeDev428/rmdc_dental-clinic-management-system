<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;
use App\Models\Appointment; // Ensure the Appointment model is imported

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Check if AJAX request for pagination
        if ($request->ajax()) {
            $procedures = ProcedurePrice::paginate(12);
            return response()->json([
                'html' => view('partials.services-cards', compact('procedures'))->render(),
                'pagination' => $procedures->links('pagination::bootstrap-4')->render()
            ]);
        }

        $procedures = ProcedurePrice::paginate(12); // Paginate with 12 items per page
        $appointments = Appointment::where('user_id', auth()->id())->latest()->first(); // Fetch the latest appointment for the logged-in user
        return view('dashboard', compact('procedures', 'appointments'));
    }
}
