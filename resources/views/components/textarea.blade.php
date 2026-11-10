@props([
    'name',
    'label'       => null,
    'value'       => null,
    'placeholder' => null,
    'rows'        => 4,
    'hint'        => null,
    'required'    => false,
    'disabled'    => false,
])

@php
    $hasError = $errors->has($name);
    $inputId  = 'textarea-' . str_replace(['[', ']'], ['-', ''], $name);

    $baseClass = 'block w-full rounded-md border bg-bg px-3 py-2 text-base text-text placeholder:text-text-subtle transition-base focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-bg-muted disabled:cursor-not-allowed resize-y';

    $stateClass = $hasError
        ? 'border-danger focus:border-danger focus:ring-danger'
        : 'border-border focus:border-primary focus:ring-primary';
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-text mb-1">
            {{ $label }}
            @if ($required)<span class="text-danger">*</span>@endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->except('class')->merge(['class' => "$baseClass $stateClass"]) }}
    >{{ old($name, $value) }}</textarea>

    @if ($hint && !$hasError)
        <p class="mt-1 text-sm text-text-muted">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-sm text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>