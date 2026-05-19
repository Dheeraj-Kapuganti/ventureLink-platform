<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Startup;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    /**
     * Process an investment in a startup.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100', // Enforce minimum investment
        ]);

        $startup = Startup::findOrFail($id);

        // Security validation: only allow investments in approved (active) startups
        if ($startup->status !== 'active') {
            return back()->withErrors(['amount' => 'This startup has not been approved by the administrators yet. You can only invest in approved, live startups.'])->withInput();
        }

        $amount = (float) $request->amount;
        $remaining = $startup->funding_goal - $startup->current_funding;

        // Prevent overfunding
        if ($amount > $remaining) {
            return back()->withErrors(['amount' => 'Investment exceeds the remaining funding goal of $' . number_format($remaining)])->withInput();
        }

        // Calculate Equity based on Founder's Offered Equity
        $equityOffered = $startup->equity_offered ?? 10.0; // Fallback to 10% if not set on old records
        $equityPercentage = 0;
        if ($startup->funding_goal > 0) {
            $equityPercentage = ($amount / $startup->funding_goal) * $equityOffered;
        }

        // Create the Investment Document
        $investment = Investment::create([
            'startup_id' => $startup->id,
            'investor_id' => Auth::id(),
            'amount' => $amount,
            'equity_percentage' => $equityPercentage,
            'status' => 'completed',
        ]);

        // Increment the startup's current funding
        $startup->current_funding += $amount;
        $startup->save();
        
        // --- Notifications ---
        if ($startup->founder) {
            $startup->founder->notify(new \App\Notifications\NewInvestmentNotification($investment, $startup));
            
            // Check if goal reached exactly with this investment
            if ($startup->current_funding >= $startup->funding_goal && ($startup->current_funding - $amount) < $startup->funding_goal) {
                $startup->founder->notify(new \App\Notifications\FundingGoalReachedNotification($startup));
            }
        }

        // Notify all administrators in the system
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewInvestmentNotification($investment, $startup));
            
            // Check if goal reached exactly with this investment
            if ($startup->current_funding >= $startup->funding_goal && ($startup->current_funding - $amount) < $startup->funding_goal) {
                $admin->notify(new \App\Notifications\FundingGoalReachedNotification($startup));
            }
        }

        return redirect()->route('investor.startups.show', $startup->id)
            ->with('success', 'Successfully invested $' . number_format($amount) . ' for ' . number_format($equityPercentage, 2) . '% equity in ' . $startup->title . '!');
    }

    /**
     * Display the investor's portfolio (investment history).
     */
    public function history()
    {
        // Fetch all investments for the authenticated investor, eager load the related startup
        $investments = Investment::with('startup')
            ->where('investor_id', Auth::id())
            ->latest()
            ->get();
            
        // Calculate Portfolio Analytics
        $totalInvested = 0;
        $estimatedValue = 0;
        $categoryDataRaw = [];
        $timelineDataRaw = [];

        foreach ($investments as $inv) {
            $totalInvested += $inv->amount;

            // Estimated Value: (Equity % / 100) * Current Startup Valuation
            $equityOffered = $inv->startup->equity_offered ?? 10.0;
            $startupValuation = $equityOffered > 0 ? ($inv->startup->funding_goal / ($equityOffered / 100)) : ($inv->startup->funding_goal * 5);
            $estimatedValue += ($inv->equity_percentage / 100) * $startupValuation;

            // Category Allocation
            $category = $inv->startup->category ?? 'General';
            if (!isset($categoryDataRaw[$category])) {
                $categoryDataRaw[$category] = 0;
            }
            $categoryDataRaw[$category] += $inv->amount;

            // Timeline Allocation (Group by Month-Year)
            $monthYear = $inv->created_at->format('M Y');
            if (!isset($timelineDataRaw[$monthYear])) {
                $timelineDataRaw[$monthYear] = 0;
            }
            $timelineDataRaw[$monthYear] += $inv->amount;
        }

        // Format data for Chart.js
        $categoryData = [
            'labels' => array_keys($categoryDataRaw),
            'data' => array_values($categoryDataRaw)
        ];

        // Ensure timeline is sorted chronologically
        $timelineData = [
            'labels' => array_reverse(array_keys($timelineDataRaw)),
            'data' => array_reverse(array_values($timelineDataRaw))
        ];

        // Overall Average Equity
        $avgEquity = $investments->count() > 0 ? $investments->avg('equity_percentage') : 0;

        return view('investor.investments.history', compact(
            'investments', 
            'totalInvested', 
            'estimatedValue', 
            'avgEquity',
            'categoryData', 
            'timelineData'
        ));
    }
}
