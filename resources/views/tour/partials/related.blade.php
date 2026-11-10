{{-- ============================================================
     RELATED TOURS
     ============================================================ --}}

<section class="py-16 lg:py-20 bg-bg-subtle">
    <div class="container-base">

        <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">
                    Có thể bạn thích
                </p>
                <h2 class="font-heading text-2xl lg:text-3xl font-bold">
                    Hành trình tương tự
                </h2>
            </div>

            <x-button href="{{ route('tours.index') }}" variant="outline">
                Xem tất cả
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </x-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tours as $tour)
                <x-tour-card :tour="$tour" />
            @endforeach
        </div>

    </div>
</section>