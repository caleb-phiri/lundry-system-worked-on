{{-- Driver Alert Component --}}
@props([
    'type' => 'info', // success, warning, danger, info
    'dismissible' => true,
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'alert alert-'.$type.($dismissible ? ' alert-dismissible' : '').' '.($attributes->get('class') ?? '')]) }} role="alert">
    <div class="alert-icon">
        @switch($type)
            @case('success')
                <i class="fas fa-check-circle"></i>
                @break
            @case('warning')
                <i class="fas fa-exclamation-triangle"></i>
                @break
            @case('danger')
                <i class="fas fa-exclamation-circle"></i>
                @break
            @default
                <i class="fas fa-info-circle"></i>
        @endswitch
    </div>
    <div class="alert-content">
        @if($title)
            <div class="alert-title">{{ $title }}</div>
        @endif
        <div class="alert-message">
            {{ $slot }}
        </div>
    </div>
    @if($dismissible)
        <button type="button" class="alert-close" aria-label="Dismiss">
            <i class="fas fa-times"></i>
        </button>
    @endif
</div>