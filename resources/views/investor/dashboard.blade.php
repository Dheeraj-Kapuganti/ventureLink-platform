@extends('layouts.investor')

@section('content')
<div class="dashboard-interior">
    <div class="d-flex justify-content-between align-items-center mb-4" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.5rem; font-family: 'Space Grotesk', sans-serif; font-size: 2.5rem; font-weight: 800;">Investor Overview</h1>
            <p style="color: var(--text-muted);">Real-time performance of your startup portfolio.</p>
        </div>
        <a href="{{ route('investor.startups.index') }}" class="btn">
            <i data-lucide="plus" style="width: 18px; margin-right: 0.5rem;"></i>
            Explore New Deals
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="cards-grid">
        <div class="card">
            <div class="card-icon">
                <i data-lucide="wallet"></i>
            </div>
            <div class="card-title">Total Invested</div>
            <div class="card-value">${{ number_format($totalInvested) }}</div>
            <div class="trend-up">
                <i data-lucide="trending-up" style="width: 14px;"></i> Across {{ $investmentCount }} startups
            </div>
        </div>

        <div class="card">
            <div class="card-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i data-lucide="pie-chart"></i>
            </div>
            <div class="card-title">Portfolio Value</div>
            <div class="card-value">${{ number_format($portfolioValue) }}</div>
            <div class="trend-up" style="color: #3b82f6;">
                @php
                    $gain = $portfolioValue - $totalInvested;
                    $percent = $totalInvested > 0 ? ($gain / $totalInvested) * 100 : 0;
                @endphp
                <i data-lucide="arrow-up-right" style="width: 14px;"></i> {{ number_format($percent, 1) }}% ROI
            </div>
        </div>

        <div class="card">
            <div class="card-icon" style="background-color: rgba(168, 85, 247, 0.1); color: #a855f7;">
                <i data-lucide="bookmark"></i>
            </div>
            <div class="card-title">Saved Startups</div>
            <div class="card-value">{{ $savedCount }}</div>
            <div class="trend-up" style="color: #a855f7;">
                <i data-lucide="heart" style="width: 14px;"></i> Bookmarked deals
            </div>
        </div>

        <div class="card">
            <div class="card-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i data-lucide="clock"></i>
            </div>
            <div class="card-title">Pending Deals</div>
            <div class="card-value">{{ $pendingDeals }}</div>
            <div class="trend-up" style="color: #f59e0b;">
                <i data-lucide="alert-circle" style="width: 14px;"></i> Action required
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
        <!-- Left: Recent Opportunities -->
        <div class="table-container">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="sparkles" style="color: #f59e0b; width: 20px;"></i>
                Recommended Startups
            </h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Startup</th>
                            <th>Category</th>
                            <th>Funding Goal</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opportunities as $startup)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 6px; background: var(--input-bg); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if($startup->logo)
                                            <img src="{{ Storage::url($startup->logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i data-lucide="building-2" style="width: 16px; color: var(--text-muted);"></i>
                                        @endif
                                    </div>
                                    <span style="font-weight: 600;">{{ $startup->title }}</span>
                                </div>
                            </td>
                            <td><span style="font-size: 0.8rem; background: var(--input-bg); padding: 2px 8px; border-radius: 4px;">{{ $startup->category }}</span></td>
                            <td style="font-weight: 500;">${{ number_format($startup->funding_goal) }}</td>
                            <td><span class="status-badge status-active" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600;">{{ strtoupper($startup->status) }}</span></td>
                            <td>
                                <a href="{{ route('investor.startups.show', $startup->id) }}" style="color: var(--primary-color); text-decoration: none; font-weight: 600; font-size: 0.875rem;">View Deal</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                No new opportunities at the moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Recent Activity -->
        <div class="card" style="margin-top: 0;">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="activity" style="color: var(--primary-color); width: 20px;"></i>
                Recent Investments
            </h2>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @forelse($recentInvestments as $inv)
                <div style="display: flex; align-items: flex-start; gap: 1rem;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="check" style="width: 20px;"></i>
                    </div>
                    <div>
                        <p style="font-size: 0.9rem; font-weight: 600; margin: 0;">Invested ${{ number_format($inv->amount) }}</p>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin: 2px 0;">in {{ $inv->startup->title }}</p>
                        <span style="font-size: 0.7rem; color: var(--text-muted);">{{ $inv->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem 0; color: var(--text-muted);">
                    <p style="font-size: 0.875rem;">No recent investments.</p>
                </div>
                @endforelse
            </div>
            
            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">
            
            <a href="{{ route('investor.portfolio.index') }}" style="display: block; text-align: center; color: var(--primary-color); font-weight: 600; font-size: 0.875rem; text-decoration: none;">
                View Full Portfolio <i data-lucide="chevron-right" style="width: 16px; vertical-align: middle;"></i>
            </a>
        </div>
    </div>
</div>
@endsection
