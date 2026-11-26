{{-- Driver Badge Component --}}
<span {{ $attributes->merge(['class' => 'badge '.($attributes->get('class') ?? '')]) }}>
    @if($dot)
        <span class="badge-dot"></span>
    @endif
    {{ $slot }}
</span>