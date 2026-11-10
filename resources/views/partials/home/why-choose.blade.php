{{-- ============================================================
     WHY CHOOSE US
     ============================================================ --}}

<section class="py-16 lg:py-24 bg-bg">
    <div class="container-base">

        {{-- Header --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">
                Vì sao chọn chúng tôi
            </p>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold mb-4">
                Trải nghiệm tuyệt vời
            </h2>
            <p class="text-text-muted max-w-2xl mx-auto">
                Chúng tôi cam kết mang đến cho bạn những hành trình đáng nhớ
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @php
                $reasons = [
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
                        'title' => 'Chất lượng đảm bảo',
                        'desc'  => 'Các hành trình được thiết kế kỹ lưỡng, đảm bảo an toàn và trải nghiệm tốt nhất.',
                    ],
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
                        'title' => 'Hướng dẫn viên chuyên nghiệp',
                        'desc'  => 'Đội ngũ HDV giàu kinh nghiệm, am hiểu địa phương và thân thiện.',
                    ],
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>',
                        'title' => 'Giá cả hợp lý',
                        'desc'  => 'Cam kết giá tốt nhất thị trường với nhiều ưu đãi hấp dẫn.',
                    ],
                    [
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>',
                        'title' => 'Hỗ trợ 24/7',
                        'desc'  => 'Đội ngũ CSKH luôn sẵn sàng hỗ trợ bạn mọi lúc mọi nơi.',
                    ],
                ];
            @endphp

            @foreach ($reasons as $index => $reason)
                <div class="text-center p-6 rounded-xl bg-bg-subtle hover:bg-primary-light/50 transition-base group"
                     data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">

                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary-light text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-text-inverse transition-base">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $reason['icon'] !!}
                        </svg>
                    </div>

                    <h3 class="font-heading text-lg font-bold mb-2">
                        {{ $reason['title'] }}
                    </h3>
                    <p class="text-sm text-text-muted leading-relaxed">
                        {{ $reason['desc'] }}
                    </p>
                </div>
            @endforeach

        </div>
    </div>
</section>