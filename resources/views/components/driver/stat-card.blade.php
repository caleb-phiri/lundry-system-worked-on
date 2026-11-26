{{-- Driver Stat Card Component --}}
<div {{ $attributes->merge(['class' => 'stat-card '.($attributes->get('class') ?? '')]) }}>
    <div class="stat-card-header">
        <div class="stat-card-label">{{ $label }}</div>
        @if(isset($icon))
            <div class="stat-card-icon {{ $iconClass ?? 'stat-card-icon-primary' }}">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
    </div>
    <div class="stat-card-value">{{ $value }}</div>
    @if(isset($trend))
        <div class="stat-card-trend {{ $trendClass ?? '' }}">
            <i class="fas fa-{{ $trendDirection ?? 'arrow-up' }}"></i>
            {{ $trend }}
        </div>
    @endif
</div>