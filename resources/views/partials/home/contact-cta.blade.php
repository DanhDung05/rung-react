{{-- ============================================================
     CONTACT CTA
     ============================================================ --}}

<section class="py-16 lg:py-24 bg-primary relative overflow-hidden">
    {{-- Decoration circles --}}
    <div class="absolute -top-48 -right-48 w-96 h-96 bg-white/5 rounded-full pointer-events-none" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -left-32 w-64 h-64 bg-white/5 rounded-full pointer-events-none" aria-hidden="true"></div>

    <div class="container-base relative">
        <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">

            <div class="font-heading text-3xl lg:text-4xl font-bold text-white mb-4">
                Sẵn sàng khám phá?
            </div>

            <p class="text-white/90 text-lg mb-8">
                Liên hệ với chúng tôi ngay để được tư vấn về những hành trình tuyệt vời nhất.
            </p>

            <div class="flex flex-wrap gap-3 justify-center">
                <x-button href="/tour" variant="secondary" size="lg">
                    Khám phá hành trình
                </x-button>
                <a href="/lien-he"
                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-lg font-medium border-2 border-white text-white bg-white/10 hover:bg-white/20 transition-colors">
                    Liên hệ ngay
                </a>
            </div>

            <div class="flex flex-wrap gap-6 justify-center mt-10 text-sm text-white/80">
                @php $email = \App\Models\Setting::get('contact_email'); @endphp
                @if ($email)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $email }}
                    </div>
                @endif

                @php $phone = \App\Models\Setting::get('contact_phone'); @endphp
                @if ($phone)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $phone }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>