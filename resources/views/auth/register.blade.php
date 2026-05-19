@extends('layouts.app')

@section('content')
<div class="split-layout">
    <div class="split-image">
        <img src="{{ asset('images/fintech-bg.png') }}" alt="Fintech Graphic">
    </div>
    <div class="split-form">
        <div class="auth-container">
            <div class="brand-title">StartupPlatform</div>
    <h2>Create an Account</h2>
    <p class="subtitle">Join as a Founder or Investor.</p>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">I want to join as a:</label>
            <select id="role" name="role" class="form-control" required>
                <option value="founder" {{ old('role') == 'founder' ? 'selected' : '' }}>Founder</option>
                <option value="investor" {{ old('role') == 'investor' ? 'selected' : '' }}>Investor</option>
            </select>
            @error('role')
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

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn">Sign Up</button>
    </form>

        <div class="auth-links">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
    </div>
</div>
@endsection
