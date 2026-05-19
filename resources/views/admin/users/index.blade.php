@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="dashboard-header">
    <div>
        <h1>User Management</h1>
        <p>Audit, search, filter, block, and manage registered system accounts.</p>
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

@if(session('error'))
    <div style="background-color: var(--danger-bg); color: var(--danger-color); border: 1px solid rgba(239, 68, 68, 0.2); padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;">
        {{ session('error') }}
    </div>
@endif

<!-- Dynamic Advanced Search & Filters Control Bar -->
<div class="panel-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="{{ route('admin.users.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr; gap: 1rem; align-items: end;" class="filters-form">
        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;" class="filters-inner-grid">
            <!-- Search Text Input -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="search" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Search Users</label>
                <div style="position: relative;">
                    <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted);"></i>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search name, email address..." style="width: 100%; padding: 0.55rem 0.75rem 0.55rem 2.25rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); transition: all 0.2s;">
                </div>
            </div>

            <!-- Role Dropdown Filter -->
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <label for="role" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Filter by Role</label>
                <select name="role" id="role" style="width: 100%; padding: 0.55rem 0.75rem; font-size: 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                    <option value="">All Roles</option>
                    <option value="founder" {{ request('role') === 'founder' ? 'selected' : '' }}>Founder</option>
                    <option value="investor" {{ request('role') === 'investor' ? 'selected' : '' }}>Investor</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Platform Admin</option>
                </select>
            </div>
        </div>

        <!-- Filter Submit Actions -->
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
            <a href="{{ route('admin.users.index') }}" style="text-decoration: none;">
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

<!-- Users Database Listing Panel -->
<div class="panel-card">
    <div class="panel-header">
        <h2 class="panel-title">
            <i data-lucide="users"></i> System Accounts Registry
        </h2>
        <x-admin.badge type="info">{{ $users->count() }} registered</x-admin.badge>
    </div>

    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>User Name & Profile</th>
                        <th>Email Address</th>
                        <th>System Role</th>
                        <th>Account Status</th>
                        <th style="text-align: right;">Administrative Controls</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <!-- User basic profile -->
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary-glow); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-color);">{{ $user->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Joined: {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email address -->
                            <td>
                                <div style="font-weight: 500;">{{ $user->email }}</div>
                            </td>

                            <!-- Role Badge -->
                            <td>
                                @if($user->role === 'admin')
                                    <x-admin.badge type="danger">Admin</x-admin.badge>
                                @elseif($user->role === 'founder')
                                    <x-admin.badge type="primary">Founder</x-admin.badge>
                                @else
                                    <x-admin.badge type="info">Investor</x-admin.badge>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td>
                                @if($user->status === 'blocked')
                                    <x-admin.badge type="danger">Blocked</x-admin.badge>
                                @else
                                    <x-admin.badge type="success">Active</x-admin.badge>
                                @endif
                            </td>

                            <!-- Control actions -->
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                    <!-- View Details Trigger -->
                                    <x-admin.button type="button" variant="outline" size="sm" class="view-user-details-btn" 
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ ucfirst($user->role) }}"
                                        data-status="{{ $user->status === 'blocked' ? 'Blocked' : 'Active' }}"
                                        data-bio="{{ $user->bio ?? 'No biography details provided.' }}"
                                        data-contact="{{ $user->contact_info ?? 'No direct contact info provided.' }}"
                                        data-joined="{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}">
                                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i> Details
                                    </x-admin.button>

                                    <!-- Block/Unblock toggle form -->
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.toggle_block', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <x-admin.button type="submit" variant="{{ $user->status === 'blocked' ? 'success' : 'warning' }}" size="sm">
                                                @if($user->status === 'blocked')
                                                    <i data-lucide="unlock" style="width: 14px; height: 14px;"></i> Unblock
                                                @else
                                                    <i data-lucide="ban" style="width: 14px; height: 14px;"></i> Block
                                                @endif
                                            </x-admin.button>
                                        </form>

                                        <!-- Secure Delete Trigger -->
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this user? ALL startups created (if founder) or portfolio investments (if investor) associated will be permanently wiped out.');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <x-admin.button type="submit" variant="danger" size="sm">
                                                <i data-lucide="user-x" style="width: 14px; height: 14px;"></i> Delete
                                            </x-admin.button>
                                        </form>
                                    @else
                                        <!-- Self accounts tag -->
                                        <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic; padding-right: 0.5rem;">Self Account</span>
                                    @endif
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
                <i data-lucide="user-x" style="width: 28px; height: 28px;"></i>
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-color); margin-bottom: 0.35rem;">No Users Found</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; max-width: 320px; margin: 0 auto;">No platform accounts match the searched criteria or role filter tags selected.</p>
        </div>
    @endif
</div>

<!-- ============================================ -->
<!-- Premium Glassmorphic User Details Modal -->
<!-- ============================================ -->
<div id="userDetailsModal" style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; pointer-events: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); padding: 1.5rem;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); width: 100%; max-width: 500px; border-radius: 16px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); transform: scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); display: flex; flex-direction: column;" id="modalBody">
        
        <!-- Modal Title Header -->
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <i data-lucide="user" style="color: var(--primary-color); width: 22px; height: 22px;"></i>
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-color); margin: 0;" id="modalTitle">Account Profile Details</h3>
            </div>
            <button id="closeModalBtn" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 6px; transition: all 0.2s;" onhover="this.style.color='var(--text-color)'">
                <i data-lucide="x" style="width: 20px; height: 20px;"></i>
            </button>
        </div>

        <!-- Scrollable Description Panel Body -->
        <div style="padding: 1.5rem; overflow-y: auto; max-height: 65vh; display: flex; flex-direction: column; gap: 1.25rem;">
            
            <!-- Basic User Identifiers -->
            <div style="display: flex; flex-direction: column; gap: 0.35rem; text-align: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary-glow); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.8rem; margin: 0 auto 0.5rem auto;" id="modalInitial">
                    U
                </div>
                <strong style="font-size: 1.25rem; color: var(--text-color);" id="modalName">N/A</strong>
                <span style="font-size: 0.85rem; color: var(--text-muted);" id="modalEmail">N/A</span>
            </div>

            <!-- Dynamic Grid Properties -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <!-- System Role -->
                <div style="background-color: var(--bg-body); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">System Role</div>
                    <strong style="color: var(--text-color); font-size: 0.95rem;" id="modalRole">N/A</strong>
                </div>
                <!-- Status -->
                <div style="background-color: var(--bg-body); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase;">Account Status</div>
                    <strong style="font-size: 0.95rem;" id="modalStatus">N/A</strong>
                </div>
            </div>

            <!-- User Biography -->
            <div>
                <h4 style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin: 0 0 0.5rem 0;">Biography Info</h4>
                <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-color); margin: 0; background-color: var(--bg-body); padding: 1rem; border-radius: 10px; border: 1px solid var(--border-color);" id="modalBio">
                    No bio available.
                </p>
            </div>

            <!-- Direct Contact Detail Block -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <h4 style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin: 0 0 0.5rem 0;">Contact Details</h4>
                <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-color); margin: 0; background-color: var(--bg-body); padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);" id="modalContact">
                    No contact details.
                </p>
            </div>

            <!-- Health Status & Timelines -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Platform Join Date:</span>
                <strong style="font-size: 0.85rem; color: var(--text-color);" id="modalJoined">N/A</strong>
            </div>
        </div>

        <!-- Footer actions -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; background-color: var(--bg-body); border-radius: 0 0 16px 16px;">
            <x-admin.button variant="primary" size="sm" id="modalCloseActionBtn">
                Close Profile
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
        grid-template-columns: 3fr 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Select modal handles
    const userDetailsModal = document.getElementById('userDetailsModal');
    const modalBody = document.getElementById('modalBody');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modalCloseActionBtn = document.getElementById('modalCloseActionBtn');
    
    // Select element fields inside modal
    const modalInitial = document.getElementById('modalInitial');
    const modalName = document.getElementById('modalName');
    const modalEmail = document.getElementById('modalEmail');
    const modalRole = document.getElementById('modalRole');
    const modalStatus = document.getElementById('modalStatus');
    const modalBio = document.getElementById('modalBio');
    const modalContact = document.getElementById('modalContact');
    const modalJoined = document.getElementById('modalJoined');

    // Function to open details modal
    function openModal(data) {
        modalInitial.textContent = data.name.charAt(0).toUpperCase();
        modalName.textContent = data.name;
        modalEmail.textContent = data.email;
        modalRole.textContent = data.role;
        modalStatus.textContent = data.status;
        modalBio.textContent = data.bio;
        modalContact.textContent = data.contact;
        modalJoined.textContent = data.joined;

        // Apply color styles to status placeholder inside modal
        if (data.status.toLowerCase() === 'active') {
            modalStatus.style.color = 'var(--success-color)';
        } else {
            modalStatus.style.color = 'var(--danger-color)';
        }

        // Show Modal with smooth scale-up animation
        userDetailsModal.style.opacity = '1';
        userDetailsModal.style.pointerEvents = 'auto';
        modalBody.style.transform = 'scale(1)';
    }

    // Function to close details modal
    function closeModal() {
        userDetailsModal.style.opacity = '0';
        userDetailsModal.style.pointerEvents = 'none';
        modalBody.style.transform = 'scale(0.95)';
    }

    // Attach click events to eye buttons
    const viewButtons = document.querySelectorAll('.view-user-details-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const data = {
                name: btn.getAttribute('data-name'),
                email: btn.getAttribute('data-email'),
                role: btn.getAttribute('data-role'),
                status: btn.getAttribute('data-status'),
                bio: btn.getAttribute('data-bio'),
                contact: btn.getAttribute('data-contact'),
                joined: btn.getAttribute('data-joined')
            };
            openModal(data);
        });
    });

    // Close button triggers
    closeModalBtn.addEventListener('click', closeModal);
    modalCloseActionBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking outside contents on overlay
    userDetailsModal.addEventListener('click', (e) => {
        if (e.target === userDetailsModal) {
            closeModal();
        }
    });
});
</script>
@endsection
