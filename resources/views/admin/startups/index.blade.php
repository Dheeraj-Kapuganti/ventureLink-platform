@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="dashboard-header">
    <div>
        <h1>Startup Management</h1>
        <p>Review, audit, search, and manage listed startup organizations across the platform.</p>
    </div>
    
    <!-- Header Quick Actions -->
    <div>
        <x-admin.button variant="outline" size="sm" onclick="window.location.reload();">
            <i data-lucide="refresh-cw" style="width: 14px; height: 14px; margin-right: 4px;"></i> Reload List
        </x-admin.button>
    </div>
</div>

<!-- Alert Success/Error Messages -->
@if(session('success'))
    <div style="background-color: var(--success-bg); color: var(--success-color); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

<!-- Dynamic Advanced Search & Filters Control Bar -->
<div class="panel-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="{{ route('admin.startups.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr; gap: 1rem; align-items: end;" class="filters-form">
        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;" class="filters-inner-grid">
            <!-- Search Text Input -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="search" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Search Keywords</label>
                <div style="position: relative;">
                    <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search title, description..." style="width: 100%; padding: 0.55rem 0.75rem 0.55rem 2.25rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); transition: all 0.2s;">
                </div>
            </div>

            <!-- Status Dropdown Filter -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="status" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Filter by Status</label>
                <select name="status" id="status" style="width: 100%; padding: 0.55rem 0.75rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Active (Approved)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Category Dropdown Filter -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="category" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Filter by Category</label>
                <select name="category" id="category" style="width: 100%; padding: 0.55rem 0.75rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Filter Submit Actions -->
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
            <a href="{{ route('admin.startups.index') }}" style="text-decoration: none;">
                <x-admin.button type="button" variant="outline" size="sm">
                    Reset
                </x-admin.button>
            </a>
            <x-admin.button type="submit" variant="primary" size="sm">
                <i data-lucide="filter" style="width: 14px; height: 14px; margin-right: 4px;"></i> Apply Filters
            </x-admin.button>
        </div>
    </form>
</div>

<!-- Startups Database Listing Panel -->
<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title">
            <i data-lucide="list"></i> Listed Startups Database
        </h2>
        <x-admin.badge type="info">{{ $startups->count() }} matches</x-admin.badge>
    </div>

    @if($startups->count() > 0)
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Startup Details</th>
                        <th>Founder Details</th>
                        <th>Status</th>
                        <th>Funding Targets</th>
                        <th style="text-align: right;">Administrative Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($startups as $startup)
                        <tr>
                            <!-- Startup basic identifiers -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($startup->logo)
                                        <img src="{{ asset('storage/' . $startup->logo) }}" alt="" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover;">
                                    @else
                                        <div style="width: 38px; height: 38px; border-radius: 8px; background: var(--primary-glow); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem;">
                                            {{ strtoupper(substr($startup->title, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-color);">{{ $startup->title }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Category: {{ $startup->category }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Founder details -->
                            <td>
                                <div style="font-weight: 500;">{{ $startup->founder->name ?? 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $startup->founder->email ?? 'N/A' }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td>
                                @if($startup->status === 'active')
                                    <x-admin.badge type="success">Active</x-admin.badge>
                                @elseif($startup->status === 'pending')
                                    <x-admin.badge type="warning">Pending</x-admin.badge>
                                @elseif($startup->status === 'rejected')
                                    <x-admin.badge type="danger">Rejected</x-admin.badge>
                                @else
                                    <x-admin.badge type="info">{{ ucfirst($startup->status) }}</x-admin.badge>
                                @endif
                            </td>

                            <!-- Targets -->
                            <td>
                                <div style="font-weight: 600; color: var(--text-color);">${{ number_format($startup->funding_goal) }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Valuation: ${{ number_format($startup->valuation ?? 0) }}</div>
                            </td>

                            <!-- Actions -->
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <!-- View Details Trigger -->
                                    <x-admin.button type="button" variant="outline" size="sm" class="view-details-btn" 
                                        data-title="{{ $startup->title }}"
                                        data-description="{{ $startup->description }}"
                                        data-category="{{ $startup->category }}"
                                        data-stage="{{ $startup->startup_stage ?? 'Seed' }}"
                                        data-goal="{{ number_format($startup->funding_goal) }}"
                                        data-valuation="{{ number_format($startup->valuation ?? 0) }}"
                                        data-funding="{{ number_format($startup->current_funding ?? 0) }}"
                                        data-status="{{ ucfirst($startup->status === 'active' ? 'Active' : $startup->status) }}"
                                        data-founder-name="{{ $startup->founder->name ?? 'N/A' }}"
                                        data-founder-email="{{ $startup->founder->email ?? 'N/A' }}"
                                        data-deadline="{{ $startup->deadline ?? 'N/A' }}">
                                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i> Details
                                    </x-admin.button>

                                    <!-- Delete Secure Trigger Form -->
                                    <form action="{{ route('admin.startups.destroy', $startup->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this startup listed? All investments and feedback comments associated will be lost.');">
                                        @csrf
                                        @method('DELETE')
                                        <x-admin.button type="submit" variant="danger" size="sm">
                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Delete
                                        </x-admin.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty filter/search search state -->
        <div style="text-align: center; padding: 4rem 1.5rem;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background-color: var(--border-color); color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                <i data-lucide="alert-circle" style="width: 28px; height: 28px;"></i>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-color); margin-bottom: 0.35rem;">No Startups Found</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 320px; margin: 0 auto;">No startup matches the searched criteria or filter tags selected.</p>
        </div>
    @endif
</div>

<!-- ============================================ -->
<!-- Premium Glassmorphic Startup Details Modal -->
<!-- ============================================ -->
<div id="detailsModal" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; pointer-events: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); padding: 1.5rem;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); width: 100%; max-width: 600px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); transform: scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); display: flex; flex-direction: column;" id="modalBody">
        
        <!-- Modal Title Header -->
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i data-lucide="rocket" style="color: var(--primary-color); width: 22px; height: 22px;"></i>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-color); margin: 0;" id="modalTitle">Startup Details</h3>
            </div>
            <button id="closeModalBtn" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 6px; transition: all 0.2s;" onhover="this.style.color='var(--text-color)'">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <!-- Scrollable Description Panel Body -->
        <div style="padding: 1.5rem; overflow-y: auto; max-height: 60vh; display: flex; flex-direction: column; gap: 1.25rem;">
            
            <!-- Dynamic Grid Properties -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <!-- Category -->
                <div style="background-color: var(--bg-body); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Category</div>
                    <strong style="color: var(--text-color); font-size: 0.95rem;" id="modalCategory">N/A</strong>
                </div>
                <!-- Stage -->
                <div style="background-color: var(--bg-body); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Startup Stage</div>
                    <strong style="color: var(--text-color); font-size: 0.95rem;" id="modalStage">N/A</strong>
                </div>
                <!-- Target Goal -->
                <div style="background-color: var(--bg-body); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Funding Goal</div>
                    <strong style="color: var(--success-color); font-size: 1rem;" id="modalGoal">$0</strong>
                </div>
                <!-- Valuation -->
                <div style="background-color: var(--bg-body); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Valuation</div>
                    <strong style="color: var(--text-color); font-size: 1rem;" id="modalValuation">$0</strong>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h4 style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin: 0 0 0.5rem 0;">Description</h4>
                <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-color); margin: 0; background-color: var(--bg-body); padding: 1rem; border-radius: 10px; border: 1px solid var(--border-color);" id="modalDescription">
                    No description available.
                </p>
            </div>

            <!-- Founder Details Block -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <h4 style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin: 0 0 0.75rem 0;">Founder Information</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Name:</span>
                        <strong style="font-size: 0.85rem; color: var(--text-color); display: block;" id="modalFounderName">N/A</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Email:</span>
                        <strong style="font-size: 0.85rem; color: var(--text-color); display: block;" id="modalFounderEmail">N/A</strong>
                    </div>
                </div>
            </div>

            <!-- Health Status & Timelines -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Registration Status:</span>
                    <strong style="font-size: 0.9rem; display: block;" id="modalStatus">N/A</strong>
                </div>
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Deadline Target:</span>
                    <strong style="font-size: 0.9rem; color: var(--text-color); display: block;" id="modalDeadline">N/A</strong>
                </div>
            </div>
        </div>

        <!-- Footer actions -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; background-color: var(--bg-body); border-radius: 0 0 16px 16px;">
            <x-admin.button variant="primary" size="sm" id="modalCloseActionBtn">
                Close Details
            </x-admin.button>
        </div>
    </div>
</div>

<style>
/* CSS Media Queries for Advanced Responsive Filters Bar Grid */
@media (min-width: 768px) {
    .filters-form {
        grid-template-columns: 1fr auto;
    }
    .filters-inner-grid {
        grid-template-columns: 2fr 1fr 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Select modal handles
    const detailsModal = document.getElementById('detailsModal');
    const modalBody = document.getElementById('modalBody');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalCloseActionBtn = document.getElementById('modalCloseActionBtn');
    
    // Select element fields inside modal
    const modalTitle = document.getElementById('modalTitle');
    const modalCategory = document.getElementById('modalCategory');
    const modalStage = document.getElementById('modalStage');
    const modalGoal = document.getElementById('modalGoal');
    const modalValuation = document.getElementById('modalValuation');
    const modalDescription = document.getElementById('modalDescription');
    const modalFounderName = document.getElementById('modalFounderName');
    const modalFounderEmail = document.getElementById('modalFounderEmail');
    const modalStatus = document.getElementById('modalStatus');
    const modalDeadline = document.getElementById('modalDeadline');

    // Function to open details modal
    function openModal(data) {
        modalTitle.textContent = data.title;
        modalCategory.textContent = data.category;
        modalStage.textContent = data.stage;
        modalGoal.textContent = '$' + data.goal;
        modalValuation.textContent = '$' + data.valuation;
        modalDescription.textContent = data.description || 'No description provided.';
        modalFounderName.textContent = data.founderName;
        modalFounderEmail.textContent = data.founderEmail;
        modalStatus.textContent = data.status;
        modalDeadline.textContent = data.deadline;

        // Apply color styles to status placeholder inside modal
        if (data.status.toLowerCase() === 'active') {
            modalStatus.style.color = 'var(--success-color)';
        } else if (data.status.toLowerCase() === 'pending') {
            modalStatus.style.color = 'var(--warning-color)';
        } else {
            modalStatus.style.color = 'var(--danger-color)';
        }

        // Show Modal with smooth scale-up animation
        detailsModal.style.opacity = '1';
        detailsModal.style.pointerEvents = 'auto';
        modalBody.style.transform = 'scale(1)';
    }

    // Function to close details modal
    function closeModal() {
        detailsModal.style.opacity = '0';
        detailsModal.style.pointerEvents = 'none';
        modalBody.style.transform = 'scale(0.95)';
    }

    // Attach click events to eye buttons
    const viewButtons = document.querySelectorAll('.view-details-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const data = {
                title: btn.getAttribute('data-title'),
                description: btn.getAttribute('data-description'),
                category: btn.getAttribute('data-category'),
                stage: btn.getAttribute('data-stage'),
                goal: btn.getAttribute('data-goal'),
                valuation: btn.getAttribute('data-valuation'),
                status: btn.getAttribute('data-status'),
                founderName: btn.getAttribute('data-founder-name'),
                founderEmail: btn.getAttribute('data-founder-email'),
                deadline: btn.getAttribute('data-deadline')
            };
            openModal(data);
        });
    });

    // Close button triggers
    closeModalBtn.addEventListener('click', closeModal);
    modalCloseActionBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking outside contents on overlay
    detailsModal.addEventListener('click', (e) => {
        if (e.target === detailsModal) {
            closeModal();
        }
    });
});
</script>
@endsection
