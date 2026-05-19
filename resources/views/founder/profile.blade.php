@extends('layouts.founder')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.25rem;">Founder Profile</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Manage your personal details and platform security.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid #10b981; margin-bottom: 1.5rem;">
            <p style="color: #10b981; font-size: 0.875rem;">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--danger-color); margin-bottom: 1.5rem;">
            <ul style="color: var(--danger-color); font-size: 0.875rem; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="cards-grid" style="grid-template-columns: 1fr 2fr; gap: 2rem;">
        
        <!-- Left Side: Profile Picture and Summary -->
        <div class="card" style="display: flex; flex-direction: column; align-items: center; text-align: center; height: fit-content;">
            <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin-bottom: 1.5rem; border: 4px solid var(--border-color); background-color: var(--input-bg); display: flex; justify-content: center; align-items: center;">
                @if($user->profile_image)
                    <img src="{{ Storage::url($user->profile_image) }}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 3rem; color: var(--text-muted); font-weight: 600;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>

            <h3 style="font-size: 1.25rem; font-weight: 600;">{{ $user->name }}</h3>
            <p style="color: var(--primary-color); font-size: 0.875rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; margin-top: 0.25rem;">{{ $user->role }}</p>

            <hr style="width: 100%; border: 0; border-top: 1px dashed var(--border-color); margin: 1.5rem 0;">

            <div style="width: 100%; text-align: left; font-size: 0.875rem;">
                <p style="color: var(--text-muted); margin-bottom: 0.25rem;">Email Address</p>
                <p style="font-weight: 500; margin-bottom: 1rem;">{{ $user->email }}</p>

                <p style="color: var(--text-muted); margin-bottom: 0.25rem;">Member Since</p>
                <p style="font-weight: 500;">{{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        <!-- Right Side: Edit Form -->
        <div class="card">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">Personal Information</h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="name" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                    </div>

                    <div>
                        <label for="email" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                    </div>

                    <div style="grid-column: span 2;">
                        <label for="contact_info" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Contact Information (Phone / LinkedIn / Twitter)</label>
                        <input type="text" id="contact_info" name="contact_info" class="form-control" value="{{ old('contact_info', $user->contact_info) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" placeholder="e.g. linkedin.com/in/username">
                    </div>

                    <div style="grid-column: span 2;">
                        <label for="bio" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">About Me (Bio)</label>
                        <textarea id="bio" name="bio" class="form-control" rows="4" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" placeholder="Brief summary of your professional background...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div style="grid-column: span 2;">
                        <label for="profile_image" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Update Profile Avatar (Optional)</label>
                        <input type="file" id="profile_image" name="profile_image" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" accept="image/*">
                    </div>
                </div>

                <h2 style="font-size: 1.125rem; font-weight: 600; margin-top: 2.5rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">Security</h2>
                
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.5rem;">Leave these fields blank if you do not want to change your password.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label for="password" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);">
                    </div>

                    <div>
                        <label for="password_confirmation" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <button type="submit" class="btn" style="width: auto; padding: 0.75rem 2rem; background-color: var(--primary-color); color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 500;">
                        Save Profile Settings
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
