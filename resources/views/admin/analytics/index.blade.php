@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="dashboard-header">
    <div>
        <h1>Platform Analytics</h1>
        <p>A comprehensive overview of capital inflow, user growth velocity, and startup ecosystem distribution.</p>
    </div>
    
    <!-- Header Quick Actions -->
    <div>
        <x-admin.button variant="outline" size="sm" onclick="window.location.reload();">
            <i data-lucide="refresh-cw" style="width: 14px; height: 14px; margin-right: 4px;"></i> Sync Real-time Data
        </x-admin.button>
    </div>
</div>

<!-- Load Chart.js from secure CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Advanced Chart Cards Grid -->
<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    
    <!-- 1. Monthly Funding Trends (Capital & Frequency) - Full Width Panel -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title">
                <i data-lucide="banknote" style="color: var(--success-color);"></i> Capital Accumulation & Deal Flow
            </h2>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">Monthly Audit</span>
        </div>
        <div style="position: relative; height: 350px; width: 100%;">
            <canvas id="fundingChart"></canvas>
        </div>
    </div>

    <!-- 2. Dual Column Growth & Category Allocations -->
    <div class="analytics-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;" class="responsive-analytics-grid">
        
        <!-- Startup & User Growth Trends -->
        <div class="panel-card">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i data-lucide="trending-up" style="color: var(--primary-color);"></i> Ecosystem Velocity Trends
                </h2>
                <x-admin.badge type="primary">Growth Tracker</x-admin.badge>
            </div>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Startup Category Distribution -->
        <div class="panel-card">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i data-lucide="pie-chart" style="color: var(--info-color);"></i> Market Vertical Allocation
                </h2>
                <x-admin.badge type="info">Category Share</x-admin.badge>
            </div>
            <div style="position: relative; height: 320px; width: 100%; display: flex; align-items: center; justify-content: center;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

    </div>
</div>

<style>
/* CSS Media Queries for Advanced Responsive Analytics Grids */
@media (min-width: 992px) {
    .analytics-grid {
        grid-template-columns: 1.2fr 0.8fr !important;
    }
}
</style>

<!-- Chart.js Render Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Read dynamic JSON datasets passed from the PHP controller
    const monthLabels = {!! $monthLabels !!};
    const fundingData = {!! $fundingTrends !!};
    const investmentCounts = {!! $investmentTrends !!};
    const startupCounts = {!! $startupGrowth !!};
    const userCounts = {!! $userGrowth !!};
    const catLabels = {!! $catLabels !!};
    const catCounts = {!! $catCounts !!};

    // Premium dynamic CSS styling colors helper
    const getCssVariable = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();

    // Redraw charts with correct styling theme context on dark/light changes
    const themeColors = {
        primary: 'rgba(99, 102, 241, 1)',      // Sleek neon indigo
        primaryGlow: 'rgba(99, 102, 241, 0.15)',
        success: 'rgba(16, 185, 129, 1)',      // Vibrant emerald
        successGlow: 'rgba(16, 185, 129, 0.15)',
        info: 'rgba(59, 130, 246, 1)',         // Sleek blue
        infoGlow: 'rgba(59, 130, 246, 0.15)',
        text: 'rgba(156, 163, 175, 1)',
        gridBorder: 'rgba(156, 163, 175, 0.1)'
    };

    // Chart.js Default configuration settings
    Chart.defaults.color = themeColors.text;
    Chart.defaults.font.family = "'Outfit', 'Inter', 'Roboto', sans-serif";

    // ----------------------------------------------------
    // Chart 1: Capital Accumulation & Deal Flow (Combined)
    // ----------------------------------------------------
    const ctxFunding = document.getElementById('fundingChart').getContext('2d');
    
    // Create soft neon emerald gradient for funding volume line fill
    const emeraldGradient = ctxFunding.createLinearGradient(0, 0, 0, 300);
    emeraldGradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
    emeraldGradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

    new Chart(ctxFunding, {
        type: 'line',
        data: {
            labels: monthLabels.length ? monthLabels : ['No Data Available'],
            datasets: [
                {
                    label: 'Funding Volume ($)',
                    data: fundingData.length ? fundingData : [0],
                    type: 'line',
                    borderColor: themeColors.success,
                    borderWidth: 3,
                    backgroundColor: emeraldGradient,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'yFunding',
                    pointBackgroundColor: themeColors.success,
                    pointBorderColor: '#fff',
                    pointHoverRadius: 7
                },
                {
                    label: 'Deals Completed (count)',
                    data: investmentCounts.length ? investmentCounts : [0],
                    type: 'bar',
                    backgroundColor: themeColors.primaryGlow,
                    borderColor: themeColors.primary,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    yAxisID: 'yCount',
                    barThickness: 32
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 14,
                        font: { size: 12, weight: 500 }
                    }
                },
                tooltip: {
                    padding: 12,
                    borderRadius: 10,
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    borderWidth: 1,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb'
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                yFunding: {
                    type: 'linear',
                    position: 'left',
                    grid: {
                        color: themeColors.gridBorder
                    },
                    ticks: {
                        callback: function(val) {
                            return '$' + val.toLocaleString();
                        }
                    }
                },
                yCount: {
                    type: 'linear',
                    position: 'right',
                    grid: { display: false },
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // ----------------------------------------------------
    // Chart 2: Ecosystem Growth Trends (Startups & Users)
    // ----------------------------------------------------
    const ctxGrowth = document.getElementById('growthChart').getContext('2d');
    
    new Chart(ctxGrowth, {
        type: 'line',
        data: {
            labels: monthLabels.length ? monthLabels : ['No Data Available'],
            datasets: [
                {
                    label: 'New Users Joined',
                    data: userCounts.length ? userCounts : [0],
                    borderColor: themeColors.primary,
                    borderWidth: 3,
                    backgroundColor: 'transparent',
                    tension: 0.35,
                    pointBackgroundColor: themeColors.primary,
                    pointHoverRadius: 6
                },
                {
                    label: 'Startups Listed',
                    data: startupCounts.length ? startupCounts : [0],
                    borderColor: themeColors.info,
                    borderWidth: 3,
                    backgroundColor: 'transparent',
                    tension: 0.35,
                    pointBackgroundColor: themeColors.info,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 14, font: { size: 12, weight: 500 } }
                },
                tooltip: {
                    padding: 12,
                    borderRadius: 10,
                    backgroundColor: 'rgba(17, 24, 39, 0.95)'
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    grid: { color: themeColors.gridBorder },
                    ticks: { stepSize: 1, precision: 0 }
                }
            }
        }
    });

    // ----------------------------------------------------
    // Chart 3: Startup Category Allocation (Pie/Doughnut)
    // ----------------------------------------------------
    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    
    // Sleek customized premium category color list (Hsl harmonious color tokens)
    const categoryColors = [
        'rgba(99, 102, 241, 0.85)',   // Indigo
        'rgba(16, 185, 129, 0.85)',   // Emerald
        'rgba(59, 130, 246, 0.85)',   // Blue
        'rgba(245, 158, 11, 0.85)',   // Amber
        'rgba(236, 72, 153, 0.85)',   // Pink
        'rgba(139, 92, 246, 0.85)',   // Purple
        'rgba(20, 184, 166, 0.85)'    // Teal
    ];

    const categoryBorders = [
        'rgba(99, 102, 241, 1)',
        'rgba(16, 185, 129, 1)',
        'rgba(59, 130, 246, 1)',
        'rgba(245, 158, 11, 1)',
        'rgba(236, 72, 153, 1)',
        'rgba(139, 92, 246, 1)',
        'rgba(20, 184, 166, 1)'
    ];

    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: catLabels.length ? catLabels : ['No Categories'],
            datasets: [{
                data: catCounts.length ? catCounts : [1],
                backgroundColor: catCounts.length ? categoryColors : ['rgba(156, 163, 175, 0.15)'],
                borderColor: catCounts.length ? categoryBorders : ['rgba(156, 163, 175, 0.3)'],
                borderWidth: 2,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 14,
                        font: { size: 11, weight: 500 }
                    }
                },
                tooltip: {
                    padding: 12,
                    borderRadius: 10,
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    callbacks: {
                        label: function(context) {
                            const val = context.raw;
                            return ` ${context.label}: ${val} startups`;
                        }
                    }
                }
            }
        }
    });

    // Theme Toggle reactive repaint logic
    const observer = new MutationObserver(() => {
        // Redetect grid and theme colors if changed
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const currentTextColor = isDark ? 'rgba(156, 163, 175, 1)' : 'rgba(75, 85, 99, 1)';
        const currentGridColor = isDark ? 'rgba(156, 163, 175, 0.1)' : 'rgba(75, 85, 99, 0.08)';

        Chart.instances.forEach(chart => {
            chart.options.scales?.x && (chart.options.scales.x.ticks.color = currentTextColor);
            chart.options.scales?.yFunding && (chart.options.scales.yFunding.ticks.color = currentTextColor);
            chart.options.scales?.yCount && (chart.options.scales.yCount.ticks.color = currentTextColor);
            chart.options.scales?.y && (chart.options.scales.y.ticks.color = currentTextColor);
            
            chart.options.scales?.yFunding && (chart.options.scales.yFunding.grid.color = currentGridColor);
            chart.options.scales?.y && (chart.options.scales.y.grid.color = currentGridColor);
            
            chart.update();
        });
    });

    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
});
</script>
@endsection
