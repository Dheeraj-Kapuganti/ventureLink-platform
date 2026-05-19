@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="dashboard-header">
    <div>
        <h1>Investment Monitoring</h1>
        <p>Track global capital flow, platform funding progression, and transactional history across startups.</p>
    </div>
    
    <!-- Header Quick Actions -->
    <div>
        <x-admin.button variant="outline" size="sm" onclick="window.location.reload();">
            <i data-lucide="refresh-cw" style="width: 14px; height: 14px; margin-right: 4px;"></i> Refresh Metrics
        </x-admin.button>
    </div>
</div>

<!-- Dynamic Global Aggregates Cards Panel -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <!-- Total Platform Funding Card -->
    <div class="stat-card" style="position: relative; overflow: hidden;">
        <div class="stat-icon-container" style="background-color: var(--success-bg); color: var(--success-color);">
            <i data-lucide="banknote" style="width: 24px; height: 24px;"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Platform Funding</span>
            <span class="stat-value" style="color: var(--success-color);">${{ number_format($totalFunding) }}</span>
            <div class="stat-trend" style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.4rem; display: flex; align-items: center; gap: 4px;">
                <i data-lucide="activity" style="width: 12px; height: 12px; color: var(--primary-color);"></i> Active Capital Pool
            </div>
        </div>
        <div style="position: absolute; right: -20px; bottom: -20px; font-size: 6rem; opacity: 0.03; font-weight: 900; color: var(--text-color); pointer-events: none;">$</div>
    </div>

    <!-- Total Platform Transactions Card -->
    <div class="stat-card" style="position: relative; overflow: hidden;">
        <div class="stat-icon-container" style="background-color: var(--primary-glow); color: var(--primary-color);">
            <i data-lucide="arrow-up-right" style="width: 24px; height: 24px;"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Investments Logged</span>
            <span class="stat-value">{{ $totalCount }}</span>
            <div class="stat-trend" style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.4rem; display: flex; align-items: center; gap: 4px;">
                <i data-lucide="check-circle" style="width: 12px; height: 12px; color: var(--success-color);"></i> Verified Transactions
            </div>
        </div>
        <div style="position: absolute; right: -15px; bottom: -15px; font-size: 5.5rem; opacity: 0.03; font-weight: 900; color: var(--text-color); pointer-events: none;">
            <i data-lucide="trending-up" style="width: 100px; height: 100px;"></i>
        </div>
    </div>

    <!-- Average Ticket Size Card -->
    <div class="stat-card" style="position: relative; overflow: hidden;">
        <div class="stat-icon-container" style="background-color: var(--info-bg); color: var(--info-color);">
            <i data-lucide="calculator" style="width: 24px; height: 24px;"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Average Deal Size</span>
            <span class="stat-value">${{ number_format($totalCount > 0 ? ($totalFunding / $totalCount) : 0) }}</span>
            <div class="stat-trend" style="color: var(--text-muted); font-size: 0.8rem; margin-top: 0.4rem; display: flex; align-items: center; gap: 4px;">
                <i data-lucide="pie-chart" style="width: 12px; height: 12px; color: var(--info-color);"></i> Global Mean Value
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Advanced Search & Filters Control Bar -->
<div class="panel-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="{{ route('admin.investments.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr; gap: 1rem; align-items: end;" class="filters-form">
        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;" class="filters-inner-grid">
            <!-- Search Text Input -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="search" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Search Deals</label>
                <div style="position: relative;">
                    <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search startup, investor name or email..." style="width: 100%; padding: 0.55rem 0.75rem 0.55rem 2.25rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); transition: all 0.2s;">
                </div>
            </div>

            <!-- Minimum Amount Filter -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="min_amount" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Minimum Amount ($)</label>
                <div style="position: relative;">
                    <i data-lucide="dollar-sign" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                    <input type="number" name="min_amount" id="min_amount" value="{{ request('min_amount') }}" placeholder="Min. transaction size..." style="width: 100%; padding: 0.55rem 0.75rem 0.55rem 2.25rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); transition: all 0.2s;">
                </div>
            </div>
        </div>

        <!-- Filter Submit Actions -->
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
            <a href="{{ route('admin.investments.index') }}" style="text-decoration: none;">
                <x-admin.button type="button" variant="outline" size="sm">
                    Reset
                </x-admin.button>
            </a>
            <x-admin.button type="submit" variant="primary" size="sm">
                <i data-lucide="filter" style="width: 14px; height: 14px; margin-right: 4px;"></i> Apply Filters
            </x-admin.button>
        </div>
    </form>
</div>

<!-- Investments Database Listing Panel -->
<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title">
            <i data-lucide="history"></i> Investment History Audit
        </h2>
        <x-admin.badge type="info">{{ $investments->count() }} transactions found</x-admin.badge>
    </div>

    @if($investments->count() > 0)
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Startup Organization</th>
                        <th>Investor Details</th>
                        <th>Capital Invested</th>
                        <th>Equity Share</th>
                        <th>Investment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investments as $investment)
                        <tr>
                            <!-- Startup Relationship -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--primary-glow); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                        {{ strtoupper(substr($investment->startup->title ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-color);">{{ $investment->startup->title ?? 'Deleted Startup' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Stage: {{ $investment->startup->startup_stage ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Investor Relationship -->
                            <td>
                                <div style="font-weight: 500; color: var(--text-color);">{{ $investment->investor->name ?? 'Deleted Investor' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $investment->investor->email ?? 'N/A' }}</div>
                            </td>

                            <!-- Investment Size -->
                            <td>
                                <div style="font-weight: 700; color: var(--success-color);">${{ number_format($investment->amount) }}</div>
                            </td>

                            <!-- Equity Share -->
                            <td>
                                <x-admin.badge type="info">
                                    {{ $investment->equity_percentage ?? 0 }}% Equity
                                </x-admin.badge>
                            </td>

                            <!-- Date History -->
                            <td>
                                <div style="font-weight: 500; color: var(--text-color);">{{ $investment->created_at ? $investment->created_at->format('M d, Y') : 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $investment->created_at ? $investment->created_at->diffForHumans() : 'N/A' }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty filter/search search state -->
        <div style="text-align: center; padding: 4rem 1.5rem;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--border-color); color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <i data-lucide="wallet" style="width: 28px; height: 28px;"></i>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-color); margin-bottom: 0.35rem;">No Investments Logged</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 320px; margin: 0 auto;">No platform funding transaction matches the searched criteria or filter tags selected.</p>
        </div>
    @endif
</div>

<style>
/* CSS Media Queries for Advanced Responsive Filters Bar Grid */
@media (min-width: 768px) {
    .filters-form {
        grid-template-columns: 1fr auto;
    }
    .filters-inner-grid {
        grid-template-columns: 2fr 1fr;
    }
}
</style>
@endsection
