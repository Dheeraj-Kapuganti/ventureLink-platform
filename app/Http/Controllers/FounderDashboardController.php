<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Startup;
use Illuminate\Support\Facades\Auth;

class FounderDashboardController extends Controller
{
    /**
     * Display the founder dashboard home page with live metrics.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Fetch Startups for the founder
        $startups = Startup::where('founder_id', $userId)->get();
        $startupIds = $startups->pluck('id');

        // 2. Core Metrics
        $totalStartups = $startups->count();
        $totalFundingRaised = $startups->sum('current_funding');
        $totalGoal = $startups->sum('funding_goal');
        
        $pendingStartups = $startups->where('status', 'pending')->count();
        $approvedStartups = $startups->where('status', 'active')->count(); // 'active' is the approved status
        $rejectedStartups = $startups->where('status', 'rejected')->count();

        // 3. Investment Analytics
        $allInvestments = \App\Models\Investment::whereIn('startup_id', $startupIds)->get();
        $uniqueInvestorsCount = $allInvestments->unique('investor_id')->count();
        $totalEquityGiven = $allInvestments->sum('equity_percentage');

        // 4. Valuation Insights (Combined dynamic post-money valuations)
        $totalValuation = 0;
        foreach ($startups as $startup) {
            $equityOffered = $startup->equity_offered ?? 10.0;
            $valuation = $equityOffered > 0 ? ($startup->funding_goal / ($equityOffered / 100)) : ($startup->funding_goal * 5);
            $totalValuation += $valuation;
        }

        // 5. Recent Activity (Recent Comments, New Investments, or Startup Updates)
        // For now, let's stick to recent startup updates
        $recentActivity = Startup::where('founder_id', $userId)
                                ->orderBy('updated_at', 'desc')
                                ->take(5)
                                ->get();

        return view('founder.dashboard', compact(
            'totalStartups', 
            'totalFundingRaised', 
            'pendingStartups', 
            'approvedStartups',
            'rejectedStartups',
            'totalValuation',
            'uniqueInvestorsCount',
            'totalEquityGiven',
            'recentActivity',
            'totalGoal'
        ));
    }
}
