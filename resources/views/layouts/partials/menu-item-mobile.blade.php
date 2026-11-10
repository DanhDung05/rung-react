@php
    $hasChildren = $item->children->isNotEmpty();
    $paddingLeft = ($level - 1) * 16 + 12;
@endphp

@if ($hasChildren)
    {{-- Item cha — hiển thị nhóm có thụt lề theo cấp --}}
    <div class="pt-3 pb-1 text-xs font-semibold text-text-muted uppercase tracking-wide"
         style="padding-left: {{ $paddingLeft + 12 }}px;">
        {{ $item->label }}
    </div>
    @foreach ($item->children as $child)
        @include('layouts.partials.menu-item-mobile', ['item' => $child, 'level' => $level + 1])
    @endforeach
@else
    <a href="{{ $item->resolved_url }}"
       target="{{ $item->target }}"
       class="py-2.5 rounded-md text-base font-medium text-text hover:bg-bg-subtle transition-base"
       style="padding-left: {{ $paddingLeft }}px;">
        {{ $item->label }}
    </a>
@endif