@props(['startup', 'size' => 'default'])

@php
    // Calculate the percentage safely
    $percent = $startup->funding_goal > 0 ? min(100, round(($startup->current_funding / $startup->funding_goal) * 100)) : 0;
    
    // Calculate the remaining funding safely (preventing negative remaining if overfunded)
    $remaining = max(0, $startup->funding_goal - ($startup->current_funding ?? 0));
    
    // Size specific adjustments
    $barHeight = $size === 'lg' ? '10px' : '6px';
    $labelSize = $size === 'lg' ? '0.875rem' : '0.75rem';
    $numberSize = $size === 'lg' ? '1.125rem' : '0.875rem';
@endphp

<div class="funding-progress-component" style="width: 100%;">
    <!-- Top Labels -->
    <div style="display: flex; justify-content: space-between; font-size: {{ $labelSize }}; margin-bottom: 0.5rem; align-items: flex-end;">
        <span style="font-weight: 500; color: var(--text-color);">
            @if($size === 'lg')
                <span style="font-weight: 600; font-size: {{ $numberSize }};">${{ number_format($startup->current_funding ?? 0) }}</span> 
                <span style="color: var(--text-muted); font-weight: 400;">raised</span>
            @else
                Funding Progress
            @endif
        </span>
        <span style="font-weight: 600; color: var(--text-color); font-size: {{ $size === 'lg' ? '1rem' : '0.75rem' }};">{{ $percent }}%</span>
    </div>

    <!-- The Progress Bar -->
    <div style="width: 100%; height: {{ $barHeight }}; background-color: var(--input-bg); border-radius: 999px; overflow: hidden; border: 1px solid var(--border-color);">
        <div style="height: 100%; width: {{ $percent }}%; background-color: var(--primary-color); transition: width 0.5s ease-out;"></div>
    </div>

    <!-- Bottom Labels: Goal & Remaining -->
    <div style="display: flex; justify-content: space-between; font-size: {{ $size === 'lg' ? '0.8rem' : '0.75rem' }}; margin-top: 0.5rem; color: var(--text-muted);">
        @if($size === 'lg')
            <span>Target: ${{ number_format($startup->funding_goal) }}</span>
            <span>${{ number_format($remaining) }} left</span>
        @else
            <span>${{ number_format($startup->current_funding ?? 0) }} raised</span>
            <span>Target: ${{ number_format($startup->funding_goal) }}</span>
        @endif
    </div>
</div>
