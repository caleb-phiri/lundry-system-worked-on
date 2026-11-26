{{-- Driver Empty State Component --}}
@props([
    'icon' => 'fas fa-inbox',
    'title' => 'No data available',
    'message' => 'There\'s nothing here yet.',
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => null,
])

<div class="empty-state" {{ $attributes }}>
    <div class="empty-state-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <h3 class="empty-state-title">{{ $title }}</h3>
    <p class="empty-state-message">{{ $message }}</p>
    @if($actionText && $actionUrl)
        <div class="empty-state-action">
            <a href="{{ $actionUrl }}" class="btn btn-primary">
                @if($actionIcon)
                    <i class="{{ $actionIcon }}"></i>
                @endif
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>