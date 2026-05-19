<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Startup;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store or update a review.
     */
    public function store(Request $request, $startupId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000'
        ]);

        $startup = Startup::findOrFail($startupId);

        Review::updateOrCreate(
            [
                'startup_id' => $startup->id,
                'user_id' => Auth::id()
            ],
            [
                'rating' => (int) $request->rating,
                'review_text' => $request->review_text
            ]
        );

        return back()->with('success', 'Your review has been saved successfully!');
    }
}
