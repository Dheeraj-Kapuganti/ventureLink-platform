@extends('layouts.founder')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.25rem;">Create New Startup</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Submit your project for investor review.</p>
        </div>
        <a href="{{ route('founder.panel') }}" class="btn" style="width: auto; background-color: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color);">Cancel</a>
    </div>

    <div class="card" style="max-width: 800px;">
        <!-- Error Alerts -->
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

        <form method="POST" action="{{ route('startups.store') }}" enctype="multipart/form-data" class="startup-form">
            @csrf

            <!-- Form Grid -->
            <div class="form-grid">
                
                <!-- Title -->
                <div class="form-group span-2">
                    <label for="title">Startup Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Project Phoenix" required>
                </div>

                <!-- Short Description -->
                <div class="form-group span-2">
                    <label for="short_description">Short Description (Tagline)</label>
                    <input type="text" id="short_description" name="short_description" class="form-control" value="{{ old('short_description') }}" placeholder="Describe your mission in one sentence." required>
                </div>

                <!-- Funding & Category -->
                <div class="form-group">
                    <label for="funding_goal">Funding Goal ($)</label>
                    <input type="number" id="funding_goal" name="funding_goal" class="form-control" value="{{ old('funding_goal') }}" placeholder="e.g. 500000" min="0" required>
                </div>

                <div class="form-group">
                    <label for="equity_offered">Equity Offered (%)</label>
                    <input type="number" id="equity_offered" name="equity_offered" class="form-control" value="{{ old('equity_offered') }}" placeholder="e.g. 10" min="0.1" max="100" step="0.1" required>
                </div>

                <div class="form-group">
                    <label for="category">Industry / Category</label>
                    <select id="category" name="category" class="form-control" required style="cursor: pointer; appearance: auto;">
                        <option value="" disabled selected>Select Category</option>
                        <option value="fintech" {{ old('category') == 'fintech' ? 'selected' : '' }}>Fintech</option>
                        <option value="healthtech" {{ old('category') == 'healthtech' ? 'selected' : '' }}>Healthtech</option>
                        <option value="ai" {{ old('category') == 'ai' ? 'selected' : '' }}>AI & Machine Learning</option>
                        <option value="ecommerce" {{ old('category') == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Stage and Deadline -->
                <div class="form-group">
                    <label for="startup_stage">Current Stage</label>
                    <select id="startup_stage" name="startup_stage" class="form-control" required style="cursor: pointer; appearance: auto;">
                        <option value="" disabled selected>Select Stage</option>
                        <option value="idea" {{ old('startup_stage') == 'idea' ? 'selected' : '' }}>Idea Phase</option>
                        <option value="mvp" {{ old('startup_stage') == 'mvp' ? 'selected' : '' }}>MVP / Prototype</option>
                        <option value="early_revenue" {{ old('startup_stage') == 'early_revenue' ? 'selected' : '' }}>Early Revenue</option>
                        <option value="growth" {{ old('startup_stage') == 'growth' ? 'selected' : '' }}>Growth / Scaling</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deadline">Funding Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="form-control" value="{{ old('deadline') }}" required>
                </div>

                <!-- Full Description -->
                <div class="form-group span-2">
                    <label for="full_description">Full Description & Pitch</label>
                    <textarea id="full_description" name="full_description" class="form-control" rows="6" placeholder="Explain the problem, your solution, and why investors should care..." required>{{ old('full_description') }}</textarea>
                </div>

                <!-- File Uploads -->
                <div class="form-group">
                    <label for="logo">Startup Logo (Optional)</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="logo" name="logo" class="file-input" accept="image/*">
                        <div class="file-upload-visual">
                            <i data-lucide="image" style="width: 24px; color: var(--text-muted); margin-bottom: 0.5rem;"></i>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">Choose Logo Image</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="banner">Cover Banner (Optional)</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="banner" name="banner" class="file-input" accept="image/*">
                        <div class="file-upload-visual">
                            <i data-lucide="monitor" style="width: 24px; color: var(--text-muted); margin-bottom: 0.5rem;"></i>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">Choose Banner Image</span>
                        </div>
                    </div>
                </div>

            </div>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
                <button type="submit" class="btn" style="width: auto; padding: 0.75rem 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="rocket" style="width: 18px;"></i> Launch Startup
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Scoped Form Specific Styles */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.span-2 {
    grid-column: span 2;
}

@media (max-width: 640px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .span-2 {
        grid-column: span 1;
    }
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    background-color: var(--bg-color);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    color: var(--text-color);
    font-family: inherit;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
}

/* File Upload Custom Design */
.file-upload-wrapper {
    position: relative;
    width: 100%;
    height: 120px;
}

.file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}

.file-upload-visual {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border: 2px dashed var(--border-color);
    border-radius: 0.5rem;
    background-color: var(--bg-color);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: all 0.2s;
    z-index: 5;
}

.file-input:hover + .file-upload-visual {
    border-color: var(--primary-color);
    background-color: rgba(79, 70, 229, 0.05);
}
</style>
@endsection
