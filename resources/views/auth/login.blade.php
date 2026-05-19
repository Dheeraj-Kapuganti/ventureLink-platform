@extends('layouts.app')

@section('content')
<div class="split-layout">
    <div class="split-image">
        <img src="{{ asset('images/fintech-bg.png') }}" alt="Fintech Graphic">
    </div>
    <div class="split-form">
        <div class="auth-container">
            <div class="brand-title">StartupPlatform</div>
    <h2>Welcome Back!</h2>
    <p class="subtitle">Please login to your account.</p>

    <!-- Display Validation Errors General -->
    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--danger-color); margin-bottom: 1rem;">
            <p style="color: var(--danger-color); font-size: 0.875rem;">Whoops! Something went wrong.</p>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
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

        <button type="submit" class="btn">Sign In</button>
    </form>

        <div class="auth-links">
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
        </div>
    </div>
    </div>
</div>
@endsection
