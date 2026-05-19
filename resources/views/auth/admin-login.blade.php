@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="brand-title" style="background: linear-gradient(to right, #ef4444, #b91c1c); -webkit-background-clip: text;">Admin Portal</div>
    <h2>Restricted Access</h2>
    <p class="subtitle">Please login with your admin credentials.</p>

    <!-- Display Validation Errors General -->
    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--danger-color); margin-bottom: 1rem;">
            <p style="color: var(--danger-color); font-size: 0.875rem;">Whoops! Something went wrong.</p>
        </div>
    @endif

    <!-- Admin Login Form -->
    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div class="form-group">
            <label for="email">Admin Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn" style="background-color: var(--danger-color);">Admin Access</button>
    </form>

    <div class="auth-links">
        Not an admin? <a href="{{ route('login') }}" style="color: var(--danger-color);">Back to User Login</a>
    </div>
</div>
@endsection
