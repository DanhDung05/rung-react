@php
    $url      = $item->resolved_url;
    $path     = parse_url($url, PHP_URL_PATH) ?? '/';
    $isActive = $path === '/'
        ? request()->is('/')
        : request()->is(ltrim($path, '/') . '*');
    $hasChildren = $item->children->isNotEmpty();
@endphp

@if ($hasChildren)
    {{-- Item có dropdown --}}
    <div class="relative" x-data="{ open: false }"
         @mouseenter="open = true" @mouseleave="open = false">
        <button type="button"
                class="px-3 py-2 rounded-md text-sm font-medium transition-base inline-flex items-center gap-1
                       {{ $isActive ? 'text-primary bg-primary-light' : 'text-text hover:text-primary hover:bg-bg-subtle' }}">
            {{ $item->label }}
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute left-0 top-full mt-1 min-w-[200px] bg-bg border border-border rounded-lg shadow-lg py-2 z-50">
            @foreach ($item->children as $child)
                @if ($child->children->isNotEmpty())
                    {{-- Cấp 3 — submenu --}}
                    <div class="relative" x-data="{ subopen: false }"
                         @mouseenter="subopen = true" @mouseleave="subopen = false">
                        <a href="{{ $child->resolved_url }}"
                           target="{{ $child->target }}"
                           class="flex items-center justify-between px-4 py-2 text-sm text-text hover:bg-bg-subtle hover:text-primary transition-base">
                            {{ $child->label }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <div x-show="subopen" x-cloak
                             class="absolute left-full top-0 ml-1 min-w-[180px] bg-bg border border-border rounded-lg shadow-lg py-2 z-50">
                            @foreach ($child->children as $grandchild)
                                <a href="{{ $grandchild->resolved_url }}"
                                   target="{{ $grandchild->target }}"
                                   class="block px-4 py-2 text-sm text-text hover:bg-bg-subtle hover:text-primary transition-base">
                                    {{ $grandchild->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Cấp 2 thường --}}
                    <a href="{{ $child->resolved_url }}"
                       target="{{ $child->target }}"
                       class="block px-4 py-2 text-sm text-text hover:bg-bg-subtle hover:text-primary transition-base">
                        {{ $child->label }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@else
    {{-- Item thường --}}
    <a href="{{ $url }}"
       target="{{ $item->target }}"
       class="px-3 py-2 rounded-md text-sm font-medium transition-base
              {{ $isActive
                  ? 'text-primary bg-primary-light'
                  : 'text-text hover:text-primary hover:bg-bg-subtle' }}">
        {{ $item->label }}
    </a>
@endif