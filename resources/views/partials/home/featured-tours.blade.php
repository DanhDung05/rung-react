{{-- ============================================================
     FEATURED TOURS
     ============================================================ --}}

@if ($tours->count() > 0)
    <section id="featured-tours" class="py-16 lg:py-24 bg-bg-subtle">
        <div class="container-base">

            {{-- Header --}}
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div data-aos="fade-up">
                    <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">
                        Hành trình nổi bật
                    </p>
                    <h2 class="font-heading text-3xl lg:text-4xl font-bold">
                        Khám phá ngay
                    </h2>
                </div>

                <x-button href="/tour" variant="outline" data-aos="fade-up">
                    Xem tất cả
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </x-button>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($tours as $tour)
                    <article class="tour-card group bg-bg rounded-xl overflow-hidden shadow-sm border border-border hover:shadow-lg transition-all duration-300 h-full flex flex-col"
                             data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

                        {{-- Image --}}
                        <a href="/tour/{{ $tour->slug }}" class="block relative aspect-[4/3] overflow-hidden">
                            @if ($tour->featured_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) }}"
                                     alt="{{ $tour->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=600&h=450&fit=crop"
                                     alt="{{ $tour->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif

                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                @if ($tour->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary text-white text-xs font-semibold">
                                        Nổi bật
                                    </span>
                                @endif
                                @if ($tour->difficulty)
                                    @php
                                        $diffMap = [
                                            'easy' => ['label' => 'Dễ', 'class' => 'bg-success'],
                                            'moderate' => ['label' => 'Trung bình', 'class' => 'bg-info'],
                                            'hard' => ['label' => 'Khó', 'class' => 'bg-warning'],
                                            'expert' => ['label' => 'Chuyên gia', 'class' => 'bg-danger'],
                                        ];
                                        $diff = $diffMap[$tour->difficulty] ?? null;
                                    @endphp
                                    @if ($diff)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full {{ $diff['class'] }} text-white text-xs font-semibold">
                                            {{ $diff['label'] }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </a>

                        {{-- Content --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                @if ($tour->category)
                                    <a href="/tour/danh-muc/{{ $tour->category->slug }}"
                                       class="text-xs text-primary font-medium uppercase tracking-wide hover:underline">
                                        {{ $tour->category->name }}
                                    </a>
                                @endif

                                <h3 class="font-heading text-lg font-bold mt-2 mb-3 line-clamp-2">
                                    <a href="/tour/{{ $tour->slug }}" class="hover:text-primary transition-base">
                                        {{ $tour->name }}
                                    </a>
                                </h3>

                                @if ($tour->excerpt)
                                    <p class="text-sm text-text-muted line-clamp-2 mb-4">
                                        {{ $tour->excerpt }}
                                    </p>
                                @endif

                                {{-- Meta --}}
                                <div class="flex items-center gap-4 text-xs text-text-muted mb-4">
                                    @if ($tour->duration_days)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $tour->duration_days }}N{{ $tour->duration_nights }}Đ
                                        </span>
                                    @endif
                                    @if ($tour->distance_km)
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            {{ number_format($tour->distance_km, 0) }}km
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Price + CTA --}}
                            <div class="flex items-center justify-between pt-4 border-t border-border">
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

                                <a href="/tour/{{ $tour->slug }}"
                                   class="inline-flex items-center gap-1 text-sm text-primary font-medium hover:gap-2 transition-all">
                                    Chi tiết
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

        </div>
    </section>
@endif