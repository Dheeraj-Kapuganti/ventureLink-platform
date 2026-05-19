<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Startup;

class StartupController extends Controller
{
    /**
     * Display a listing of startups for investors to browse.
     */
    public function index(Request $request)
    {
        // Only show approved startups to investors
        $query = Startup::where('status', 'active');

        // 1. Search by Title or short description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // 2. Category Filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // 3. Funding Filter
        if ($request->filled('funding_min')) {
            $query->where('funding_goal', '>=', (float) $request->funding_min);
        }
        if ($request->filled('funding_max')) {
            $query->where('funding_goal', '<=', (float) $request->funding_max);
        }

        // 4. Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'most_funded') {
            $query->orderBy('current_funding', 'desc');
        } elseif ($sort === 'goal_highest') {
            $query->orderBy('funding_goal', 'desc');
        } else {
            $query->orderBy('created_at', 'desc'); // latest by default
        }

        // Fetch paginated results
        $startups = $query->paginate(12)->withQueryString();

        // Get unique categories for the dropdown filter
        $categories = Startup::pluck('category')->unique()->filter()->values();

        return view('investor.startups.index', compact('startups', 'categories'));
    }

    /**
     * Display the detailed view of a specific startup.
     */
    public function show($id)
    {
        $startup = Startup::with(['founder', 'comments.user', 'reviews.user'])->findOrFail($id);

        // Security check: restrict non-approved startups from investors
        if ($startup->status !== 'active') {
            abort(403, 'This startup has not been approved by the administrators yet.');
        }

        // Fetch real database metrics for the premium UI
        $mockData = [
            'equity_available' => ($startup->equity_offered ?? 10.0) . '%',
            'investor_count' => $startup->investments->unique('investor_id')->count(),
        ];

        return view('investor.startups.show', compact('startup', 'mockData'));
    }
}
