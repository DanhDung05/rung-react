@props([
    'name',
    'label'       => null,
    'options'     => [],       // ['value' => 'Label'] hoặc [['value' => '', 'label' => '']]
    'selected'    => null,
    'placeholder' => null,
    'hint'        => null,
    'required'    => false,
    'disabled'    => false,
])

@php
    $hasError = $errors->has($name);
    $inputId  = 'select-' . str_replace(['[', ']'], ['-', ''], $name);
    $current  = old($name, $selected);

    $baseClass = 'block w-full rounded-md border bg-bg px-3 py-2 text-base text-text transition-base focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-bg-muted disabled:cursor-not-allowed';

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

    <select
        name="{{ $name }}"
        id="{{ $inputId }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->except('class')->merge(['class' => "$baseClass $stateClass"]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $label)
            @if (is_array($label))
                <option value="{{ $label['value'] }}" @selected($current == $label['value'])>
                    {{ $label['label'] }}
                </option>
            @else
                <option value="{{ $value }}" @selected($current == $value)>
                    {{ $label }}
                </option>
            @endif
        @endforeach
    </select>

    @if ($hint && !$hasError)
        <p class="mt-1 text-sm text-text-muted">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p class="mt-1 text-sm text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>