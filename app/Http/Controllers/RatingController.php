<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string|max:255',
        ]);

        Rating::create([
            'rating' => $validated['rating'],
            'message' => $validated['message'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Rating saved successfully.']);
    }
}
