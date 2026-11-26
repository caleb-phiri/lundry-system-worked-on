{{-- Driver Button Component --}}
@props([
    'variant' => 'primary', // primary, secondary, outline, ghost, success, warning, danger, info
    'size' => 'base', // sm, base, lg, xl
    'block' => false,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'loading' => false,
    'disabled' => false,
])

<?php
$classes = 'btn';
$classes .= ' btn-'.$variant;
$classes .= ' btn-'.$size;
if ($block) $classes .= ' btn-block';
if ($loading) $classes .= ' loading';
if ($disabled) $classes .= ' disabled';
$attributes = $attributes->merge(['class' => $classes.' '.($attributes->get('class') ?? '')]);
?>

<button {{ $attributes }} @if($disabled) disabled @endif>
    @if($loading)
        {{-- Spinner handled by CSS --}}
    @elseif($icon && $iconPosition === 'left')
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
    @if($icon && $iconPosition === 'right')
        <i class="{{ $icon }}"></i>
    @endif
</button>