{{-- ============================================================
     TOUR GALLERY
     ============================================================ --}}

@if ($tour->media->count() > 0)
    <div>
        <h2 class="font-heading text-2xl font-bold mb-6">Thư viện ảnh</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($tour->media as $media)
                <a href="{{ $media->url }}"
                   class="glightbox relative aspect-square rounded-xl overflow-hidden group"
                   data-gallery="tour-{{ $tour->id }}">

                    <img src="{{ $media->url }}"
                         alt="{{ $media->alt_text ?? $tour->name }}"
                         loading="lazy"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif