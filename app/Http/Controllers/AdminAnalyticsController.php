<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Startup;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    /**
     * Display the dynamic platform analytics panel with Chart.js configurations.
     */
    public function index()
    {
        // 1. Fetch all datasets from MongoDB
        $allInvestments = Investment::all();
        $allStartups = Startup::all();
        $allUsers = User::all();

        // 2. Perform robust MongoDB Eloquent date grouping at PHP collection level
        $fundingByMonth = $allInvestments->groupBy(function ($inv) {
            return $inv->created_at ? $inv->created_at->format('M Y') : 'Unknown';
        })->map(function ($group) {
            return [
                'total_amount' => $group->sum('amount'),
                'count' => $group->count()
            ];
        });

        $startupsByMonth = $allStartups->groupBy(function ($s) {
            return $s->created_at ? $s->created_at->format('M Y') : 'Unknown';
        })->map(function ($group) {
            return $group->count();
        });

        $usersByMonth = $allUsers->groupBy(function ($u) {
            return $u->created_at ? $u->created_at->format('M Y') : 'Unknown';
        })->map(function ($group) {
            return $group->count();
        });

        // 3. Extract unique chronological month sequences to populate horizontal chart axes
        $uniqueMonths = collect()
            ->merge($fundingByMonth->keys())
            ->merge($startupsByMonth->keys())
            ->merge($usersByMonth->keys())
            ->filter(fn($m) => $m !== 'Unknown')
            ->unique()
            ->sortBy(function ($monthStr) {
                return Carbon::parse($monthStr)->timestamp;
            })
            ->values()
            ->all();

        // 4. Align grouped metrics sequentially to prevent chronology gaps
        $fundingData = [];
        $investmentCounts = [];
        $startupCounts = [];
        $userCounts = [];

        foreach ($uniqueMonths as $month) {
            $fundingData[] = $fundingByMonth->get($month, ['total_amount' => 0])['total_amount'];
            $investmentCounts[] = $fundingByMonth->get($month, ['count' => 0])['count'];
            $startupCounts[] = $startupsByMonth->get($month, 0);
            $userCounts[] = $usersByMonth->get($month, 0);
        }

        // 5. Build category distribution metrics
        $categoryDistribution = $allStartups->groupBy('category')->map(function ($group) {
            return $group->count();
        });

        // 6. JSON Serialize all configurations for clean, static inline integration
        $monthLabels = json_encode($uniqueMonths);
        $fundingTrends = json_encode($fundingData);
        $investmentTrends = json_encode($investmentCounts);
        $startupGrowth = json_encode($startupCounts);
        $userGrowth = json_encode($userCounts);

        $catLabels = json_encode($categoryDistribution->keys()->all());
        $catCounts = json_encode($categoryDistribution->values()->all());

        return view('admin.analytics.index', compact(
            'monthLabels',
            'fundingTrends',
            'investmentTrends',
            'startupGrowth',
            'userGrowth',
            'catLabels',
            'catCounts'
        ));
    }
}
