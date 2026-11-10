{{-- ============================================================
     HERO SLIDER — Phong cách đồng bộ với ảnh thiết kế
     ============================================================ --}}

@if ($sliders->count() > 0)
    <section class="hero-section relative w-full h-screen min-h-[600px] overflow-hidden">
        
        @php
            $firstSlider = $sliders->first();
            $imgUrl = $firstSlider->image_url;
            $parseTitle = function($text) {
                return preg_replace('/\*(.*?)\*/', '<span class="font-script text-green-400">$1</span>', e($text));
            };
        @endphp

        {{-- Ảnh nền --}}
        @if ($imgUrl)
            <img src="{{ $imgUrl }}"
                 alt="{{ $firstSlider->title }}"
                 loading="eager"
                 fetchpriority="high"
                 class="absolute inset-0 w-full h-full object-cover z-0">
        @else
            <div class="absolute inset-0 bg-primary z-0"></div>
        @endif

        {{-- Lớp phủ tối --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-black/10 z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>

        {{-- Nội dung chữ --}}
        <div class="relative z-20 container-base w-full h-full flex flex-col justify-center pt-20 pb-32">
            <div class="max-w-3xl">
                
                @if ($firstSlider->subtitle)
                    <p class="text-white text-sm lg:text-base mb-4 font-bold tracking-widest uppercase flex items-center gap-3">
                        <span class="w-8 h-px bg-white"></span>
                        {{ $firstSlider->subtitle }}
                    </p>
                @endif

                @if ($firstSlider->title)
                    {{-- Đã bỏ data-aos để hiện ngay --}}
                    <h1 class="font-heading text-4xl lg:text-7xl font-bold text-white mb-6 leading-[1.1]" 
                        style="text-shadow: 0 4px 16px rgba(0,0,0,0.8);">
                        {!! $parseTitle($firstSlider->title) !!}
                    </h1>
                @endif

                <p class="text-white/90 text-base lg:text-lg mb-8 max-w-xl leading-relaxed">
                    Cùng Rung Adventure khám phá những cung đường trekking tuyệt đẹp, trải nghiệm thiên nhiên hoang sơ và chạm đến những giá trị nguyên bản của cuộc sống.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    {{-- Nút CTA --}}
                    <a href="{{ $firstSlider->link_url ?: route('tours.index') }}" 
                       class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-primary text-white font-semibold hover:bg-primary-hover transition-colors">
                        Khám phá ngay →
                    </a>
                    
                    {{-- Nút Video --}}
                    <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" 
                       class="glightbox inline-flex items-center gap-3 text-white hover:text-primary transition-colors group">
                        <div class="w-12 h-12 rounded-full border-2 border-white/50 flex items-center justify-center group-hover:border-primary transition-colors bg-black/20 backdrop-blur-sm">
                            <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Xem video hành trình</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Đường xé giấy (Torn paper effect) --}}
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-30 translate-y-[1px]">
            <svg class="relative block w-[calc(100%+1.3px)] h-[30px] lg:h-[50px] text-bg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="fill-current"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="fill-current"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="fill-current"></path>
            </svg>
        </div>

    </section>
@else
    {{-- Fallback khi không có slider --}}
    <section class="hero-section relative h-screen bg-primary flex items-center justify-center">
        <div class="text-center z-10 relative">
            <h1 class="font-heading text-4xl lg:text-7xl font-bold text-white mb-6">
                Khám phá Việt Nam
            </h1>
            <p class="text-white/90 text-lg mb-8">
                Những hành trình trekking tuyệt vời đang chờ bạn
            </p>
        </div>
    </section>
@endif