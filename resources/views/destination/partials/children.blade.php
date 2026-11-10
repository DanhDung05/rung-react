{{-- ============================================================
     CHILDREN — Điểm đến con
     ============================================================ --}}

<div>
    <h2 class="font-heading text-2xl font-bold mb-6">
        Điểm đến trong khu vực ({{ $children->count() }})
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($children as $child)
            <a href="{{ route('destinations.show', $child->slug) }}"
               class="group flex gap-4 p-4 rounded-lg bg-bg border border-border hover:border-primary hover:shadow-md transition-base">

                @if ($child->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($child->image) }}"
                         alt="{{ $child->name }}"
                         class="w-20 h-20 flex-shrink-0 rounded-md object-cover">
                @else
                    <div class="w-20 h-20 flex-shrink-0 rounded-md bg-bg-muted flex items-center justify-center text-text-subtle">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="font-heading text-base font-semibold text-text group-hover:text-primary transition-base">
                        {{ $child->name }}
                    </div>

                    @if ($child->province || $child->altitude)
                        <div class="text-xs text-text-muted mt-1">
                            @if ($child->province) {{ $child->province }} @endif
                            @if ($child->altitude)
                                @if ($child->province) • @endif
                                {{ number_format($child->altitude) }} m
                            @endif
                        </div>
                    @endif
                </div>

                <svg class="w-4 h-4 flex-shrink-0 text-text-muted self-center group-hover:text-primary group-hover:translate-x-1 transition-all"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>
</div>