{{-- ============================================================
     FEATURED DESTINATIONS
     ============================================================ --}}

@if ($destinations->count() > 0)
    <section class="py-16 lg:py-24 bg-bg">
        <div class="container-base">

            {{-- Header --}}
            <div class="text-center mb-12" data-aos="fade-up">
                <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">
                    Điểm đến
                </p>
                <h2 class="font-heading text-3xl lg:text-4xl font-bold mb-4">
                    Khám phá Việt Nam
                </h2>
                <p class="text-text-muted max-w-2xl mx-auto">
                    Những điểm đến tuyệt đẹp đang chờ bạn khám phá
                </p>
            </div>

            {{-- Grid: Đổi 6 cột thành 3 cột --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($destinations as $destination)
                    <a href="/diem-den/{{ $destination->slug }}"
                       class="destination-card group relative aspect-[4/5] rounded-xl overflow-hidden shadow-sm"
                       data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

                        @if ($destination->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                                 alt="{{ $destination->name }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <img src="https://images.unsplash.com/photo-1528127269322-539801943592?w=600&h=750&fit=crop"
                                 alt="{{ $destination->name }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @endif

                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                        {{-- Content --}}
                        <div class="absolute inset-x-0 bottom-0 p-6">
                            <div class="font-heading text-xl font-bold text-white mb-1">
                                {{ $destination->name }}
                            </div>
                            @if ($destination->tours_count > 0)
                                <p class="text-sm text-white/80">
                                    {{ $destination->tours_count }} hành trình
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>
@endif