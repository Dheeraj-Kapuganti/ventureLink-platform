@extends('layouts.investor')

@section('content')
<style>
    .details-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-top: -6rem; /* Overlaps banner slightly */
        position: relative;
        z-index: 10;
        padding: 0 1rem;
    }

    @media (max-width: 1024px) {
        .details-container {
            grid-template-columns: 1fr;
        }
    }

    .hero-banner {
        height: 300px;
        background-size: cover;
        background-position: center;
        border-radius: 1.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 150px;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }

    .content-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .startup-logo-large {
        width: 100px;
        height: 100px;
        border-radius: 1rem;
        background-color: var(--card-bg);
        border: 4px solid var(--bg-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        color: var(--text-muted);
        margin-top: -50px;
        margin-bottom: 1rem;
    }

    .title-area {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .main-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .category-badge {
        font-size: 0.875rem;
        color: var(--primary-color);
        background-color: rgba(16, 185, 129, 0.1);
        padding: 0.35rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .description-text {
        color: var(--text-muted);
        line-height: 1.7;
        font-size: 1rem;
        white-space: pre-line; /* respects line breaks */
    }

    .founder-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background-color: var(--input-bg);
        border-radius: 1rem;
    }

    .founder-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.25rem;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .metric-box {
        padding: 1.25rem;
        background-color: var(--input-bg);
        border-radius: 1rem;
        text-align: center;
    }

    .metric-label {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-color);
    }

    .progress-container {
        margin-bottom: 2rem;
    }

    .progress-bar-bg {
        width: 100%;
        height: 10px;
        background-color: var(--input-bg);
        border-radius: 5px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: 5px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .invest-btn {
        width: 100%;
        padding: 1rem;
        font-size: 1.125rem;
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .comment-item {
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 0;
    }

    .comment-item:last-child {
        border-bottom: none;
    }
    
    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .comment-author {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .comment-time {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .comment-text {
        font-size: 0.95rem;
        color: var(--text-color);
    }
    
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.25rem;
    }
    .star-rating-input input {
        display: none;
    }
    .star-rating-input label {
        cursor: pointer;
        color: var(--border-color);
        transition: color 0.2s;
    }
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label,
    .star-rating-input input:checked ~ label {
        color: #f59e0b;
    }
</style>

<div class="dashboard-interior">
    
    @if(session('success'))
        <div style="background-color: rgba(16, 185, 129, 0.1); color: var(--primary-color); border: 1px solid var(--primary-color); padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; font-weight: 500;">
            <i data-lucide="check-circle" style="width: 18px; margin-right: 0.5rem; vertical-align: text-bottom;"></i>
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('investor.startups.index') }}" class="btn" style="background: none; color: var(--text-muted); box-shadow: none; padding: 0.5rem 0; margin-bottom: 1rem;">
        <i data-lucide="arrow-left" style="width: 18px;"></i> Back to Startups
    </a>

    <!-- Hero Banner -->
    <div class="hero-banner" style="{{ $startup->banner ? 'background-image: url('.asset('storage/'.$startup->banner).');' : 'background: linear-gradient(135deg, #1e293b, #0f172a);' }}">
    </div>

    <div class="details-container">
        <!-- Main Content Column -->
        <div class="main-column">
            <div class="content-card animate-reveal">
                @if($startup->logo)
                    <img src="{{ asset('storage/'.$startup->logo) }}" alt="Logo" class="startup-logo-large">
                @else
                    <div class="startup-logo-large">{{ strtoupper(substr($startup->title, 0, 1)) }}</div>
                @endif
                
                <div class="title-area">
                    <div>
                        <h1 class="main-title">{{ $startup->title }}</h1>
                        <p style="color: var(--text-muted); font-size: 1.1rem;">{{ $startup->short_description }}</p>
                    </div>
                    <span class="category-badge">{{ $startup->category ?? 'General' }}</span>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 2rem 0;">

                <h2 class="section-title"><i data-lucide="align-left"></i> About the Startup</h2>
                <div class="description-text">
                    {{ $startup->description }}
                </div>
            </div>

            <div class="content-card animate-reveal" style="animation-delay: 0.2s;">
                <h2 class="section-title"><i data-lucide="users"></i> Founder Information</h2>
                <div class="founder-info">
                    <div class="founder-avatar">
                        {{ strtoupper(substr($startup->founder->name ?? 'F', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $startup->founder->name ?? 'Unknown Founder' }}</div>
                        <div style="color: var(--text-muted); font-size: 0.9rem;">Lead Founder / CEO</div>
                    </div>
                </div>
            </div>

            <div class="content-card animate-reveal" style="animation-delay: 0.3s;">
                <h2 class="section-title"><i data-lucide="star"></i> Reviews & Ratings</h2>
                
                @php
                    $myReview = $startup->reviews->where('user_id', auth()->id())->first();
                @endphp

                <form action="{{ route('reviews.store', $startup->id) }}" method="POST" style="margin-bottom: 2rem; background: var(--input-bg); padding: 1.5rem; border-radius: 1rem;">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Your Rating</label>
                        <div class="star-rating-input">
                            <input type="radio" id="star5" name="rating" value="5" {{ ($myReview && $myReview->rating == 5) ? 'checked' : '' }} required /><label for="star5" title="5 stars"><i data-lucide="star" style="fill: currentColor;"></i></label>
                            <input type="radio" id="star4" name="rating" value="4" {{ ($myReview && $myReview->rating == 4) ? 'checked' : '' }} /><label for="star4" title="4 stars"><i data-lucide="star" style="fill: currentColor;"></i></label>
                            <input type="radio" id="star3" name="rating" value="3" {{ ($myReview && $myReview->rating == 3) ? 'checked' : '' }} /><label for="star3" title="3 stars"><i data-lucide="star" style="fill: currentColor;"></i></label>
                            <input type="radio" id="star2" name="rating" value="2" {{ ($myReview && $myReview->rating == 2) ? 'checked' : '' }} /><label for="star2" title="2 stars"><i data-lucide="star" style="fill: currentColor;"></i></label>
                            <input type="radio" id="star1" name="rating" value="1" {{ ($myReview && $myReview->rating == 1) ? 'checked' : '' }} /><label for="star1" title="1 star"><i data-lucide="star" style="fill: currentColor;"></i></label>
                        </div>
                    </div>
                    
                    <textarea name="review_text" class="form-control" rows="3" placeholder="Write your review here... (Optional)" style="resize: vertical; margin-bottom: 1rem;">{{ $myReview->review_text ?? '' }}</textarea>
                    
                    <button type="submit" class="btn">{{ $myReview ? 'Update Review' : 'Submit Review' }}</button>
                </form>

                <div class="reviews-list">
                    @forelse($startup->reviews->sortByDesc('created_at') as $review)
                        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $review->user->profile_image ? asset('storage/'.$review->user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($review->user->name).'&background=10b981&color=fff' }}" alt="User" style="width: 32px; height: 32px; border-radius: 50%;">
                                    <div>
                                        <div style="font-weight: 600;">{{ $review->user->name }}</div>
                                        <div style="color: var(--text-muted); font-size: 0.75rem;">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div style="display: flex; gap: 0.25rem; color: #f59e0b;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" style="width: 14px; {{ $i <= $review->rating ? 'fill: currentColor;' : 'color: var(--border-color); fill: none;' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($review->review_text)
                                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin-top: 0.5rem;">{{ $review->review_text }}</p>
                            @endif
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 0.875rem; text-align: center;">No reviews yet. Be the first to review this startup!</p>
                    @endforelse
                </div>
            </div>

            <div class="content-card animate-reveal" style="animation-delay: 0.4s;">
                <h2 class="section-title"><i data-lucide="message-square"></i> Investor Discussion</h2>
                
                <!-- Post Comment Form -->
                <form action="{{ route('comments.store', $startup->id) }}" method="POST" style="margin-bottom: 2rem;">
                    @csrf
                    <div style="display: flex; gap: 1rem;">
                        <img src="{{ auth()->user()->profile_image ? asset('storage/'.auth()->user()->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10b981&color=fff' }}" alt="Me" style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">
                        <div style="flex: 1;">
                            <textarea name="body" class="form-control" rows="2" placeholder="Ask a question or leave a comment..." required style="resize: vertical; margin-bottom: 0.5rem;"></textarea>
                            <div style="display: flex; justify-content: flex-end;">
                                <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Post Comment</button>
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
                        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                            <img src="{{ $comment->user->profile_image ? asset('storage/'.$comment->user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=8b5cf6&color=fff' }}" alt="User" style="width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;">
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
                                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem;">
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
                                        <img src="{{ $reply->user->profile_image ? asset('storage/'.$reply->user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=3b82f6&color=fff' }}" alt="User" style="width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;">
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

                                <!-- Reply Button -->
                                <button type="button" onclick="document.getElementById('reply-form-{{ $comment->id }}').style.display='block'" style="background: none; border: none; color: var(--primary-color); font-size: 0.75rem; font-weight: 600; padding: 0; cursor: pointer; margin-top: 0.5rem;">
                                    Reply
                                </button>

                                <!-- Hidden Reply Form -->
                                <form id="reply-form-{{ $comment->id }}" action="{{ route('comments.store', $startup->id) }}" method="POST" style="display: none; margin-top: 0.5rem;">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <input type="text" name="body" class="form-control" placeholder="Write a reply..." required style="padding: 0.5rem; font-size: 0.875rem;">
                                        <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Send</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 0.875rem; text-align: center; margin-top: 2rem;">No comments yet. Be the first to start the discussion!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Action Column -->
        <div class="sidebar-column">
            <div class="content-card animate-reveal" style="animation-delay: 0.1s; position: sticky; top: 90px;">
                
                @php
                    $progress = 0;
                    if($startup->funding_goal > 0) {
                        $progress = min(100, ($startup->current_funding / $startup->funding_goal) * 100);
                    }
                @endphp

                <div class="progress-container">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; align-items: flex-end;">
                        <div>
                            <div style="font-size: 2rem; font-weight: 700; color: var(--text-color);">${{ number_format($startup->current_funding) }}</div>
                            <div style="color: var(--text-muted); font-size: 0.9rem;">raised of ${{ number_format($startup->funding_goal) }} goal</div>
                        </div>
                        <div style="font-weight: 600; color: var(--primary-color);">{{ number_format($progress, 1) }}%</div>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>

                <div class="metric-grid">
                    <div class="metric-box">
                        <div class="metric-label">Equity Offered</div>
                        <div class="metric-value" style="color: #3b82f6;">{{ $mockData['equity_available'] }}</div>
                    </div>
                    <div class="metric-box">
                        <div class="metric-label">Investors</div>
                        <div class="metric-value">{{ $mockData['investor_count'] }}</div>
                    </div>
                    <div class="metric-box" style="grid-column: 1 / -1; display: flex; justify-content: space-between; align-items: center;">
                        <span class="metric-label" style="margin: 0;">Community Rating</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i data-lucide="star" style="color: #f59e0b; fill: #f59e0b; width: 20px;"></i>
                            <span class="metric-value">{{ $startup->averageRating() ?: 'New' }}</span>
                        </div>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">
                
                <div style="text-align: center; color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem;">
                    Minimum investment: $100
                </div>

                <form action="{{ route('investor.startups.invest', $startup->id) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        @php
                            $equityOffered = $startup->equity_offered ?? 10.0;
                            $valuation = $equityOffered > 0 ? ($startup->funding_goal / ($equityOffered / 100)) : ($startup->funding_goal * 5);
                        @endphp
                        <div style="position: relative;">
                            <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-weight: 600;">$</span>
                            <input type="number" id="investmentAmount" name="amount" class="form-control" placeholder="Enter amount" min="100" style="padding-left: 2rem; font-size: 1.125rem; font-weight: 600;" required>
                        </div>
                        <div id="equityPreview" style="color: var(--primary-color); font-size: 0.875rem; font-weight: 600; margin-top: 0.5rem; display: none;">
                            Estimated Equity: <span id="equityValue">0</span>%
                        </div>
                        @error('amount')
                            <div style="color: var(--danger-color); font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn invest-btn">
                        Invest Now
                    </button>
                </form>
                
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button class="btn" style="flex: 1; background: var(--input-bg); color: var(--text-color); box-shadow: none;">
                        <i data-lucide="share-2" style="width: 18px; margin-right: 0.5rem;"></i> Share
                    </button>
                    @php
                        $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())->where('startup_id', $startup->id)->exists();
                    @endphp
                    <form action="{{ route('investor.bookmarks.toggle', $startup->id) }}" method="POST" style="flex: 1; display: flex;">
                        @csrf
                        <button type="submit" class="btn" style="flex: 1; background: {{ $isBookmarked ? 'rgba(16, 185, 129, 0.1)' : 'var(--input-bg)' }}; color: {{ $isBookmarked ? 'var(--primary-color)' : 'var(--text-color)' }}; box-shadow: none; border: {{ $isBookmarked ? '1px solid var(--primary-color)' : '1px solid transparent' }};">
                            <i data-lucide="bookmark" style="width: 18px; margin-right: 0.5rem; fill: {{ $isBookmarked ? 'var(--primary-color)' : 'none' }};"></i> {{ $isBookmarked ? 'Saved' : 'Save' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('investmentAmount');
        const equityPreview = document.getElementById('equityPreview');
        const equityValue = document.getElementById('equityValue');
        const valuation = {{ $valuation }};

        if(amountInput && equityPreview) {
            amountInput.addEventListener('input', function() {
                const amount = parseFloat(this.value);
                if(amount > 0 && valuation > 0) {
                    const equity = (amount / valuation) * 100;
                    equityValue.textContent = equity.toFixed(2);
                    equityPreview.style.display = 'block';
                } else {
                    equityPreview.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
