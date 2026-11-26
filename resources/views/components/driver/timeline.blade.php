{{-- Driver Timeline Component --}}
@props([
    'items' => [],
    'currentItem' => null,
    'reverse' => false,
])

<div class="timeline {{ $reverse ? 'timeline-reverse' : '' }}" {{ $attributes }}>
    @foreach($items as $index => $item)
        <?php
        $isLast = $index === count($items) - 1;
        $isCurrent = $currentItem !== null && $index === $currentItem;
        $isCompleted = !$isCurrent && ($currentItem === null || $index < $currentItem);
        $classes = 'timeline-item';
        if ($isCompleted) $classes .= ' completed';
        if ($isCurrent) $classes .= ' current';
        ?>
        <div class="{{ $classes }}" data-index="{{ $index }}">
            <div class="timeline-marker" aria-hidden="true"></div>
            <div class="timeline-content">
                @if(isset($item['time']))
                    <div class="timeline-time">
                        <i class="far fa-clock"></i>
                        {{ $item['time'] }}
                    </div>
                @endif
                <div class="timeline-title font-medium text-gray-900">
                    {{ $item['title'] ?? '' }}
                </div>
                @if(isset($item['description']))
                    <div class="timeline-description text-gray-500 text-sm mt-1">
                        {{ $item['description'] }}
                    </div>
                @endif
                @if(isset($item['meta']))
                    <div class="timeline-meta flex items-center gap-2 mt-2 text-xs text-gray-400">
                        @foreach($item['meta'] as $meta)
                            <span>{{ $meta }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<style>
.timeline {
    position: relative;
    padding: var(--space-2) 0;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--color-primary-100), var(--color-gray-200));
    z-index: 1;
}
.timeline-reverse::before {
    left: auto;
    right: 20px;
}
.timeline-item {
    position: relative;
    padding-left: 3.5rem;
    margin-bottom: var(--space-6);
    z-index: 2;
}
.timeline-reverse .timeline-item {
    padding-left: 0;
    padding-right: 3.5rem;
}
.timeline-item:last-child {
    margin-bottom: 0;
}
.timeline-marker {
    position: absolute;
    left: 12px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--color-white);
    border: 3px solid var(--color-primary-500);
    z-index: 3;
    transition: all var(--transition-base);
}
.timeline-reverse .timeline-marker {
    left: auto;
    right: 12px;
}
.timeline-item.completed .timeline-marker {
    background: var(--color-success);
    border-color: var(--color-success);
    box-shadow: 0 0 0 3px var(--color-success-light);
}
.timeline-item.current .timeline-marker {
    background: var(--color-warning);
    border-color: var(--color-warning);
    box-shadow: 0 0 0 3px var(--color-warning-light);
}
.timeline-content {
    background: var(--color-gray-50);
    border-radius: var(--radius-lg);
    padding: var(--space-3) var(--space-4);
    transition: all var(--transition-base);
}
.timeline-item:hover .timeline-content {
    background: var(--color-white);
    box-shadow: var(--shadow-sm);
}
.timeline-time {
    display: flex;
    align-items: center;
    gap: var(--space-1);
    font-size: var(--font-size-xs);
    color: var(--color-gray-500);
    margin-bottom: var(--space-1);
}
.timeline-title {
    font-size: var(--font-size-sm);
    line-height: var(--line-height-tight);
}
.timeline-description {
    font-size: var(--font-size-xs);
    line-height: var(--line-height-normal);
}
.timeline-meta {
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .timeline::before {
        left: 10px;
    }
    .timeline-reverse::before {
        right: 10px;
    }
    .timeline-item {
        padding-left: 3rem;
    }
    .timeline-reverse .timeline-item {
        padding-right: 3rem;
    }
    .timeline-marker {
        left: 10px;
        width: 14px;
        height: 14px;
    }
    .timeline-reverse .timeline-marker {
        right: 10px;
    }
}
</style>