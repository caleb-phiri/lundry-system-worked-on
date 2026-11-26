{{-- Driver Progress Card Component --}}
@props([
    'title' => null,
    'value' => 0,
    'max' => 100,
    'label' => null,
    'icon' => null,
    'color' => 'primary', // primary, success, warning, danger, info
    'showPercentage' => true,
    'size' => 'base', // sm, base, lg
])

<?php
$percentage = $max > 0 ? min(100, round(($value / $max) * 100)) : 0;
$colorClasses = [
    'primary' => ['bar' => 'bg-primary-500', 'bg' => 'bg-primary-100', 'text' => 'text-primary-700'],
    'success' => ['bar' => 'bg-success', 'bg' => 'bg-success-light', 'text' => 'text-success-dark'],
    'warning' => ['bar' => 'bg-warning', 'bg' => 'bg-warning-light', 'text' => 'text-warning-dark'],
    'danger' => ['bar' => 'bg-danger', 'bg' => 'bg-danger-light', 'text' => 'text-danger-dark'],
    'info' => ['bar' => 'bg-info', 'bg' => 'bg-info-light', 'text' => 'text-info-dark'],
];
$colors = $colorClasses[$color] ?? $colorClasses['primary'];
$sizeClasses = [
    'sm' => ['height' => '4px', 'radius' => 'rounded-full', 'gap' => 'gap-2', 'font' => 'text-xs'],
    'base' => ['height' => '8px', 'radius' => 'rounded-full', 'gap' => 'gap-2', 'font' => 'text-sm'],
    'lg' => ['height' => '12px', 'radius' => 'rounded-lg', 'gap' => 'gap-3', 'font' => 'text-base'],
];
$sizes = $sizeClasses[$size] ?? $sizeClasses['base'];
?>

<div class="progress-card {{ $attributes->merge(['class' => ($attributes->get('class') ?? '')]) }}">
    @if($title || $icon)
        <div class="progress-header flex items-center {{ $sizes['gap'] }} mb-2">
            @if($icon)
                <div class="progress-icon w-8 h-8 {{ $colors['bg'] }} {{ $colors['text'] }} rounded-lg flex items-center justify-center">
                    <i class="{{ $icon }}"></i>
                </div>
            @endif
            @if($title)
                <h4 class="progress-title font-semibold text-gray-900 {{ $sizes['font'] }}">{{ $title }}</h4>
            @endif
        </div>
    @endif
    
    <div class="progress-track w-full" style="height: {{ $sizes['height'] }};">
        <div class="progress-bar {{ $colors['bar'] }} {{ $sizes['radius'] }}" 
             style="height: 100%; width: {{ $percentage }}%;"
             role="progressbar"
             aria-valuenow="{{ $percentage }}"
             aria-valuemin="0"
             aria-valuemax="100"
             aria-label="{{ $title ?? 'Progress' }}: {{ $percentage }}%">
        </div>
    </div>
    
    @if($showPercentage || $label)
        <div class="progress-footer flex justify-between items-center mt-2 {{ $sizes['gap'] }}">
            @if($label)
                <span class="progress-label text-gray-500 {{ $sizes['font'] }}">{{ $label }}</span>
            @endif
            @if($showPercentage)
                <span class="progress-value font-semibold text-gray-900 {{ $sizes['font'] }}">{{ $percentage }}%</span>
            @endif
        </div>
    @endif
</div>