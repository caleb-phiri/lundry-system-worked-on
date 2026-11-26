{{-- Driver Dropdown Component --}}
@props([
    'trigger' => null,
    'align' => 'right', // left, right
    'width' => 'auto', // auto, 12rem, 16rem, 20rem
])

<div class="dropdown {{ $attributes->merge(['class' => ($attributes->get('class') ?? '')]) }}" x-data="{ open: false }" @click.outside="open = false">
    <div @click="open = !open" class="dropdown-trigger" x-ref="trigger">
        {{ $trigger ?? $slot }}
    </div>
    
    <div class="dropdown-menu" 
         :class="{ 'show': open }" 
         :style="align === 'right' ? 'right: 0; left: auto;' : 'left: 0; right: auto;'"
         x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         role="menu"
         aria-orientation="vertical"
    >
        {{ $slot }}
    </div>
</div>

<style>
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-trigger {
    cursor: pointer;
}
.dropdown-menu {
    position: absolute;
    top: 100%;
    z-index: var(--z-dropdown);
    min-width: 12rem;
    margin-top: var(--space-2);
    padding: var(--space-2);
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px);
    transition: opacity var(--transition-fast), visibility var(--transition-fast), transform var(--transition-fast);
}
.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.dropdown-menu[style*="left: 0"] {
    left: 0;
    right: auto;
}
.dropdown-menu[style*="right: 0"] {
    right: 0;
    left: auto;
}
</style>