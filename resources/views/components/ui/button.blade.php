@props([
    'type' => 'button',
    'color' => 'primary',
    'size' => null,
    'outline' => false,
    'disabled' => false,
    'id' => null
])

@php
    $colors = [
        'primary' => 'primary',
        'secondary' => 'secondary',
        'success' => 'success',
        'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        'dark' => 'dark',
        'light' => 'light',
    ];

    $sizes = [
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
    ];

    $class = 'btn';
    $class .= $outline ? ' btn-outline-' : ' btn-';
    $class .= $colors[$color] ?? 'primary';
    $class .= $size && isset($sizes[$size]) ? ' ' . $sizes[$size] : '';
    $class .= $disabled ? ' disabled' : '';
@endphp

<button
    type="{{ $type }}"
    class="{{ $class }}"
    @if($id) id="{{ $id }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => '']) }}
>
    {{ $slot }}
</button>
