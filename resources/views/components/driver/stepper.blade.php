{{-- Driver Stepper Component --}}
@props([
    'steps' => [],
    'currentStep' => 0,
    'completedSteps' => [],
    'vertical' => false,
])

<div class="stepper {{ $vertical ? 'stepper-vertical' : '' }}" role="navigation" aria-label="Progress steps">
    @if(!$vertical)
        <div class="stepper-line" aria-hidden="true"></div>
    @endif
    
    <ol class="stepper-items">
        @foreach($steps as $index => $step)
            <?php
            $isCompleted = in_array($index, $completedSteps) || $index < $currentStep;
            $isActive = $index === $currentStep;
            $isLast = $index === count($steps) - 1;
            $classes = 'stepper-item';
            if ($isCompleted) $classes .= ' completed';
            if ($isActive) $classes .= ' active';
            ?>
            <li class="{{ $classes }}" {{ $attributes->merge(['data-step' => $index]) }}>
                <div class="stepper-circle" aria-hidden="true">
                    @if($isCompleted)
                        <i class="fas fa-check"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <div class="stepper-label">{{ $step['label'] ?? $step }}</div>
                @if(isset($step['description']))
                    <div class="stepper-description">{{ $step['description'] }}</div>
                @endif
            </li>
        @endforeach
    </ol>
</div>

<style>
.stepper {
    display: flex;
    align-items: flex-start;
    position: relative;
}
.stepper-line {
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--color-gray-200);
    z-index: 1;
}
.stepper-vertical .stepper-line {
    top: 0;
    left: 20px;
    width: 2px;
    height: 100%;
}
.stepper-items {
    display: flex;
    width: 100%;
    list-style: none;
    padding: 0;
    margin: 0;
    z-index: 2;
}
.stepper-vertical .stepper-items {
    flex-direction: column;
}
.stepper-item {
    flex: 1;
    display: flex;
    @if(!$vertical)
        flex-direction: column;
    @else
        flex-direction: row;
    @endif
    align-items: center;
    position: relative;
    padding: var(--space-4) 0;
}
.stepper-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-bold);
    color: var(--color-gray-400);
    background: var(--color-white);
    border: 2px solid var(--color-gray-200);
    transition: all var(--transition-base);
    margin-bottom: var(--space-2);
    flex-shrink: 0;
}
.stepper-vertical .stepper-circle {
    margin-bottom: 0;
    margin-right: var(--space-3);
}
.stepper-item.completed .stepper-circle {
    background: var(--color-success);
    border-color: var(--color-success);
    color: var(--color-white);
}
.stepper-item.active .stepper-circle {
    background: var(--color-primary-500);
    border-color: var(--color-primary-500);
    color: var(--color-white);
    box-shadow: 0 0 0 4px var(--color-primary-100);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 var(--color-primary-100); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.stepper-label {
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    color: var(--color-gray-500);
    text-align: center;
    white-space: nowrap;
}
.stepper-vertical .stepper-label {
    text-align: left;
}
.stepper-item.completed .stepper-label,
.stepper-item.active .stepper-label {
    color: var(--color-gray-900);
}
.stepper-description {
    font-size: var(--font-size-xs);
    color: var(--color-gray-400);
    text-align: center;
    margin-top: var(--space-1);
}
</style>