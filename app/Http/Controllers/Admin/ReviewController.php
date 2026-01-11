<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::with(['user', 'appointment']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('rating', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by rating
        if ($request->filled('rating_filter')) {
            $query->where('rating', $request->rating_filter);
        }

        // Filter by featured status
        if ($request->filled('featured_filter')) {
            $query->where('featured', $request->featured_filter === 'featured');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $reviews = $query->paginate(15)->appends($request->all());
        $featuredCount = Rating::where('featured', true)->count();

        return view('admin.reviews.index', compact('reviews', 'featuredCount'));
    }

    public function toggleFeatured(Request $request, $id)
    {
        $review = Rating::findOrFail($id);
        $featuredCount = Rating::where('featured', true)->count();

        // Check if trying to feature more than 6 reviews
        if (!$review->featured && $featuredCount >= 6) {
            return response()->json([
                'success' => false,
                'message' => 'You can only feature up to 6 reviews. Please unfeature another review first.'
            ], 400);
        }

        $review->featured = !$review->featured;
        $review->save();

        return response()->json([
            'success' => true,
            'featured' => $review->featured,
            'message' => $review->featured ? 'Review featured successfully' : 'Review unfeatured successfully'
        ]);
    }
}
