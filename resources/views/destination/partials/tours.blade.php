{{-- ============================================================
     TOURS AT DESTINATION — Tour liên kết
     ============================================================ --}}

<div id="tours-at-destination">
    <h2 class="font-heading text-2xl font-bold mb-6">
        Hành trình tại đây ({{ $tours->count() }})
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($tours as $tour)
            <x-tour-card :tour="$tour" />
        @endforeach
    </div>
</div>