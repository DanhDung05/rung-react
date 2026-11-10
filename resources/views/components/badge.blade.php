@props([
    'variant' => 'default',  // default | primary | secondary | success | warning | danger | info
    'size'    => 'md',       // sm | md
])

@php
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];

    $variants = [
        'default'   => 'bg-bg-muted text-text',
        'primary'   => 'bg-primary text-text-inverse',
        'secondary' => 'bg-secondary text-text-inverse',
        'success'   => 'bg-success text-text-inverse',
        'warning'   => 'bg-warning text-text-inverse',
        'danger'    => 'bg-danger text-text-inverse',
        'info'      => 'bg-info text-text-inverse',
    ];
    $variantClass = $variants[$variant] ?? $variants['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium $sizeClass $variantClass"]) }}>
    {{ $slot }}
</span>