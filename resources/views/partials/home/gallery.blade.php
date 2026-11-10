{{-- ============================================================
     GALLERY
     ============================================================ --}}

@php
    $galleryTours = \App\Models\Tour::published()
        ->whereNotNull('featured_image')
        ->limit(6)
        ->get();
@endphp

@if ($galleryTours->count() > 0)
    <section class="py-16 lg:py-24 bg-bg-subtle">
        <div class="container-base">

            <div class="text-center mb-12" data-aos="fade-up">
                <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">
                    Thư viện
                </p>
                <h2 class="font-heading text-3xl lg:text-4xl font-bold mb-4">
                    Khoảnh khắc đáng nhớ
                </h2>
                <p class="text-text-muted max-w-2xl mx-auto">
                    Những hình ảnh đẹp nhất từ các hành trình của chúng tôi
                </p>
            </div>

            {{-- Gallery: 3 cột mobile, 4 cột desktop, aspect vuông --}}
            <div id="home-gallery" data-gallery class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4">
                @foreach ($galleryTours as $tour)
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) }}"
                       class="glightbox relative aspect-square rounded-lg overflow-hidden group"
                       data-gallery="home"
                       data-aos="fade-up"
                       data-aos-delay="{{ $loop->index * 50 }}">

                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) }}"
                             alt="{{ $tour->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-300 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>
@endif