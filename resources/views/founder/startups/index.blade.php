@extends('layouts.founder')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.25rem;">My Startups</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Manage all your pitched projects from one central hub.</p>
        </div>
        <a href="{{ route('startups.create') }}" class="btn" style="width: auto; background-color: var(--primary-color);">
            <i data-lucide="plus" style="width: 18px; margin-right: 0.5rem; display: inline-block; vertical-align: text-bottom;"></i>
            Launch New
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid #10b981; margin-bottom: 1.5rem;">
            <p style="color: #10b981; font-size: 0.875rem;">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filtering & Search Bar -->
    <div style="background-color: var(--card-bg); padding: 1rem; border-radius: 0.75rem; border: 1px solid var(--border-color); margin-bottom: 2rem;">
        <form method="GET" action="{{ route('startups.index') }}" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            
            <!-- Search -->
            <div style="flex-grow: 1; min-width: 250px; position: relative;">
                <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.8rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
            </div>

            <!-- Status Filter -->
            <select name="status" style="padding: 0.6rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <!-- Sort By -->
            <select name="sort" style="padding: 0.6rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color); color: var(--text-color);">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Created</option>
                <option value="funding_highest" {{ request('sort') == 'funding_highest' ? 'selected' : '' }}>Most Funded</option>
                <option value="funding_lowest" {{ request('sort') == 'funding_lowest' ? 'selected' : '' }}>Least Funded</option>
            </select>

            <!-- Buttons -->
            <button type="submit" class="btn" style="width: auto; padding: 0.6rem 1.5rem; background-color: var(--primary-color);">Filter</button>
            @if(request()->anyFilled(['search', 'status', 'sort']))
                <a href="{{ route('startups.index') }}" style="color: var(--text-muted); font-size: 0.875rem; text-decoration: none;">Clear</a>
            @endif
        </form>
    </div>

    @if($startups->count() > 0)
        <div class="cards-grid">
            @foreach($startups as $startup)
                <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; position: relative; border-radius: 1rem;">
                    
                    <!-- Cover Banner Area -->
                    <div style="width: 100%; height: 120px; background-color: var(--input-bg); position: relative;">
                        @if($startup->banner)
                            <img src="{{ Storage::url($startup->banner) }}" alt="Banner" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width:100%; height:100%; background: linear-gradient(135deg, var(--input-bg), rgba(79, 70, 229, 0.1));"></div>
                        @endif

                        <!-- Status Badge Overlay -->
                        <div style="position: absolute; top: 1rem; right: 1rem; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; background: var(--bg-color); color: var(--text-color); box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @if($startup->status === 'approved')
                                <span style="color: #10b981;">● Approved</span>
                            @elseif($startup->status === 'rejected')
                                <span style="color: var(--danger-color);">● Rejected</span>
                            @else
                                <span style="color: #f59e0b;">● Pending</span>
                            @endif
                        </div>
                    </div>

                    <!-- Circular Offset Logo -->
                    <div style="width: 64px; height: 64px; border-radius: 50%; border: 4px solid var(--card-bg); background-color: var(--input-bg); position: absolute; top: 88px; left: 1.5rem; display: flex; justify-content: center; align-items: center; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); z-index: 10;">
                        @if($startup->logo)
                            <img src="{{ Storage::url($startup->logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i data-lucide="building" style="color: var(--text-muted); width: 24px;"></i>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div style="padding: 2.5rem 1.5rem 1.5rem 1.5rem; flex-grow: 1; display: flex; flex-direction: column;">
                        <div>
                            <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--text-color); letter-spacing: -0.5px; margin-bottom: 0.25rem;">{{ $startup->title }}</h3>
                            <p style="color: var(--primary-color); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1rem;">{{ $startup->category }}</p>
                        </div>

                        <p style="color: var(--text-muted); font-size: 0.875rem; line-height: 1.6; margin-bottom: 1.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $startup->description }}
                        </p>

                        <!-- Funding Component -->
                        <div style="margin-bottom: 1.5rem; flex-grow: 1;">
                            <x-funding-progress :startup="$startup" size="default" />
                        </div>

                        <!-- Buttons -->
                        <div style="display: flex; gap: 0.75rem; margin-top: auto; padding-top: 1.25rem; border-top: 1px solid var(--border-color);">
                            <a href="{{ route('startups.show', $startup->id) }}" class="btn" style="flex: 1; padding: 0.5rem; font-size: 0.85rem; background: var(--text-color); color: var(--bg-color); text-align: center; text-decoration: none; border-radius: 0.5rem; margin-top:0;">View Vault</a>
                            <a href="{{ route('startups.edit', $startup->id) }}" class="btn" style="flex: 1; padding: 0.5rem; font-size: 0.85rem; background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); text-align: center; text-decoration: none; border-radius: 0.5rem; margin-top:0;">Manage</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div style="width: 100%; padding: 4rem 2rem; background-color: var(--card-bg); border: 1px dashed var(--border-color); border-radius: 1rem; text-align: center;">
            <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--border-color); margin-bottom: 1rem; display: inline-block;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">No Startups Yet</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">You haven't launched any startups. Start by creating your first project.</p>
            <a href="{{ route('startups.create') }}" class="btn" style="width: auto; background-color: var(--primary-color);">Create First Startup</a>
        </div>
    @endif
</div>
@endsection
