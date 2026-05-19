@extends('layouts.founder')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.25rem;">Edit: {{ $startup->title }}</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Update your startup details or replace your media files.</p>
        </div>
        <a href="{{ route('startups.show', $startup->id) }}" class="btn" style="width: auto; background-color: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color);">Cancel</a>
    </div>

    <div class="card" style="max-width: 800px;">
        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); padding: 1.5rem; border-radius: 0.5rem; border: 1px solid var(--danger-color); margin-bottom: 2rem;">
                <h4 style="color: var(--danger-color); margin-bottom: 0.5rem;">Please review the errors below:</h4>
                <ul style="color: var(--danger-color); font-size: 0.875rem; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('startups.update', $startup->id) }}" enctype="multipart/form-data" class="startup-form">
            @csrf
            @method('PUT')

            <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Title -->
                <div class="form-group span-2" style="grid-column: span 2;">
                    <label for="title" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Startup Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $startup->title) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                </div>

                <!-- Short Description -->
                <div class="form-group span-2" style="grid-column: span 2;">
                    <label for="short_description" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Short Description</label>
                    <input type="text" id="short_description" name="short_description" class="form-control" value="{{ old('short_description', $startup->short_description) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                </div>

                <!-- Funding & Category -->
                <div class="form-group">
                    <label for="funding_goal" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Funding Goal ($)</label>
                    <input type="number" id="funding_goal" name="funding_goal" class="form-control" value="{{ old('funding_goal', $startup->funding_goal) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                </div>

                <div class="form-group">
                    <label for="equity_offered" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Equity Offered (%)</label>
                    <input type="number" id="equity_offered" name="equity_offered" class="form-control" value="{{ old('equity_offered', $startup->equity_offered ?? 10) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" min="0.1" max="100" step="0.1" required>
                </div>

                <div class="form-group">
                    <label for="category" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Industry / Category</label>
                    <select id="category" name="category" class="form-control" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                        <option value="fintech" {{ old('category', $startup->category) == 'fintech' ? 'selected' : '' }}>Fintech</option>
                        <option value="healthtech" {{ old('category', $startup->category) == 'healthtech' ? 'selected' : '' }}>Healthtech</option>
                        <option value="ai" {{ old('category', $startup->category) == 'ai' ? 'selected' : '' }}>AI & Machine Learning</option>
                        <option value="ecommerce" {{ old('category', $startup->category) == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                        <option value="other" {{ old('category', $startup->category) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Stage and Deadline -->
                <div class="form-group">
                    <label for="startup_stage" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Current Stage</label>
                    <select id="startup_stage" name="startup_stage" class="form-control" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                        <option value="idea" {{ old('startup_stage', $startup->startup_stage) == 'idea' ? 'selected' : '' }}>Idea Phase</option>
                        <option value="mvp" {{ old('startup_stage', $startup->startup_stage) == 'mvp' ? 'selected' : '' }}>MVP / Prototype</option>
                        <option value="early_revenue" {{ old('startup_stage', $startup->startup_stage) == 'early_revenue' ? 'selected' : '' }}>Early Revenue</option>
                        <option value="growth" {{ old('startup_stage', $startup->startup_stage) == 'growth' ? 'selected' : '' }}>Growth / Scaling</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deadline" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Funding Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="form-control" value="{{ old('deadline', \Carbon\Carbon::parse($startup->deadline)->format('Y-m-d')) }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>
                </div>

                <!-- Full Description -->
                <div class="form-group span-2" style="grid-column: span 2;">
                    <label for="full_description" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Full Description</label>
                    <textarea id="full_description" name="full_description" class="form-control" rows="6" style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" required>{{ old('full_description', $startup->description) }}</textarea>
                </div>

                <!-- File Replacement (The Image requirements) -->
                <div class="form-group span-2" style="grid-column: span 2;">
                    <p style="font-size: 0.875rem; color: var(--text-color); margin-bottom: 1rem; font-weight: 500;">Replace Media (Leaves old file if blank)</p>
                </div>

                <div class="form-group">
                    <label for="logo" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Update Logo (Max 2MB)</label>
                    <input type="file" id="logo" name="logo" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" accept="image/*">
                    @if($startup->logo)
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.5rem;">Current: Attached</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="banner" style="display:block; margin-bottom:0.5rem; color:var(--text-muted); font-size:0.875rem;">Update Banner (Max 4MB)</label>
                    <input type="file" id="banner" name="banner" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--bg-color);" accept="image/*">
                    @if($startup->banner)
                        <span style="font-size: 0.75rem; color: var(--text-muted); display: block; margin-top: 0.5rem;">Current: Attached</span>
                    @endif
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
                <button type="submit" class="btn" style="width: auto; padding: 0.75rem 2rem; background-color: var(--primary-color); color: white; border: none; border-radius: 0.5rem; cursor: pointer;">
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Separate Delete Form to handle safe deletions -->
        <form method="POST" action="{{ route('startups.destroy', $startup->id) }}" style="margin-top: 2rem; border-top: 1px solid rgba(239, 68, 68, 0.2); padding-top: 2rem;" onsubmit="return confirm('Are you sure you want to permanently delete this startup? This action is irreversible and all uploaded media will be destroyed.');">
            @csrf
            @method('DELETE')
            <p style="color: var(--danger-color); font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Danger Zone</p>
            <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1rem;">Deleting this startup will destroy all associated public images securely from the server disk preventing orphaned files.</p>
            
            <button type="submit" class="btn" style="width: auto; background-color: rgba(239, 68, 68, 0.1); color: var(--danger-color); border: 1px solid var(--danger-color); padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer;">
                Force Delete Project
            </button>
        </form>
    </div>
</div>
@endsection
