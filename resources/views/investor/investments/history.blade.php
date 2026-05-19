@extends('layouts.investor')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .analytics-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-container {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        opacity: 0;
        transform: translateY(20px);
    }
    
    .chart-container.animate-reveal {
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="page-title" style="margin-bottom: 0;">Portfolio Analytics</h1>
    </div>

    <!-- Top Metrics -->
    <div class="cards-grid">
        <div class="card animate-reveal" style="animation-delay: 0.1s;">
            <div class="card-icon" style="background-color: rgba(16, 185, 129, 0.1); color: var(--primary-color);">
                <i data-lucide="wallet"></i>
            </div>
            <div class="card-title">Total Invested</div>
            <div class="card-value">${{ number_format($totalInvested) }}</div>
        </div>
        
        <div class="card animate-reveal" style="animation-delay: 0.2s;">
            <div class="card-icon" style="background-color: rgba(168, 85, 247, 0.1); color: #a855f7;">
                <i data-lucide="trending-up"></i>
            </div>
            <div class="card-title">Est. Portfolio Value</div>
            <div class="card-value">${{ number_format($estimatedValue) }}</div>
        </div>

        <div class="card animate-reveal" style="animation-delay: 0.3s;">
            <div class="card-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i data-lucide="briefcase"></i>
            </div>
            <div class="card-title">Startups Backed</div>
            <div class="card-value">{{ $investments->count() }}</div>
        </div>

        <div class="card animate-reveal" style="animation-delay: 0.4s;">
            <div class="card-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i data-lucide="pie-chart"></i>
            </div>
            <div class="card-title">Avg. Equity Stake</div>
            <div class="card-value">{{ number_format($avgEquity, 2) }}%</div>
        </div>
    </div>

    <!-- Charts Section -->
    @if($investments->count() > 0)
    <div class="analytics-grid">
        <!-- Category Allocation Doughnut -->
        <div class="chart-container animate-reveal" style="animation-delay: 0.5s;">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-color);">Allocation by Category</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Investment Velocity Line/Bar -->
        <div class="chart-container animate-reveal" style="animation-delay: 0.6s;">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-color);">Investment History ($)</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- History Table -->
    <div class="table-container animate-reveal" style="animation-delay: 0.7s; opacity: 0; transform: translateY(20px);">
        <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem;">Recent Transactions</h2>
        
        @if($investments->count() > 0)
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Startup</th>
                        <th>Amount</th>
                        <th>Equity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investments as $investment)
                    <tr>
                        <td style="color: var(--text-muted);">{{ $investment->created_at->format('M d, Y') }}</td>
                        <td style="font-weight: 500;">
                            <a href="{{ route('investor.startups.show', $investment->startup_id) }}" style="color: var(--text-color); text-decoration: none;">
                                {{ $investment->startup->title ?? 'Unknown Startup' }}
                            </a>
                        </td>
                        <td style="font-weight: 600; color: var(--primary-color);">${{ number_format($investment->amount) }}</td>
                        <td style="font-weight: 600; color: #a855f7;">{{ number_format($investment->equity_percentage ?? 0, 2) }}%</td>
                        <td><span class="status-badge status-active">{{ ucfirst($investment->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <i data-lucide="briefcase" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No investments yet.</h3>
            <p style="margin-top: 0.5rem;">Discover great startups and make your first investment today.</p>
            <a href="{{ route('investor.startups.index') }}" class="btn" style="margin-top: 1.5rem;">Browse Startups</a>
        </div>
        @endif
    </div>
</div>

@if($investments->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Premium Chart Colors based on our CSS variables
        const colors = [
            '#10b981', // Emerald
            '#3b82f6', // Blue
            '#8b5cf6', // Violet
            '#f59e0b', // Amber
            '#ef4444', // Red
            '#ec4899'  // Pink
        ];

        // Determine if dark mode is active for text colors
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDarkMode ? '#94a3b8' : '#475569';
        const gridColor = isDarkMode ? '#334155' : '#e2e8f0';

        // 1. Category Doughnut Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        const catData = {!! json_encode($categoryData) !!};
        
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catData.labels,
                datasets: [{
                    data: catData.data,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: textColor, padding: 20, font: { family: 'Inter', size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' $' + context.parsed.toLocaleString();
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // 2. Timeline Bar Chart
        const timeCtx = document.getElementById('timelineChart').getContext('2d');
        const timeData = {!! json_encode($timelineData) !!};

        // Create Gradient
        const gradient = timeCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.8)');   
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.2)');

        new Chart(timeCtx, {
            type: 'bar',
            data: {
                labels: timeData.labels,
                datasets: [{
                    label: 'Investment Volume',
                    data: timeData.data,
                    backgroundColor: gradient,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' $' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            color: textColor,
                            font: { family: 'Inter' },
                            callback: function(value) {
                                if (value >= 1000) { return '$' + value / 1000 + 'k'; }
                                return '$' + value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
