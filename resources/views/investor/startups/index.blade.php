@extends('layouts.investor')

@section('content')
<style>
    /* Filter Section Styles */
    .filter-container {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        opacity: 0;
        transform: translateY(20px);
    }
    
    .filter-container.animate-reveal {
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
        background-color: var(--input-bg);
        color: var(--text-color);
        font-family: inherit;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        align-items: end;
    }

    /* Startup Card Specific Styles */
    .startup-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .startup-banner {
        height: 120px;
        background-color: var(--border-color);
        border-radius: 0.75rem 0.75rem 0 0;
        margin: -1.5rem -1.5rem 1rem -1.5rem;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .startup-logo {
        width: 64px;
        height: 64px;
        border-radius: 0.75rem;
        background-color: var(--card-bg);
        border: 2px solid var(--card-bg);
        position: absolute;
        bottom: -32px;
        left: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: var(--text-muted);
    }

    .startup-header {
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .startup-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.25rem;
    }

    .startup-category {
        font-size: 0.875rem;
        color: var(--primary-color);
        background-color: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        display: inline-block;
        font-weight: 500;
    }

    .startup-description {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.5;
        flex-grow: 1;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .funding-progress-container {
        margin-bottom: 1.5rem;
    }

    .funding-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .progress-bar-bg {
        width: 100%;
        height: 8px;
        background-color: var(--border-color);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: 4px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
</style>

<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="page-title" style="margin-bottom: 0;">Browse Startups</h1>
    </div>

    <!-- Filter Section -->
    <div class="filter-container">
        <form action="{{ route('investor.startups.index') }}" method="GET" class="filter-grid">
            <div class="form-group">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search startups..." value="{{ request('search') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category" class="form-control">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Sort By</label>
                <select name="sort" class="form-control">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="most_funded" {{ request('sort') === 'most_funded' ? 'selected' : '' }}>Most Funded</option>
                    <option value="goal_highest" {{ request('sort') === 'goal_highest' ? 'selected' : '' }}>Highest Goal</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn" style="width: 100%;">
                    <i data-lucide="filter" style="width: 18px;"></i>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Startups Grid -->
    <div class="cards-grid">
        @forelse($startups as $startup)
            @php
                $progress = 0;
                if($startup->funding_goal > 0) {
                    $progress = min(100, ($startup->current_funding / $startup->funding_goal) * 100);
                }
            @endphp
            <div class="card startup-card">
                <div class="startup-banner" style="{{ $startup->banner ? 'background-image: url('.asset('storage/'.$startup->banner).');' : 'background: linear-gradient(to right, #34d399, #10b981);' }}">
                    @if($startup->logo)
                        <img src="{{ asset('storage/'.$startup->logo) }}" alt="{{ $startup->title }} Logo" class="startup-logo">
                    @else
                        <div class="startup-logo">{{ strtoupper(substr($startup->title, 0, 1)) }}</div>
                    @endif
                </div>
                
                <div class="startup-header">
                    <div class="startup-title">{{ $startup->title }}</div>
                    <div class="startup-category">{{ $startup->category ?? 'General' }}</div>
                </div>

                <div class="startup-description">
                    {{ $startup->short_description ?? 'No description provided for this startup.' }}
                </div>

                <div class="funding-progress-container">
                    <div class="funding-stats">
                        <span>${{ number_format($startup->current_funding ?? 0) }} raised</span>
                        <span style="color: var(--text-muted);">Goal: ${{ number_format($startup->funding_goal ?? 0) }}</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>

                <a href="{{ route('investor.startups.show', $startup->id) }}" class="btn" style="width: 100%; background-color: transparent; color: var(--primary-color); border: 1px solid var(--primary-color); margin-top: auto;">
                    View Details
                </a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: var(--card-bg); border-radius: 1rem; border: 1px solid var(--border-color);">
                <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">No Startups Found</h3>
                <p style="color: var(--text-muted);">Try adjusting your filters or search query.</p>
                <a href="{{ route('investor.startups.index') }}" class="btn" style="margin-top: 1rem;">Clear Filters</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $startups->links() }}
    </div>
</div>
@endsection
