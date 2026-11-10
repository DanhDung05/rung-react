@props([
    'tour',
    'showCategory' => true,
    'showExcerpt' => true,
])

@php
    // Map độ khó
    $difficultyMap = [
        'easy'     => ['label' => 'Dễ', 'class' => 'bg-success'],
        'moderate' => ['label' => 'Trung bình', 'class' => 'bg-info'],
        'hard'     => ['label' => 'Khó', 'class' => 'bg-warning'],
        'expert'   => ['label' => 'Chuyên gia', 'class' => 'bg-danger'],
    ];
    $difficulty = $tour->difficulty ? ($difficultyMap[$tour->difficulty] ?? null) : null;
@endphp

<article {{ $attributes->merge(['class' => 'tour-card group bg-bg rounded-xl overflow-hidden shadow-sm border border-border hover:shadow-lg transition-all duration-300 h-full flex flex-col']) }}>

    {{-- Image --}}
    <a href="{{ route('tours.show', $tour->slug) }}" class="block relative aspect-[4/3] overflow-hidden">
        @if ($tour->featured_image)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) }}"
                 alt="{{ $tour->name }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=600&h=450&fit=crop"
                 alt="{{ $tour->name }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @endif

        {{-- Badges --}}
        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
            @if ($tour->is_featured)
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary text-white text-xs font-semibold shadow-sm">
                    Nổi bật
                </span>
            @endif

            @if ($difficulty)
                <span class="inline-flex items-center px-3 py-1 rounded-full {{ $difficulty['class'] }} text-white text-xs font-semibold shadow-sm">
                    {{ $difficulty['label'] }}
                </span>
            @endif
        </div>

        {{-- View count overlay --}}
        @if ($tour->view_count > 0)
            <div class="absolute bottom-4 right-4 inline-flex items-center gap-1 px-2 py-1 rounded-md bg-black/60 backdrop-blur-sm text-white text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ number_format($tour->view_count) }}
            </div>
        @endif
    </a>

    {{-- Content --}}
    <div class="p-5 flex-1 flex flex-col justify-between">

        <div>
            {{-- Category --}}
            @if ($showCategory && $tour->category)
                <a href="{{ route('tours.index', ['category' => $tour->category->slug]) }}"
                   class="text-xs text-primary font-medium uppercase tracking-wide hover:underline">
                    {{ $tour->category->name }}
                </a>
            @endif

            {{-- Title --}}
            <h3 class="font-heading text-lg font-bold mt-2 mb-3 line-clamp-2">
                <a href="{{ route('tours.show', $tour->slug) }}" class="hover:text-primary transition-base">
                    {{ $tour->name }}
                </a>
            </h3>

            {{-- Excerpt --}}
            @if ($showExcerpt && $tour->excerpt)
                <p class="text-sm text-text-muted line-clamp-2 mb-4">
                    {{ $tour->excerpt }}
                </p>
            @endif

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-text-muted mb-4">
                @if ($tour->duration_days)
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $tour->duration_days }}N{{ $tour->duration_nights }}Đ
                    </span>
                @endif

                @if ($tour->distance_km)
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ number_format($tour->distance_km, 0) }}km
                    </span>
                @endif

                @if ($tour->altitude_max)
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                        </svg>
                        {{ number_format($tour->altitude_max) }}m
                    </span>
                @endif
            </div>
        </div>

        {{-- Price + CTA --}}
        <div class="flex items-center justify-between pt-4 border-t border-border mt-auto">
            @if ($tour->price_reference)
                <div>
                    <p class="text-xs text-text-muted">Từ</p>
                    <p class="font-heading text-lg font-bold text-primary">
                        {{ number_format($tour->price_reference, 0, ',', '.') }}₫
                    </p>
                </div>
            @else
                <span class="text-sm text-text-muted">Liên hệ</span>
            @endif

            <a href="{{ route('tours.show', $tour->slug) }}"
               class="inline-flex items-center gap-1 text-sm text-primary font-medium hover:gap-2 transition-all">
                Chi tiết
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>

</article>