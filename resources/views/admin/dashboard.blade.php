@extends('layouts.admin')

@section('content')
<!-- Dashboard Top Header Row -->
<div class="dashboard-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, Admin. Real-time MongoDB metrics and platform activity logs.</p>
    </div>
    
    <!-- Header quick actions -->
    <div style="display: flex; gap: 0.75rem;">
        <x-admin.button variant="outline" size="sm" onclick="window.location.reload();">
            <i data-lucide="refresh-cw" style="width: 14px; height: 14px; margin-right: 4px;"></i> Refresh Feed
        </x-admin.button>
        <x-admin.button variant="primary" size="sm">
            <i data-lucide="download" style="width: 14px; height: 14px; margin-right: 4px;"></i> Export Metrics
        </x-admin.button>
    </div>
</div>

<!-- Financial and Account Statistics Grid (100% Real MongoDB Data) -->
<div class="stats-grid">
    <!-- Total capital invested across platform -->
    <x-admin.stat-card 
        title="Total Invested Amount" 
        value="${{ number_format($stats['total_funding_amount']) }}" 
        icon="banknote" 
        color="success" 
        trend="{{ number_format($stats['total_investments']) }}" 
        trendDirection="up" 
        trendLabel="investments made"
    />

    <!-- Startups approved and active -->
    <x-admin.stat-card 
        title="Approved Startups" 
        value="{{ number_format($stats['approved_startups']) }}" 
        icon="rocket" 
        color="primary" 
        trend="{{ number_format($stats['total_startups']) }}" 
        trendDirection="up" 
        trendLabel="total listed"
    />

    <!-- Total platform member accounts -->
    <x-admin.stat-card 
        title="Total Registered Users" 
        value="{{ number_format($stats['total_users']) }}" 
        icon="users" 
        color="info" 
        trend="{{ number_format($stats['total_founders']) }} F" 
        trendDirection="up" 
        trendLabel="& {{ number_format($stats['total_investors']) }} Investors"
    />

    <!-- Applications requiring immediate action -->
    <x-admin.stat-card 
        title="Pending Startups" 
        value="{{ number_format($stats['pending_startups']) }}" 
        icon="shield-alert" 
        color="warning" 
        trend="{{ number_format($stats['rejected_startups']) }}" 
        trendDirection="{{ $stats['pending_startups'] > 0 ? 'down' : 'up' }}" 
        trendLabel="applications rejected"
    />
</div>

<!-- Two-Column Primary Layout (Approvals & Activities) -->
<div class="dashboard-grid">
    
    <!-- Left Column: Pending Approvals list -->
    <div class="panel-card">
        <div class="panel-header">
            <h2 class="panel-title">
                <i data-lucide="clock"></i> Pending Applications
            </h2>
            <x-admin.badge type="warning">
                {{ $startups->count() }} Waiting
            </x-admin.badge>
        </div>

        @if(session('success'))
            <div style="background-color: var(--success-bg); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
                {{ session('success') }}
            </div>
        @endif

        @if($startups->count() > 0)
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Startup Name</th>
                            <th>Founder</th>
                            <th>Category</th>
                            <th>Funding Goal</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($startups as $startup)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        @if($startup->logo)
                                            <img src="{{ asset('storage/' . $startup->logo) }}" alt="" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
                                        @else
                                            <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-glow); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                                                {{ strtoupper(substr($startup->title, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div style="font-weight: 600;">{{ $startup->title }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">Stage: {{ $startup->startup_stage ?? 'Seed' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $startup->founder->name ?? 'Founder' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $startup->founder->email ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <x-admin.badge type="info">
                                        {{ $startup->category }}
                                    </x-admin.badge>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">${{ number_format($startup->funding_goal) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">Target</div>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                        <!-- Approve Form -->
                                        <form action="{{ route('admin.startups.approve', $startup->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <x-admin.button type="submit" variant="success" size="sm">
                                                <i data-lucide="check" style="width: 14px; height: 14px; margin-right: 2px;"></i> Approve
                                            </x-admin.button>
                                        </form>
                                        
                                        <!-- Reject Form -->
                                        <form action="{{ route('admin.startups.reject', $startup->id) }}" method="POST" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                            @csrf
                                            <input type="text" name="reason" placeholder="Reason (optional)" style="padding: 0.4rem 0.65rem; font-size: 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); max-width: 150px; transition: all 0.2s;" onfocus="this.style.borderColor='var(--danger-color)'" onblur="this.style.borderColor='var(--border-color)'">
                                            <x-admin.button type="submit" variant="danger" size="sm">
                                                <i data-lucide="x" style="width: 14px; height: 14px; margin-right: 2px;"></i> Reject
                                            </x-admin.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 4rem 1.5rem;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--success-bg); color: var(--success-color); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                    <i data-lucide="check-circle" style="width: 28px; height: 28px;"></i>
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-color); margin-bottom: 0.35rem;">All caught up!</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 320px; margin: 0 auto;">No pending startup application requests require review at this time.</p>
            </div>
        @endif
    </div>

    <!-- Right Column: Live MongoDB Metrics Summary Panel & Recent Onboarding timelines -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Premium MongoDB Metrics Hub -->
        <div class="panel-card">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i data-lucide="database"></i> MongoDB Metrics
                </h2>
                <x-admin.badge type="success">Dynamic Data</x-admin.badge>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.85rem; padding-top: 0.5rem;">
                <!-- Users Segment -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="users" style="width: 15px; height: 15px; color: var(--info-color);"></i> Total Users
                    </span>
                    <strong style="font-size: 1rem; color: var(--text-color);">{{ number_format($stats['total_users']) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; padding-left: 1.25rem;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">&bull; Total Founders</span>
                    <strong style="font-size: 0.9rem; color: var(--text-color);">{{ number_format($stats['total_founders']) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; padding-left: 1.25rem;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">&bull; Total Investors</span>
                    <strong style="font-size: 0.9rem; color: var(--text-color);">{{ number_format($stats['total_investors']) }}</strong>
                </div>
                
                <!-- Startups Segment -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="rocket" style="width: 15px; height: 15px; color: var(--primary-color);"></i> Total Startups
                    </span>
                    <strong style="font-size: 1rem; color: var(--text-color);">{{ number_format($stats['total_startups']) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; padding-left: 1.25rem;">
                    <span style="font-size: 0.85rem; color: var(--success-color);">&bull; Approved Startups</span>
                    <strong style="font-size: 0.9rem; color: var(--success-color);">{{ number_format($stats['approved_startups']) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; padding-left: 1.25rem;">
                    <span style="font-size: 0.85rem; color: var(--warning-color);">&bull; Pending Startups</span>
                    <strong style="font-size: 0.9rem; color: var(--warning-color);">{{ number_format($stats['pending_startups']) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; padding-left: 1.25rem;">
                    <span style="font-size: 0.85rem; color: var(--danger-color);">&bull; Rejected Startups</span>
                    <strong style="font-size: 0.9rem; color: var(--danger-color);">{{ number_format($stats['rejected_startups']) }}</strong>
                </div>
                
                <!-- Investments Segment -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="handshake" style="width: 15px; height: 15px; color: var(--success-color);"></i> Total Investments
                    </span>
                    <strong style="font-size: 1rem; color: var(--text-color);">{{ number_format($stats['total_investments']) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 0.25rem;">
                    <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="banknote" style="width: 15px; height: 15px; color: var(--success-color);"></i> Total Funding Amount
                    </span>
                    <strong style="font-size: 1.15rem; color: var(--success-color); font-weight: 700;">${{ number_format($stats['total_funding_amount']) }}</strong>
                </div>
            </div>
        </div>

        <!-- Recent Timelines -->
        <div class="panel-card">
            <div class="panel-header">
                <h2 class="panel-title">
                    <i data-lucide="activity"></i> Platform Activity
                </h2>
                <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">Live Feed</span>
            </div>
            
            <div class="activity-list">
                @php
                    $recentStartups = \App\Models\Startup::latest()->take(3)->get();
                    $recentUsers = \App\Models\User::latest()->take(3)->get();
                @endphp
                
                @if($recentStartups->count() > 0 || $recentUsers->count() > 0)
                    <!-- Loop through recent startups registered -->
                    @foreach($recentStartups as $rStartup)
                        <div class="activity-item">
                            <div class="activity-icon-container" style="background-color: var(--primary-glow); color: var(--primary-color);">
                                <i data-lucide="rocket" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    <strong>{{ $rStartup->title }}</strong> was listed by {{ $rStartup->founder->name ?? 'Founder' }}
                                </div>
                                <div class="activity-time" style="display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                                    <span>Status:</span>
                                    @if($rStartup->status === 'active')
                                        <x-admin.badge type="success">Active</x-admin.badge>
                                    @elseif($rStartup->status === 'pending')
                                        <x-admin.badge type="warning">Pending</x-admin.badge>
                                    @elseif($rStartup->status === 'rejected')
                                        <x-admin.badge type="danger">Rejected</x-admin.badge>
                                    @else
                                        <x-admin.badge type="info">{{ ucfirst($rStartup->status) }}</x-admin.badge>
                                    @endif
                                    <span>&bull; {{ $rStartup->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Loop through recent users registered -->
                    @foreach($recentUsers as $rUser)
                        <div class="activity-item">
                            <div class="activity-icon-container" style="background-color: var(--info-bg); color: var(--info-color);">
                                <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    <strong>{{ $rUser->name }}</strong> joined as a {{ ucfirst($rUser->role) }}
                                </div>
                                <div class="activity-time">
                                    Joined &bull; {{ $rUser->created_at ? $rUser->created_at->diffForHumans() : 'Just now' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 2rem 0; color: var(--text-muted); font-size: 0.9rem;">
                        <i data-lucide="database" style="width: 24px; height: 24px; margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto; opacity: 0.5;"></i>
                        No recent activity logged yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
