@props([
    'type'        => 'info',    // success | error | warning | info
    'title'       => null,
    'dismissible' => false,
])

@php
    $styles = [
        'success' => 'bg-success/10 border-success text-success',
        'error'   => 'bg-danger/10 border-danger text-danger',
        'warning' => 'bg-warning/10 border-warning text-warning',
        'info'    => 'bg-info/10 border-info text-info',
    ];
    $styleClass = $styles[$type] ?? $styles['info'];
@endphp

<div
    {{ $attributes->merge(['class' => "border-l-4 $styleClass rounded-md p-4"]) }}
    role="alert"
    @if($dismissible) x-data="{ show: true }" x-show="show" @endif
>
    <div class="flex items-start">
        <div class="flex-1">
            @if ($title)
                <p class="font-semibold mb-1">{{ $title }}</p>
            @endif
            <div class="text-sm">{{ $slot }}</div>
        </div>

        @if ($dismissible)
            <button type="button" @click="show = false" class="ml-4 text-current opacity-60 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        @endif
    </div>
</div>