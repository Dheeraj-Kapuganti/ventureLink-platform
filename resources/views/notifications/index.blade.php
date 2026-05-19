@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'investor' ? 'layouts.investor' : (auth()->user()->role === 'founder' ? 'layouts.founder' : 'layouts.app')))

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.5rem;">Notifications</h1>
            <p style="color: var(--text-muted);">Stay updated with your startup activity.</p>
        </div>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="btn" style="background: var(--input-bg); color: var(--text-color); box-shadow: none;">
                <i data-lucide="check-check" style="width: 18px; margin-right: 0.5rem;"></i> Mark all as read
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
        <div style="background-color: rgba(16, 185, 129, 0.1); color: var(--primary-color); border: 1px solid var(--primary-color); padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-weight: 500;">
            <i data-lucide="check-circle" style="width: 18px; margin-right: 0.5rem; vertical-align: text-bottom;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $isUnread = empty($notification->read_at);
            @endphp
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; gap: 1.5rem; align-items: flex-start; background-color: {{ $isUnread ? 'rgba(59, 130, 246, 0.05)' : 'transparent' }}; transition: background-color 0.2s;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: {{ $isUnread ? 'linear-gradient(135deg, #3b82f6, #8b5cf6)' : 'var(--input-bg)' }}; display: flex; align-items: center; justify-content: center; color: {{ $isUnread ? '#fff' : 'var(--text-muted)' }}; flex-shrink: 0;">
                    <i data-lucide="{{ $data['icon'] ?? 'bell' }}" style="width: 24px; height: 24px;"></i>
                </div>
                
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                        <h4 style="font-weight: 600; font-size: 1.1rem; color: {{ $isUnread ? 'var(--text-color)' : 'var(--text-muted)' }};">{{ $data['title'] ?? 'Notification' }}</h4>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">{{ $data['message'] ?? '' }}</p>
                    
                    <div style="display: flex; gap: 1rem;">
                        @if(isset($data['link']) && $data['link'] !== '#')
                            @php
                                $resolvedLink = $data['link'];
                                if (auth()->user()->role === 'founder' && str_contains($resolvedLink, '/investor-panel/startups/')) {
                                    $parts = explode('/', parse_url($resolvedLink, PHP_URL_PATH));
                                    $startupId = end($parts);
                                    $resolvedLink = route('startups.show', $startupId);
                                }
                            @endphp
                            <a href="{{ $resolvedLink }}" style="color: var(--primary-color); font-weight: 600; font-size: 0.875rem; text-decoration: none;">View Details</a>
                        @endif
                        
                        @if($isUnread)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: var(--text-muted); font-weight: 500; font-size: 0.875rem; cursor: pointer; padding: 0;">Mark as read</button>
                            </form>
                        @endif
                    </div>
                </div>
                
                @if($isUnread)
                    <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #3b82f6; margin-top: 1rem;"></div>
                @endif
            </div>
        @empty
            <div style="padding: 4rem 2rem; text-align: center;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--input-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; color: var(--text-muted);">
                    <i data-lucide="bell" style="width: 32px; height: 32px;"></i>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">All caught up!</h3>
                <p style="color: var(--text-muted);">You have no notifications at the moment.</p>
            </div>
        @endforelse
    </div>
    
    @if($notifications->hasPages())
        <div style="margin-top: 2rem;">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
