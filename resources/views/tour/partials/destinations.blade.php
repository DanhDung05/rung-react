{{-- ============================================================
     DESTINATIONS
     ============================================================ --}}

<div>
    <h2 class="font-heading text-2xl font-bold mb-6">
        Điểm đến ({{ $destinations->count() }})
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($destinations as $destination)
            <a href="/diem-den/{{ $destination->slug }}"
               class="group flex gap-4 p-4 rounded-lg bg-bg border border-border hover:border-primary hover:shadow-md transition-base">

                {{-- Image --}}
                @if ($destination->image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                         alt="{{ $destination->name }}"
                         class="w-20 h-20 flex-shrink-0 rounded-md object-cover">
                @else
                    <div class="w-20 h-20 flex-shrink-0 rounded-md bg-bg-muted flex items-center justify-center text-text-subtle">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="font-heading text-base font-semibold text-text group-hover:text-primary transition-base">
                        {{ $destination->name }}
                    </div>

                    @if ($destination->region || $destination->province)
                        <div class="text-xs text-text-muted mt-1">
                            @if ($destination->region) {{ $destination->region }} @endif
                            @if ($destination->province)
                                @if ($destination->region) • @endif
                                {{ $destination->province }}
                            @endif
                        </div>
                    @endif

                    @if ($destination->altitude)
                        <div class="text-xs text-text-muted mt-1">
                            ⛰️ Độ cao {{ number_format($destination->altitude) }} m
                        </div>
                    @endif
                </div>

                {{-- Arrow --}}
                <svg class="w-4 h-4 flex-shrink-0 text-text-muted self-center group-hover:text-primary group-hover:translate-x-1 transition-all"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endforeach
    </div>
</div>