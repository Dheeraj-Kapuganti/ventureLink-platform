@extends('layouts.founder')

@section('content')
<div class="dashboard-interior">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title" style="margin-bottom: 0.25rem;">{{ $startup->title }}</h1>
            
            @if($startup->status == 'pending')
                <span class="role-badge" style="background-color: rgba(245, 158, 11, 0.2); color: #f59e0b; margin-left: 0;">PENDING</span>
            @elseif($startup->status == 'approved')
                <span class="role-badge" style="background-color: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: 0;">APPROVED</span>
            @else
                <span class="role-badge" style="background-color: rgba(100, 116, 139, 0.2); color: #64748b; margin-left: 0;">{{ strtoupper($startup->status) }}</span>
            @endif

            <span style="color: var(--text-muted); font-size: 0.9rem; margin-left: 1rem;">Stage: {{ ucwords(str_replace('_', ' ', $startup->startup_stage)) }}</span>
        </div>
        <div>
            <a href="{{ route('startups.edit', $startup->id) }}" class="btn" style="width: auto; background-color: var(--primary-color);">Edit Startup</a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 0.5rem; border: 1px solid #10b981; margin-bottom: 1.5rem;">
            <p style="color: #10b981; font-size: 0.875rem;">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Banner Image Display -->
    @if($startup->banner)
        <div style="width: 100%; height: 300px; border-radius: 1rem; overflow: hidden; margin-bottom: 2rem; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <img src="{{ Storage::url($startup->banner) }}" alt="Startup Banner" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    @else
        <div style="width: 100%; height: 200px; border-radius: 1rem; overflow: hidden; margin-bottom: 2rem; border: 1px dashed var(--border-color); display: flex; justify-content: center; align-items: center; flex-direction: column; background-color: var(--card-bg);">
            <i data-lucide="image" style="width: 32px; color: var(--text-muted);"></i>
            <span style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">No Banner Uploaded</span>
        </div>
    @endif

    <div class="cards-grid" style="grid-template-columns: 1fr 2fr;">
        <!-- Left Sidebar Details -->
        <div class="card" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
            @if($startup->logo)
                <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin-bottom: 1.5rem; border: 4px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <img src="{{ Storage::url($startup->logo) }}" alt="Startup Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @else
                <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin-bottom: 1.5rem; border: 2px dashed var(--border-color); display: flex; justify-content: center; align-items: center; background-color: var(--input-bg);">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">No Logo</span>
                </div>
            @endif

            <h3 style="font-size: 1.25rem; font-weight: 600;">{{ $startup->title }}</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">{{ $startup->short_description }}</p>

            <hr style="width: 100%; border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

            <div style="width: 100%; text-align: left;">
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Funding Goal</p>
                <p style="font-weight: 600; font-size: 1.25rem; margin-bottom: 1rem;">${{ number_format($startup->funding_goal) }}</p>

                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Category</p>
                <p style="font-weight: 500; font-size: 1rem; text-transform: capitalize; margin-bottom: 1rem;">{{ $startup->category }}</p>

                <!-- Founder Information Section -->
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Lead Founder</p>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--primary-color); display: flex; justify-content: center; align-items: center; color: white; font-weight: 600; font-size: 0.875rem;">
                        {{ strtoupper(substr($startup->founder->name, 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-weight: 500; font-size: 0.9rem; color: var(--text-color);">{{ $startup->founder->name }}</p>
                        <p style="font-size: 0.75rem; color: var(--text-muted);">Verified Account</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Main Column Container -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Project Details Card -->
            <div class="card" style="margin: 0;">
                <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">About the Project</h2>
                <div style="line-height: 1.6; color: var(--text-color); font-size: 0.95rem;">
                    {!! nl2br(e($startup->description)) !!}
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Investment Progress</h3>
                
                <!-- Reusable Progress Bar Component -->
                <div style="margin-bottom: 2rem;">
                    <x-funding-progress :startup="$startup" size="lg" />
                </div>

                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem;">Funding Timeline</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background-color: var(--bg-color); padding: 1.25rem; border-radius: 0.75rem; border: 1px solid var(--border-color);">
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">Campaign Started</p>
                        <p style="font-weight: 600; font-size: 1.125rem;">{{ $startup->created_at->format('M d, Y') }}</p>
                    </div>
                    <div style="background-color: rgba(239, 68, 68, 0.05); padding: 1.25rem; border-radius: 0.75rem; border: 1px solid rgba(239, 68, 68, 0.2);">
                        <p style="font-size: 0.875rem; color: var(--danger-color); margin-bottom: 0.25rem;">Funding Deadline</p>
                        <p style="font-weight: 600; font-size: 1.125rem; color: var(--danger-color);">{{ \Carbon\Carbon::parse($startup->deadline)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Investor Discussion Card -->
            <div class="card" style="margin: 0;">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="message-square" style="width: 20px;"></i> Investor Discussion & Questions
                </h3>
                
                <!-- Post Comment Form (Founder can also post update) -->
                <form action="{{ route('comments.store', $startup->id) }}" method="POST" style="margin-bottom: 2rem;">
                    @csrf
                    <div style="display: flex; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-color); display: flex; justify-content: center; align-items: center; color: white; font-weight: 600; flex-shrink: 0;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1;">
                            <textarea name="body" class="form-control" rows="2" placeholder="Write a comment or post an update for your investors..." required style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); background-color: var(--input-bg); color: var(--text-color); resize: vertical; margin-bottom: 0.5rem;"></textarea>
                            <div style="display: flex; justify-content: flex-end;">
                                <button type="submit" class="btn" style="width: auto; padding: 0.5rem 1.5rem; font-size: 0.875rem; background-color: var(--primary-color);">Post Comment</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Comments List -->
                @php
                    $rootComments = $startup->comments->whereNull('parent_id')->sortByDesc('created_at');
                @endphp

                <div class="comments-list">
                    @forelse($rootComments as $comment)
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $comment->user_id === $startup->founder_id ? 'var(--primary-color)' : 'var(--input-bg)' }}; display: flex; justify-content: center; align-items: center; color: {{ $comment->user_id === $startup->founder_id ? 'white' : 'var(--text-color)' }}; font-weight: 600; border: 1px solid var(--border-color); flex-shrink: 0;">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="font-weight: 600; color: {{ $comment->user_id === $startup->founder_id ? 'var(--primary-color)' : 'var(--text-color)' }};">
                                            {{ $comment->user->name }} {{ $comment->user_id === $startup->founder_id ? '(Founder)' : '' }}
                                        </span>
                                        <span style="color: var(--text-muted); font-size: 0.75rem;">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    @if(auth()->id() === $comment->user_id)
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem;">
                                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem; white-space: pre-line;">{{ $comment->body }}</p>
                                
                                <!-- Replies -->
                                @php
                                    $replies = $startup->comments->where('parent_id', $comment->id)->sortBy('created_at');
                                @endphp
                                
                                @foreach($replies as $reply)
                                    <div style="display: flex; gap: 1rem; margin-top: 1rem; background: var(--input-bg); padding: 1rem; border-radius: 0.5rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $reply->user_id === $startup->founder_id ? 'var(--primary-color)' : 'var(--border-color)' }}; display: flex; justify-content: center; align-items: center; color: {{ $reply->user_id === $startup->founder_id ? 'white' : 'var(--text-color)' }}; font-weight: 600; flex-shrink: 0; font-size: 0.875rem;">
                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                    <span style="font-weight: 600; color: {{ $reply->user_id === $startup->founder_id ? 'var(--primary-color)' : 'var(--text-color)' }};">
                                                        {{ $reply->user->name }} {{ $reply->user_id === $startup->founder_id ? '(Founder)' : '' }}
                                                    </span>
                                                    <span style="color: var(--text-muted); font-size: 0.75rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                
                                                @if(auth()->id() === $reply->user_id)
                                                    <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Delete this reply?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem;">
                                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            <p style="color: var(--text-muted); font-size: 0.875rem; white-space: pre-line;">{{ $reply->body }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Reply Trigger -->
                                <button type="button" onclick="document.getElementById('reply-form-{{ $comment->id }}').style.display='block'" style="background: none; border: none; color: var(--primary-color); font-size: 0.75rem; font-weight: 600; padding: 0; cursor: pointer; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                                    <i data-lucide="reply" style="width: 12px;"></i> Reply
                                </button>

                                <!-- Hidden Reply Form -->
                                <form id="reply-form-{{ $comment->id }}" action="{{ route('comments.store', $startup->id) }}" method="POST" style="display: none; margin-top: 0.5rem;">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <input type="text" name="body" class="form-control" placeholder="Write a reply..." required style="flex: 1; padding: 0.5rem; font-size: 0.875rem; border-radius: 0.375rem; border: 1px solid var(--border-color); background-color: var(--input-bg); color: var(--text-color);">
                                        <button type="submit" class="btn" style="width: auto; padding: 0.5rem 1rem; font-size: 0.875rem; background-color: var(--primary-color);">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 0.875rem; text-align: center; margin-top: 2rem;">No questions or comments from investors yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
