{{-- Driver Tabs Component --}}
@props([
    'tabs' => [],
    'activeTab' => null,
    'variant' => 'line', // line, pills, underline
    'fullWidth' => false,
])

<?php
$activeTab = $activeTab ?? ($tabs[0]['id'] ?? null);
$variantClasses = [
    'line' => 'border-b border-gray-200',
    'pills' => 'bg-gray-100 rounded-lg p-1',
    'underline' => '',
];
$containerClass = $variantClasses[$variant] ?? $variantClasses['line'];
?>

<div class="tabs {{ $attributes->merge(['class' => ($attributes->get('class') ?? '')]) }}">
    <div class="tabs-list {{ $containerClass }} flex {{ $fullWidth ? '' : 'overflow-x-auto' }} {{ $variant === 'pills' ? 'gap-1' : 'gap-1' }}" role="tablist">
        @foreach($tabs as $tab)
            <?php
            $isActive = $tab['id'] === $activeTab;
            $tabClasses = 'tab-trigger';
            if ($variant === 'pills') {
                $tabClasses .= ' rounded-md px-4 py-2';
                if ($isActive) $tabClasses .= ' bg-white shadow-sm text-primary-600';
                else $tabClasses .= ' text-gray-600 hover:text-gray-900';
            } elseif ($variant === 'line') {
                $tabClasses .= ' border-b-2 -mb-px px-4 py-3';
                if ($isActive) $tabClasses .= ' border-primary-500 text-primary-600';
                else $tabClasses .= ' border-transparent text-gray-500 hover:text-gray-700';
            } else {
                $tabClasses .= ' px-4 py-3 border-b-2 -mb-px';
                if ($isActive) $tabClasses .= ' border-primary-500 text-primary-600';
                else $tabClasses .= ' border-transparent text-gray-500 hover:text-gray-700';
            }
            ?>
            <button 
                role="tab"
                aria-selected="{{ $isActive ? 'true' : 'false' }}"
                aria-controls="{{ $tab['id'] }}-panel"
                id="{{ $tab['id'] }}-tab"
                class="{{ $tabClasses }} font-medium text-sm transition-colors whitespace-nowrap {{ $tab['class'] ?? '' }}"
                {{ $tab['disabled'] ? 'disabled aria-disabled="true"' : '' }}
                @if(!$tab['disabled'])
                    wire:click="setActiveTab('{{ $tab['id'] }}')"
                    @click.prevent="setActiveTab('{{ $tab['id'] }}')"
                @endif
            >
                @if(isset($tab['icon']))
                    <i class="{{ $tab['icon'] }}"></i>
                @endif
                {{ $tab['label'] }}
                @if(isset($tab['badge']))
                    <span class="badge badge-primary ms-2">{{ $tab['badge'] }}</span>
                @endif
            </button>
        @endforeach
    </div>
    
    <div class="tabs-content">
        @foreach($tabs as $tab)
            <div 
                role="tabpanel"
                id="{{ $tab['id'] }}-panel"
                aria-labelledby="{{ $tab['id'] }}-tab"
                class="tab-panel {{ $tab['id'] === $activeTab ? 'active' : '' }}"
                hidden="{{ $tab['id'] !== $activeTab ? 'hidden' : '' }}"
            >
                {{ $tab['content'] ?? '' }}
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabsContainer = document.querySelector('.tabs');
    if (!tabsContainer) return;
    
    const triggers = tabsContainer.querySelectorAll('[role="tab"]');
    const panels = tabsContainer.querySelectorAll('[role="tabpanel"]');
    
    triggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            if (this.disabled) return;
            
            const tabId = this.id.replace('-tab', '');
            
            // Update triggers
            triggers.forEach(t => {
                t.setAttribute('aria-selected', 'false');
                t.classList.remove('border-primary-500', 'text-primary-600', 'bg-white', 'shadow-sm');
                t.classList.add('border-transparent', 'text-gray-500');
                if (t.closest('.tabs-list').classList.contains('pills')) {
                    t.classList.remove('bg-white', 'shadow-sm', 'text-primary-600');
                    t.classList.add('text-gray-600');
                }
            });
            
            this.setAttribute('aria-selected', 'true');
            this.classList.remove('border-transparent', 'text-gray-500', 'text-gray-600');
            this.classList.add('border-primary-500', 'text-primary-600');
            if (this.closest('.tabs-list').classList.contains('pills')) {
                this.classList.add('bg-white', 'shadow-sm');
                this.classList.remove('text-gray-600');
            }
            
            // Update panels
            panels.forEach(panel => {
                const panelTabId = panel.id.replace('-panel', '');
                if (panelTabId === tabId) {
                    panel.classList.add('active');
                    panel.removeAttribute('hidden');
                } else {
                    panel.classList.remove('active');
                    panel.setAttribute('hidden', '');
                }
            });
            
            // Dispatch event
            window.dispatchEvent(new CustomEvent('tab-change', { detail: { tabId } }));
        });
        
        // Keyboard navigation
        trigger.addEventListener('keydown', function(e) {
            const index = Array.from(triggers).indexOf(this);
            let newIndex = index;
            
            if (e.key === 'ArrowRight') newIndex = (index + 1) % triggers.length;
            if (e.key === 'ArrowLeft') newIndex = (index - 1 + triggers.length) % triggers.length;
            if (e.key === 'Home') newIndex = 0;
            if (e.key === 'End') newIndex = triggers.length - 1;
            
            if (newIndex !== index) {
                e.preventDefault();
                triggers[newIndex].focus();
                triggers[newIndex].click();
            }
        });
    });
});
</script>
@endpush