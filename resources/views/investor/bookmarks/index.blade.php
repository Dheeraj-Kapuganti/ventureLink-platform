@extends('layouts.investor')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.5rem;">Saved Startups</h1>
            <p style="color: var(--text-muted);">Startups you've bookmarked for later consideration.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background-color: rgba(16, 185, 129, 0.1); color: var(--primary-color); border: 1px solid var(--primary-color); padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-weight: 500;">
            <i data-lucide="check-circle" style="width: 18px; margin-right: 0.5rem; vertical-align: text-bottom;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($startups->count() > 0)
        <div class="cards-grid">
            @foreach($startups as $index => $startup)
                <div class="card startup-card animate-reveal" style="animation-delay: {{ $index * 0.1 }}s; padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 120px; background: linear-gradient(135deg, rgba(16,185,129,0.2) 0%, rgba(59,130,246,0.2) 100%); position: relative;">
                        <div style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem;">
                            <span class="status-badge status-active">{{ $startup->category }}</span>
                        </div>
                    </div>
                    <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $startup->title }}</h3>
                        <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem; flex: 1;">
                            {{ Str::limit($startup->short_description ?? $startup->description, 80) }}
                        </p>
                        
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                <span style="color: var(--text-muted);">Raised</span>
                                <span style="font-weight: 600; color: var(--primary-color);">${{ number_format($startup->current_funding) }}</span>
                            </div>
                            <div class="progress-bar">
                                @php
                                    $progress = $startup->funding_goal > 0 ? min(100, ($startup->current_funding / $startup->funding_goal) * 100) : 0;
                                @endphp
                                <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-top: 0.5rem; color: var(--text-muted);">
                                <span>Goal: ${{ number_format($startup->funding_goal) }}</span>
                                <span>{{ number_format($progress, 1) }}%</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('investor.startups.show', $startup->id) }}" class="btn" style="flex: 1; text-align: center;">View Details</a>
                            <form action="{{ route('investor.bookmarks.toggle', $startup->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid #ef4444; box-shadow: none; padding: 0.5rem 1rem;" title="Remove Bookmark">
                                    <i data-lucide="bookmark-minus" style="width: 18px; margin: 0;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card" style="text-align: center; padding: 4rem 2rem;">
            <i data-lucide="bookmark" style="width: 64px; height: 64px; margin: 0 auto 1.5rem auto; opacity: 0.2;"></i>
            <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">No Saved Startups</h2>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">You haven't bookmarked any startups yet. Explore active startups and save your favorites here.</p>
            <a href="{{ route('investor.startups.index') }}" class="btn" style="display: inline-block;">Browse Startups</a>
        </div>
    @endif
</div>
@endsection
