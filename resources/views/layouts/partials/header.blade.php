@php
    $siteName = \App\Models\Setting::get('site_name', 'Rung Adventure');
    $logo     = \App\Models\Setting::get('site_logo');
    $hotline  = \App\Models\Setting::get('contact_phone', '0987 654 321');
@endphp

<header
    data-header
    x-data="{
        mobileOpen: false,
        scrolled: false,
        isHome: document.body.classList.contains('page-home')
    }"
    x-init="
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 }, { passive: true });
        scrolled = window.scrollY > 50;
    "
    :class="{ 'header-transparent': isHome && !scrolled && !mobileOpen }"
    class="sticky top-0 z-sticky bg-bg/95 backdrop-blur-sm border-b border-border transition-all duration-300"
>
    <div class="container-base">
        {{-- Giảm chiều cao: h-14 lg:h-16 (trước là h-16 lg:h-20) --}}
        <div class="flex items-center justify-between h-14 lg:h-16">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 flex-shrink-0 header-logo" aria-label="{{ $siteName }}">
                @if ($logo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}"
                         alt="{{ $siteName }}"
                         class="h-8 lg:h-9 w-auto">
                @else
                    <div class="header-logo-text flex flex-col items-center justify-center leading-none">
                        <svg class="w-6 h-6 mb-0.5 text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 22h20L12 2zm0 4.5l6.5 13h-13L12 6.5z"/>
                        </svg>
                        <span class="font-heading text-base lg:text-lg font-bold tracking-widest uppercase">
                            {{ $siteName }}
                        </span>
                    </div>
                @endif
            </a>

            {{-- Navigation desktop: gap-6 -> gap-5, thêm text-sm --}}
            <nav class="hidden lg:flex items-center gap-5 text-sm header-nav" aria-label="Menu chính">
                @if ($mainMenu)
                    @foreach ($mainMenu->items as $item)
                        @include('layouts.partials.menu-item-desktop', ['item' => $item])
                    @endforeach
                @endif
            </nav>

            {{-- Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                {{-- Icon Search --}}
                <button class="header-nav text-text-muted hover:text-primary transition-colors" aria-label="Tìm kiếm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                </button>

                {{-- Hotline --}}
                <div class="flex items-center gap-1.5 header-nav">
                    <div class="w-7 h-7 rounded-full border border-text-muted/30 flex items-center justify-center text-text-muted">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="text-[9px] uppercase text-text-muted">Hotline</span>
                        <span class="text-xs font-bold">{{ $hotline }}</span>
                    </div>
                </div>

                {{-- Nút CTA: px-6 py-2 text-sm -> px-4 py-1.5 text-xs --}}
                <a href="{{ url('/lien-he') }}" class="bg-primary text-white rounded-full px-4 py-1.5 text-xs font-semibold hover:bg-primary-hover transition-colors flex items-center gap-1.5">
                    Đặt tour ngay
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            {{-- Mobile toggle --}}
            <button
                type="button"
                @click="mobileOpen = !mobileOpen"
                class="lg:hidden p-1.5 rounded-md text-text hover:bg-bg-subtle transition-base header-mobile-toggle"
                aria-label="Mở menu"
                :aria-expanded="mobileOpen.toString()"
            >
                <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- Mobile menu --}}
    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-border bg-bg absolute w-full shadow-xl"
    >
        <nav class="container-base py-3 flex flex-col gap-1.5 text-sm" aria-label="Menu di động">
            @if ($mainMenu)
                @foreach ($mainMenu->items as $item)
                    @include('layouts.partials.menu-item-mobile', ['item' => $item, 'level' => 1])
                @endforeach
            @endif

            <div class="mt-3 pt-3 border-t border-border flex flex-col gap-3">
                <x-button href="{{ url('/lien-he') }}" variant="primary" size="sm" block>
                    Đặt tour ngay
                </x-button>
            </div>
        </nav>
    </div>
</header>