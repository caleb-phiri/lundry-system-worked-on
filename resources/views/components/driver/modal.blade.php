{{-- Driver Modal Component --}}
@props([
    'id' => 'modal-'.uniqid(),
    'title' => null,
    'size' => 'md', // sm, md, lg, xl, full
    'centered' => false,
    'scrollable' => false,
    'backdrop' => true, // true, false, 'static'
    'keyboard' => true,
])

<?php
$sizeClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-6xl mx-4',
];
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
?>

<div 
    class="modal-overlay" 
    id="{{ $id }}"
    data-backdrop="{{ $backdrop }}"
    data-keyboard="{{ $keyboard ? 'true' : 'false' }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    aria-hidden="true"
>
    <div class="modal {{ $sizeClass }} {{ $centered ? 'modal-centered' : '' }} {{ $scrollable ? 'modal-scrollable' : '' }}">
        @if($title)
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}-title">{{ $title }}</h5>
                <button type="button" class="modal-close" data-modal-close aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        <div class="modal-body {{ $scrollable ? 'overflow-y-auto' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('{{ $id }}');
    if (!modal) return;
    
    const closeBtn = modal.querySelector('[data-modal-close]');
    const backdrop = modal.dataset.backdrop;
    const keyboard = modal.dataset.keyboard === 'true';
    
    // Close on backdrop click
    if (backdrop !== 'static') {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal(modal);
        });
    }
    
    // Close button
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closeModal(modal);
        });
    }
    
    // Keyboard
    if (keyboard) {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal(modal);
            }
        });
    }
    
    // Expose open/close functions
    window['open{{ ucfirst($id) }}'] = () => openModal(modal);
    window['close{{ ucfirst($id) }}'] = () => closeModal(modal);
});

function openModal(modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    modal.querySelector('.modal')?.focus();
}

function closeModal(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
}
</script>
@endpush