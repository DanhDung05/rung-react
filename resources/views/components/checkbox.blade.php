@props([
    'name',
    'label'    => null,
    'value'    => '1',
    'checked'  => false,
    'hint'     => null,
    'disabled' => false,
])

@php
    $hasError = $errors->has($name);
    $inputId  = 'checkbox-' . str_replace(['[', ']'], ['-', ''], $name);
    $isChecked = old($name, $checked);
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    <label for="{{ $inputId }}" class="inline-flex items-start gap-2 cursor-pointer">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $value }}"
            @checked($isChecked)
            @if($disabled) disabled @endif
            {{ $attributes->except('class')->merge([
                'class' => 'mt-0.5 rounded border-border text-primary focus:ring-primary focus:ring-offset-0 disabled:opacity-50'
            ]) }}
        />
        @if ($label)
            <span class="text-base text-text select-none">{{ $label }}</span>
        @endif
    </label>

    @if ($hint && !$hasError)
        <p class="mt-1 text-sm text-text-muted">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-sm text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>