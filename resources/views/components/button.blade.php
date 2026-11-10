@props([
    'variant' => 'primary',    // primary | secondary | outline | ghost | danger
    'size'    => 'md',          // sm | md | lg
    'type'    => 'button',      // button | submit | reset
    'href'    => null,
    'block'   => false,         // full width
])

@php
    $base = 'inline-flex items-center justify-center font-medium transition-base focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $sizes = [
        'sm' => 'h-8 px-3 text-sm rounded-md',
        'md' => 'h-10 px-4 text-base rounded-md',
        'lg' => 'h-12 px-6 text-lg rounded-lg',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];

    // Dùng inline style cho màu chữ để chắc chắn không bị CSS ghi đè
    $variants = [
        'primary'   => 'bg-primary hover:bg-primary-hover focus-visible:ring-primary shadow-sm',
        'secondary' => 'bg-secondary hover:bg-secondary-hover focus-visible:ring-secondary shadow-sm',
        'outline'   => 'border border-border text-text hover:bg-bg-subtle focus-visible:ring-primary',
        'ghost'     => 'text-text hover:bg-bg-subtle focus-visible:ring-primary',
        'danger'    => 'bg-danger hover:opacity-90 focus-visible:ring-danger shadow-sm',
    ];
    $variantClass = $variants[$variant] ?? $variants['primary'];

    // Màu chữ mặc định (inline style)
    $textColor = match ($variant) {
        'primary', 'secondary', 'danger' => 'color: #ffffff;',
        default => '',
    };

    $blockClass = $block ? 'w-full' : '';

    $classes = trim("$base $sizeClass $variantClass $blockClass");
@endphp

@if ($href)
    <a href="{{ $href }}" style="{{ $textColor }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" style="{{ $textColor }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif