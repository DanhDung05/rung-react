{{-- ============================================================
     INTRO SECTION
     ============================================================ --}}

<section class="py-16 lg:py-24 bg-bg overflow-hidden">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Text --}}
            <div data-aos="fade-right">
                <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-3">
                    Về chúng tôi
                </p>

                <h1 class="font-heading text-3xl lg:text-4xl font-bold mb-6 leading-tight">
                    {{ \App\Models\Setting::get('site_name', 'Rung Adventure') }}
                </h1>

                <p class="text-text-muted text-lg leading-relaxed mb-6">
                    {{ \App\Models\Setting::get('site_description', 'Chúng tôi mang đến những hành trình trekking tuyệt vời, giúp bạn khám phá vẻ đẹp hoang sơ của thiên nhiên Việt Nam.') }}
                </p>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div>
                        <div class="font-heading text-3xl lg:text-4xl font-bold text-primary mb-1">
                            {{ \App\Models\Tour::published()->count() }}+
                        </div>
                        <div class="text-sm text-text-muted">Hành trình</div>
                    </div>
                    <div>
                        <div class="font-heading text-3xl lg:text-4xl font-bold text-primary mb-1">
                            {{ \App\Models\Destination::published()->count() }}+
                        </div>
                        <div class="text-sm text-text-muted">Điểm đến</div>
                    </div>
                    <div>
                        <div class="font-heading text-3xl lg:text-4xl font-bold text-primary mb-1">
                            {{ \App\Models\Post::published()->count() }}+
                        </div>
                        <div class="text-sm text-text-muted">Bài viết</div>
                    </div>
                </div>

                <x-button href="/gioi-thieu" variant="primary" size="lg">
                    Tìm hiểu thêm
                </x-button>
            </div>

            {{-- Image --}}
            <div class="relative overflow-hidden" data-aos="fade-left">
                <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-lg">
                    @php
                        $aboutImage = \App\Models\Page::where('slug', 'gioi-thieu')->first()?->featured_image;
                    @endphp
                    @if ($aboutImage)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($aboutImage) }}"
                             alt="Về chúng tôi"
                             class="w-full h-full object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop"
                             alt="Về chúng tôi"
                             class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-primary-light rounded-2xl -z-10"></div>
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-secondary/20 rounded-full -z-10"></div>
            </div>

        </div>
    </div>
</section>