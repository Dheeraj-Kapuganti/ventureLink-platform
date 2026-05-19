@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="brand-title" style="margin-bottom: 0;">StartupPlatform</div>
        
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn" style="width: auto; padding: 0.5rem 1.5rem; background-color: var(--danger-color);">Logout</button>
        </form>
    </div>

    <!-- Error Messaging (e.g. Role check fails) -->
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--danger-color); margin-bottom: 2rem;">
            <p style="color: var(--danger-color); font-size: 0.875rem;">{{ session('error') }}</p>
        </div>
    @endif

    <div class="card">
        <h2>Dashboard</h2>
        <p class="subtitle" style="text-align: left; margin-bottom: 1rem;">
            Welcome back, <strong>{{ Auth::user()->name }}</strong>! 
            <span class="role-badge">{{ Auth::user()->role }}</span>
        </p>

        <p style="color: var(--text-muted); font-size: 0.9rem;">
            Your account email is <strong>{{ Auth::user()->email }}</strong>.
        </p>
        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <h3>Role Protected Areas</h3>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.5rem;">
            Click on the links below to test the RoleMiddleware. You will only be able to access the panel that matches your Role Badge above.
        </p>

        <div class="links-grid">
            <a href="{{ route('founder.panel') }}" class="dashboard-btn-link" style="{{ Auth::user()->role == 'founder' ? 'border-color: var(--primary-color);' : '' }}">
                <strong style="color: var(--text-color);">Founder Panel</strong>
                <br>
                <span style="font-size: 0.75rem; color: var(--text-muted);">Requires 'founder' role</span>
            </a>
            
            <a href="{{ route('investor.panel') }}" class="dashboard-btn-link" style="{{ Auth::user()->role == 'investor' ? 'border-color: var(--primary-color);' : '' }}">
                <strong style="color: var(--text-color);">Investor Panel</strong>
                <br>
                <span style="font-size: 0.75rem; color: var(--text-muted);">Requires 'investor' role</span>
            </a>

            <a href="{{ route('admin.panel') }}" class="dashboard-btn-link" style="{{ Auth::user()->role == 'admin' ? 'border-color: var(--primary-color);' : '' }}">
                <strong style="color: var(--text-color);">Admin Panel</strong>
                <br>
                <span style="font-size: 0.75rem; color: var(--text-muted);">Requires 'admin' role</span>
            </a>
        </div>

    </div>
</div>
@endsection
