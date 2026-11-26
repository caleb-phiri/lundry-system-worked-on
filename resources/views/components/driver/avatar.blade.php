{{-- Driver Avatar Component --}}
@props([
    'src' => null,
    'alt' => '',
    'name' => null,
    'size' => 'base', // xs, sm, base, lg, xl, 2xl, 3xl
    'status' => null, // online, offline, busy, away
    'shape' => 'circle', // circle, rounded, square
])

<?php
$sizeClasses = [
    'xs' => 'avatar-xs',
    'sm' => 'avatar-sm',
    'base' => 'avatar-base',
    'lg' => 'avatar-lg',
    'xl' => 'avatar-xl',
    '2xl' => 'avatar-2xl',
    '3xl' => 'avatar-3xl',
];
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['base'];

$shapeClasses = [
    'circle' => 'rounded-full',
    'rounded' => 'rounded-lg',
    'square' => 'rounded-none',
];
$shapeClass = $shapeClasses[$shape] ?? $shapeClasses['circle'];

$initials = '';
if ($name) {
    $words = explode(' ', trim($name));
    $initials = strtoupper(($words[0][0] ?? '').($words[1][0] ?? ''));
}
?>

<div class="avatar {{ $sizeClass }} {{ $shapeClass }} {{ $attributes->merge(['class' => ($attributes->get('class') ?? '')]) }}"
     role="img"
     aria-label="{{ $alt ?: $name ?: 'Avatar' }}">
    @if($src)
        <img src="{{ $src }}" alt="{{ $alt ?: $name }}" loading="lazy">
    @else
        {{ $initials ?: '<i class="fas fa-user"></i>' }}
    @endif
    
    @if($status)
        <span class="avatar-status avatar-status-{{ $status }}"></span>
    @endif
</div>

<style>
.avatar {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-full);
    background: linear-gradient(135deg, var(--color-primary-100), var(--color-secondary-100));
    color: var(--color-primary-600);
    font-weight: var(--font-weight-semibold);
    overflow: hidden;
    flex-shrink: 0;
}
.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.avatar-rounded { border-radius: var(--radius-lg); }
.avatar-square { border-radius: var(--radius-base); }

.avatar-xs { width: 24px; height: 24px; font-size: var(--font-size-xs); }
.avatar-sm { width: 32px; height: 32px; font-size: var(--font-size-sm); }
.avatar-base { width: 40px; height: 40px; font-size: var(--font-size-base); }
.avatar-lg { width: 48px; height: 48px; font-size: var(--font-size-lg); }
.avatar-xl { width: 64px; height: 64px; font-size: var(--font-size-xl); }
.avatar-2xl { width: 80px; height: 80px; font-size: var(--font-size-2xl); }
.avatar-3xl { width: 120px; height: 120px; font-size: var(--font-size-4xl); }

.avatar-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 25%;
    height: 25%;
    border-radius: 50%;
    border: 2px solid var(--color-white);
    background: var(--color-gray-400);
}
.avatar-status-online { background: var(--color-success); }
.avatar-status-offline { background: var(--color-gray-400); }
.avatar-status-busy { background: var(--color-danger); }
.avatar-status-away { background: var(--color-warning); }

.avatar-group {
    display: flex;
}
.avatar-group .avatar {
    margin-left: -8px;
    border: 2px solid var(--color-white);
    transition: transform var(--transition-fast);
}
.avatar-group .avatar:first-child {
    margin-left: 0;
}
.avatar-group .avatar:hover {
    transform: scale(1.1);
    z-index: 1;
}
</style>