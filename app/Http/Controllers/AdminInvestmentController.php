<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

class AdminInvestmentController extends Controller
{
    /**
     * Display all investments with search, filter, and platform statistics.
     */
    public function index(Request $request)
    {
        $query = Investment::with(['startup', 'investor']);

        // 1. Keyword search (by Startup title or Investor name/email)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('startup', function ($sq) use ($search) {
                    $sq->where('title', 'like', "%{$search}%");
                })->orWhereHas('investor', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // 2. Minimum amount filter
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', (int) $request->input('min_amount'));
        }

        $investments = $query->latest()->get();

        // Dynamically compute global database aggregates
        $totalFunding = Investment::sum('amount');
        $totalCount = Investment::count();

        return view('admin.investments.index', compact('investments', 'totalFunding', 'totalCount'));
    }
}
