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
        $procedures = ProcedurePrice::paginate(9); // 3x3 grid like dashboard
        
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('partials.services-cards', compact('procedures'))->render();
            $pagination = $procedures->render('pagination::bootstrap-4');
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }
        
        return response()->json([
            'data' => $procedures->items(),
            'current_page' => $procedures->currentPage(),
            'last_page' => $procedures->lastPage(),
            'total' => $procedures->total()
        ]);
    }
}
