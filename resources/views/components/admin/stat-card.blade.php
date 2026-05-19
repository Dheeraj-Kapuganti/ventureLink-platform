@props([
    'title',
    'value',
    'icon' => 'activity',
    'color' => 'primary',
    'trend' => null,
    'trendDirection' => 'up',
    'trendLabel' => 'vs last month'
])

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <div class="stat-card-top">
        <span class="stat-card-title">{{ $title }}</span>
        <div class="stat-card-icon {{ $color }}">
            <i data-lucide="{{ $icon }}" style="width: 22px; height: 22px;"></i>
        </div>
    </div>
    <div class="stat-card-value">{{ $value }}</div>
    @if($trend !== null)
        <div class="stat-card-footer">
            <span class="stat-card-trend {{ $trendDirection === 'up' ? 'up' : 'down' }}">
                <i data-lucide="{{ $trendDirection === 'up' ? 'trending-up' : 'trending-down' }}" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;"></i>
                {{ $trend }}
            </span>
            <span class="stat-card-label">{{ $trendLabel }}</span>
        </div>
    @endif
</div>
