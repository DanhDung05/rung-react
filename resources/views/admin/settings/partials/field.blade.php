@php
    $key   = $key ?? '';
    $field = $field ?? [];
    $value = $value ?? null;

    $type         = $field['type'] ?? 'string';
    $label        = $field['label'] ?? $key;
    $hint         = $field['hint'] ?? null;
    $options      = $field['options'] ?? [];
    $currentValue = old($key, $value);
@endphp

<div class="space-y-1">
    @if ($type === 'string')
        <x-input :name="$key" :label="$label" :value="$currentValue" :hint="$hint" />

    @elseif ($type === 'text')
        <x-textarea :name="$key" :label="$label" :value="$currentValue" :hint="$hint" rows="3" />

    @elseif ($type === 'boolean')
        <x-checkbox :name="$key" :label="$label" value="1"
                    :checked="(bool) $currentValue"
                    :hint="$hint" />

    @elseif ($type === 'number')
        <x-input :name="$key" type="number" :label="$label" :value="$currentValue" :hint="$hint" />

    @elseif ($type === 'json')
        <x-textarea :name="$key" :label="$label" :value="$currentValue" :hint="$hint" rows="4" />

    @elseif ($type === 'image')
        <label class="block text-sm font-medium text-text mb-1">{{ $label }}</label>

        @if ($currentValue)
            <div class="mb-3">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($currentValue) }}"
                     alt="{{ $label }}"
                     class="max-h-24 rounded border border-border bg-bg-subtle p-2">
                <p class="text-xs text-text-muted mt-1">Ảnh hiện tại</p>
            </div>
        @endif

        <input type="file" name="{{ $key }}"
               accept="image/jpeg,image/png,image/webp,image/svg+xml"
               class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                      file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                      file:bg-primary file:text-text-inverse file:font-medium
                      hover:file:bg-primary-hover">

        @if ($hint)
            <p class="text-xs text-text-muted mt-1">{{ $hint }}</p>
        @endif
        @error($key)
            <p class="text-xs text-danger mt-1">{{ $message }}</p>
        @enderror

    @elseif ($type === 'color')
        <label class="block text-sm font-medium text-text mb-1">{{ $label }}</label>
        <div class="flex items-center gap-3">
            <input type="color"
                   value="{{ $currentValue ?: '#000000' }}"
                   onchange="document.getElementById('color-input-{{ $key }}').value = this.value"
                   class="w-12 h-10 rounded border border-border cursor-pointer">

            <input type="text"
                   id="color-input-{{ $key }}"
                   name="{{ $key }}"
                   value="{{ $currentValue }}"
                   placeholder="#0ea5e9"
                   class="flex-1 rounded-md border border-border bg-bg px-3 py-2 text-sm font-mono
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        @if ($hint)
            <p class="text-xs text-text-muted mt-1">{{ $hint }}</p>
        @endif
        @error($key)
            <p class="text-xs text-danger mt-1">{{ $message }}</p>
        @enderror

    @elseif ($type === 'select')
        <x-select :name="$key" :label="$label" :options="$options" :selected="$currentValue" :hint="$hint" />

    @else
        <x-input :name="$key" :label="$label" :value="$currentValue" :hint="$hint" />
    @endif
</div>