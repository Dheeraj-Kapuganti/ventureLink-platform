<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Startup;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class InvestorDashboardController extends Controller
{
    /**
     * Display the investor dashboard with real-time portfolio metrics.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Fetch Real Investment Data
        $investments = Investment::with('startup')
            ->where('investor_id', $userId)
            ->get();

        $totalInvested = $investments->sum('amount');
        $investmentCount = $investments->count();

        // 2. Portfolio Value Calculation
        $portfolioValue = 0;
        foreach ($investments as $inv) {
            $equityOffered = $inv->startup->equity_offered ?? 10.0;
            $valuation = $equityOffered > 0 ? ($inv->startup->funding_goal / ($equityOffered / 100)) : ($inv->startup->funding_goal * 5);
            $portfolioValue += ($inv->equity_percentage / 100) * $valuation;
        }

        // 3. Saved Startups Count
        $savedCount = Bookmark::where('user_id', $userId)->count();

        // 4. Pending Deals (Startups the user has invested in that are not yet 'active' status, if any)
        // For this simplified logic, we'll count investments in 'pending' or 'draft' startups
        $pendingDeals = $investments->filter(function($inv) {
            return $inv->startup->status !== 'active';
        })->count();

        // 5. Recent Activity (Recent Investments)
        $recentInvestments = Investment::with('startup')
            ->where('investor_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // 6. Recommended Startups (New startups the user hasn't invested in yet)
        $investedStartupIds = $investments->pluck('startup_id')->toArray();
        $opportunities = Startup::where('status', 'active')
            ->whereNotIn('_id', $investedStartupIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('investor.dashboard', compact(
            'totalInvested',
            'investmentCount',
            'portfolioValue',
            'savedCount',
            'pendingDeals',
            'recentInvestments',
            'opportunities'
        ));
    }
}
