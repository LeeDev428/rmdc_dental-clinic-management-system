<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Rating::all(); // Fetch all reviews from the ratings_review table
        return view('admin.reviews.index', compact('reviews'));
    }
}
