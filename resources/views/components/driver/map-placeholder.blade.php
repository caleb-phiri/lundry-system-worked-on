{{-- Driver Map Placeholder Component --}}
@props([
    'type' => 'pickup', // pickup, delivery
    'address' => '',
    'label' => 'Open in Google Maps',
    'onClick' => null,
])

<div class="map-placeholder {{ $attributes->merge(['class' => ($attributes->get('class') ?? '')]) }}"
     {{ $onClick ? 'onclick="'.$onClick.'"' : 'onclick="openMaps(\''.$type.'\')"' }}
     role="button"
     tabindex="0"
     aria-label="{{ $label }}"
     onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click()}"
>
    <i class="fas fa-map-marked-alt"></i>
    {{ $label }}
    @if($address)
        <div class="map-address">{{ $address }}</div>
    @endif
</div>

@push('scripts')
<script>
function openMaps(type) {
    const addresses = {
        pickup: '{{ $address }}',
        delivery: '{{ $address }}'
    };
    
    const address = addresses[type] || '{{ $address }}';
    const encodedAddress = encodeURIComponent(address);
    
    // Detect if mobile device
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // Try native maps app
        if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
            window.open('maps://?q=' + encodedAddress);
            // Fallback to Google Maps web
            setTimeout(() => {
                window.open('https://www.google.com/maps/search/?api=1&query=' + encodedAddress, '_blank');
            }, 500);
        } else {
            window.open('geo:0,0?q=' + encodedAddress);
        }
    } else {
        window.open('https://www.google.com/maps/search/?api=1&query=' + encodedAddress, '_blank');
    }
}
</script>
@endpush

<style>
.map-placeholder {
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-gray-200) 100%);
    height: 120px;
    border-radius: var(--radius-lg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--color-gray-500);
    font-size: var(--font-size-sm);
    gap: var(--space-2);
    cursor: pointer;
    transition: all var(--transition-base);
    border: 2px dashed var(--color-gray-300);
    margin-top: var(--space-4);
}
.map-placeholder:hover {
    background: linear-gradient(135deg, var(--color-primary-50) 0%, var(--color-primary-100) 100%);
    color: var(--color-primary-600);
    border-color: var(--color-primary-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.map-placeholder:focus {
    outline: 2px solid var(--color-primary-500);
    outline-offset: 2px;
}
.map-placeholder i {
    font-size: var(--font-size-2xl);
}
.map-address {
    font-size: var(--font-size-xs);
    color: var(--color-gray-400);
    text-align: center;
    max-width: 200px;
}
</style>