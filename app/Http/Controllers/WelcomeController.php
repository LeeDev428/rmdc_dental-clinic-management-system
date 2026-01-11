<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;
use App\Models\Rating;

class WelcomeController extends Controller
{
    public function index()
    {
        $procedures = ProcedurePrice::all(); // Fetch all dental procedures
        $featuredReviews = Rating::with('user')
            ->where('featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        return view('welcome', compact('procedures', 'featuredReviews'));
    }
    
    public function getServices(Request $request)
    {
        $procedures = ProcedurePrice::paginate(12); // Match dashboard: 12 items per page (3x4 grid)
        
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
