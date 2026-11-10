{{-- ============================================================
     ITINERARY — Accordion lịch trình
     ============================================================ --}}

<div>
    <h2 class="font-heading text-2xl font-bold mb-6">
        Lịch trình ({{ $itineraries->count() }} ngày)
    </h2>

    <div class="space-y-3">
        @foreach ($itineraries as $itinerary)
            <details class="itinerary-item group bg-bg rounded-lg border border-border overflow-hidden"
                     @if ($loop->first) open @endif>

                <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer hover:bg-bg-subtle transition-base list-none">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        {{-- Day number --}}
                        <div class="w-12 h-12 flex-shrink-0 rounded-full bg-primary text-text-inverse flex items-center justify-center font-heading font-bold text-sm">
                            {{ $itinerary->day_number }}
                        </div>

                        {{-- Title --}}
                        <div class="flex-1 min-w-0">
                            <div class="font-heading text-base font-semibold">
                                Ngày {{ $itinerary->day_number }}: {{ $itinerary->title }}
                            </div>

                            {{-- Quick meta --}}
                            <div class="flex flex-wrap items-center gap-3 text-xs text-text-muted mt-1">
                                @if ($itinerary->distance_km)
                                    <span>🚶 {{ number_format($itinerary->distance_km, 0) }} km</span>
                                @endif
                                @if ($itinerary->altitude)
                                    <span>⛰️ {{ number_format($itinerary->altitude) }} m</span>
                                @endif
                                @if ($itinerary->meals)
                                    <span>🍽️ {{ $itinerary->meals }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-5 h-5 flex-shrink-0 text-text-muted transition-transform group-open:rotate-180"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>

                <div class="px-5 pb-5 pt-0 border-t border-border">
                    <div class="pt-4 space-y-3 text-sm">
                        @if ($itinerary->description)
                            <p class="text-text leading-relaxed whitespace-pre-line">
                                {{ $itinerary->description }}
                            </p>
                        @endif

                        {{-- Detail meta --}}
                        @if ($itinerary->accommodation || $itinerary->meals)
                            <div class="flex flex-wrap gap-4 pt-3 border-t border-border text-xs text-text-muted">
                                @if ($itinerary->accommodation)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        {{ $itinerary->accommodation }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </details>
        @endforeach
    </div>
</div>