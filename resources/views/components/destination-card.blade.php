@props([
    'destination',
    'variant' => 'default',
])

@php
    $toursCount = $destination->tours_count ?? $destination->tours()->count();
    $altitude = $destination->altitude;
@endphp

@if ($variant === 'compact')
    {{-- ============================================================
         COMPACT — Dùng cho grid nhỏ, ít thông tin
         ============================================================ --}}
    <a href="{{ route('destinations.show', $destination->slug) }}"
       {{ $attributes->merge(['class' => 'group relative aspect-square rounded-xl overflow-hidden shadow-sm block']) }}>

        @if ($destination->image)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                 alt="{{ $destination->name }}"
                 loading="lazy"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-hover"></div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-text/80 via-text/20 to-transparent"></div>

        <div class="absolute inset-x-0 bottom-0 p-4">
            <div class="font-heading text-lg font-bold text-text-inverse mb-1">
                {{ $destination->name }}
            </div>
            @if ($toursCount > 0)
                <p class="text-xs text-text-inverse/80">
                    {{ $toursCount }} hành trình
                </p>
            @endif
        </div>
    </a>

@elseif ($variant === 'featured')
    {{-- ============================================================
         FEATURED — Card lớn, đầy đủ thông tin
         ============================================================ --}}
    <article {{ $attributes->merge(['class' => 'group bg-bg rounded-xl overflow-hidden shadow-sm border border-border hover:shadow-lg transition-all duration-300']) }}>

        <a href="{{ route('destinations.show', $destination->slug) }}"
           class="block relative aspect-[4/3] overflow-hidden">

            @if ($destination->image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                     alt="{{ $destination->name }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gradient-to-br from-primary to-primary-hover flex items-center justify-center">
                    <svg class="w-16 h-16 text-text-inverse/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            @endif

            @if ($destination->is_featured)
                <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-secondary text-text-inverse text-xs font-semibold">
                    Nổi bật
                </span>
            @endif

            @if ($toursCount > 0)
                <span class="absolute bottom-4 right-4 inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-text/60 backdrop-blur-sm text-text-inverse text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    {{ $toursCount }} tour
                </span>
            @endif
        </a>

        <div class="p-5">
            @if ($destination->region || $destination->province)
                <p class="text-xs text-primary font-medium uppercase tracking-wide mb-2">
                    {{ $destination->region ?? '' }}
                    @if ($destination->region && $destination->province) • @endif
                    {{ $destination->province ?? '' }}
                </p>
            @endif

            <h3 class="font-heading text-lg font-bold mb-2 line-clamp-2">
                <a href="{{ route('destinations.show', $destination->slug) }}" class="hover:text-primary transition-base">
                    {{ $destination->name }}
                </a>
            </h3>

            @if ($destination->description)
                <p class="text-sm text-text-muted line-clamp-2 mb-4">
                    {{ $destination->description }}
                </p>
            @endif

            <div class="flex items-center justify-between pt-4 border-t border-border">
                @if ($altitude)
                    <span class="flex items-center gap-1 text-xs text-text-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                        </svg>
                        {{ number_format($altitude) }} m
                    </span>
                @else
                    <span class="text-xs text-text-muted">Khám phá</span>
                @endif

                <a href="{{ route('destinations.show', $destination->slug) }}"
                   class="inline-flex items-center gap-1 text-sm text-primary font-medium hover:gap-2 transition-all">
                    Chi tiết
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </article>

@else
    {{-- ============================================================
         DEFAULT — Card tiêu chuẩn
         ============================================================ --}}
    <article {{ $attributes->merge(['class' => 'group bg-bg rounded-xl overflow-hidden shadow-sm border border-border hover:shadow-lg transition-all duration-300']) }}>

        <a href="{{ route('destinations.show', $destination->slug) }}"
           class="block relative aspect-[4/3] overflow-hidden">

            @if ($destination->image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                     alt="{{ $destination->name }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-bg-muted flex items-center justify-center">
                    <svg class="w-16 h-16 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            @endif

            @if ($destination->is_featured)
                <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-secondary text-text-inverse text-xs font-semibold">
                    Nổi bật
                </span>
            @endif
        </a>

        <div class="p-5">
            @if ($destination->region)
                <p class="text-xs text-primary font-medium uppercase tracking-wide mb-2">
                    {{ $destination->region }}
                </p>
            @endif

            <h3 class="font-heading text-lg font-bold mb-2 line-clamp-2">
                <a href="{{ route('destinations.show', $destination->slug) }}" class="hover:text-primary transition-base">
                    {{ $destination->name }}
                </a>
            </h3>

            @if ($toursCount > 0)
                <p class="text-xs text-text-muted">
                    {{ $toursCount }} hành trình
                </p>
            @endif
        </div>
    </article>
@endif