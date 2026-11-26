{{-- Driver Input Component --}}
@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'success' => false,
    'hint' => null,
    'icon' => null,
    'size' => 'base', // sm, base, lg
    'required' => false,
    'readonly' => false,
    'disabled' => false,
])

<?php
$inputClasses = 'form-input';
$inputClasses .= ' form-input-'.$size;
if ($error) $inputClasses .= ' error';
if ($success && !$error) $inputClasses .= ' success';
$inputClasses .= ' '.($attributes->get('class') ?? '');
?>

<div class="form-group">
    @if($label)
        <label class="form-label" {{ $labelId ? 'for="'.$labelId.'"' : '' }}>
            {{ $label }}
            @if($required)
                <span class="required" aria-hidden="true">*</span>
            @endif
        </label>
    @endif
    
    @if($icon)
        <div class="form-input-wrapper">
            <i class="input-icon {{ $icon }}"></i>
            <input 
                {{ $attributes->merge(['class' => $inputClasses]) }} 
                type="{{ $type }}"
                @if($readonly) readonly @endif
                @if($disabled) disabled @endif
                @if($labelId) aria-describedby="{{$labelId}}_hint" @endif
                @if($error) aria-invalid="true" aria-describedby="{{$labelId}}_error" @endif
            >
        </div>
    @else
        <input 
            {{ $attributes->merge(['class' => $inputClasses]) }} 
            type="{{ $type }}"
            @if($readonly) readonly @endif
            @if($disabled) disabled @endif
            @if($labelId) aria-describedby="{{$labelId}}_hint" @endif
            @if($error) aria-invalid="true" aria-describedby="{{$labelId}}_error" @endif
        >
    @endif
    
    @if($error)
        <p class="form-text error" id="{{ $labelId }}_error" role="alert">{{ $error }}</p>
    @elseif($hint)
        <p class="form-text" id="{{ $labelId }}_hint">{{ $hint }}</p>
    @endif
</div>