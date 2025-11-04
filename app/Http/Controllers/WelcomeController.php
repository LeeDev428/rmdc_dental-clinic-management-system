<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;

class WelcomeController extends Controller
{
    public function index()
    {
        $procedures = ProcedurePrice::all(); // Fetch all dental procedures
        return view('welcome', compact('procedures'));
    }
    
    public function getServices(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 12;
        
        $procedures = ProcedurePrice::paginate($perPage);
        
        return response()->json([
            'data' => $procedures->items(),
            'current_page' => $procedures->currentPage(),
            'last_page' => $procedures->lastPage(),
            'total' => $procedures->total()
        ]);
    }
}
