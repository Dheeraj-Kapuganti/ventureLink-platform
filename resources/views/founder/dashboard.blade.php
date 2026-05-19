@extends('layouts.founder')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.5rem; font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 800;">Welcome, {{ Auth::user()->name }}!</h1>
            <p style="color: var(--text-muted);">Founder Dashboard &bull; Real-time performance metrics for your startups.</p>
        </div>
        <a href="{{ route('startups.create') }}" class="btn">
            <i data-lucide="rocket" style="width: 18px; margin-right: 0.5rem;"></i>
            Launch New Startup
        </a>
    </div>

    <!-- First row: Primary Metrics -->
    <div class="cards-grid">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <h3 class="card-title">Live Startups</h3>
                <div style="padding: 0.5rem; background-color: var(--input-bg); color: var(--text-muted); border-radius: 0.5rem;">
                    <i data-lucide="building"></i>
                </div>
            </div>
            <p class="card-value">{{ $totalStartups }}</p>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                {{ $approvedStartups }} Approved &bull; {{ $pendingStartups }} Pending
            </p>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <h3 class="card-title">Total Capital Raised</h3>
                <div style="padding: 0.5rem; background-color: rgba(16, 185, 129, 0.1); color: #10b981; border-radius: 0.5rem;">
                    <i data-lucide="banknote"></i>
                </div>
            </div>
            <p class="card-value">${{ number_format($totalFundingRaised) }}</p>
            <div style="width: 100%; background: var(--input-bg); height: 6px; border-radius: 3px; margin-top: 1rem; overflow: hidden;">
                @php
                    $progress = $totalGoal > 0 ? ($totalFundingRaised / $totalGoal) * 100 : 0;
                @endphp
                <div style="width: {{ min($progress, 100) }}%; background: #10b981; height: 100%;"></div>
            </div>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <h3 class="card-title">Active Investors</h3>
                <div style="padding: 0.5rem; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 0.5rem;">
                    <i data-lucide="users"></i>
                </div>
            </div>
            <p class="card-value">{{ $uniqueInvestorsCount }}</p>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                Unique backers across all deals
            </p>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <h3 class="card-title">Equity Distributed</h3>
                <div style="padding: 0.5rem; background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; border-radius: 0.5rem;">
                    <i data-lucide="pie-chart"></i>
                </div>
            </div>
            <p class="card-value">{{ number_format($totalEquityGiven, 1) }}%</p>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                Of total portfolio valuation
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
        <!-- Main Content Panel: Recent Activity -->
        <div class="card" style="margin-top: 0;">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="activity" style="color: var(--primary-color);"></i>
                Recent Startup Activity
            </h2>
            
            <div style="display: flex; flex-direction: column; gap: 0;">
                @forelse($recentActivity as $activity)
                    <div style="display: flex; align-items: flex-start; gap: 1rem; border-bottom: 1px solid var(--border-color); padding: 1.25rem 0;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--input-bg); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if($activity->logo)
                                <img src="{{ Storage::url($activity->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i data-lucide="rocket" style="width: 20px; color: var(--primary-color);"></i>
                            @endif
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <h4 style="font-weight: 600; font-size: 1rem; color: var(--text-color);">{{ $activity->title }}</h4>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $activity->updated_at->diffForHumans() }}</span>
                            </div>
                            <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.25rem;">
                                Status updated to <span style="color: {{ $activity->status === 'active' ? '#10b981' : ($activity->status === 'pending' ? '#f59e0b' : 'var(--text-muted)') }}; font-weight: 600; text-transform: uppercase; font-size: 0.75rem;">{{ $activity->status }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="inbox" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <p>No startups found. Start by launching your first idea!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Side: Mini Stats -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card" style="margin-top: 0; background: linear-gradient(135deg, var(--primary-color), #8b5cf6); color: white; border: none;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; opacity: 0.9;">Portfolio Valuation</h3>
                <p style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">${{ number_format($totalValuation) }}</p>
                <p style="font-size: 0.8rem; opacity: 0.8;">Combined post-money value of your startups</p>
            </div>

            <div class="card" style="margin-top: 0;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">Quick Links</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li>
                        <a href="{{ route('startups.index') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: var(--text-color); font-size: 0.9rem; font-weight: 500;">
                            <i data-lucide="list" style="width: 18px; color: var(--text-muted);"></i> Manage Startups
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifications.index') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: var(--text-color); font-size: 0.9rem; font-weight: 500;">
                            <i data-lucide="bell" style="width: 18px; color: var(--text-muted);"></i> Notifications
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: var(--text-color); font-size: 0.9rem; font-weight: 500;">
                            <i data-lucide="settings" style="width: 18px; color: var(--text-muted);"></i> Account Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
