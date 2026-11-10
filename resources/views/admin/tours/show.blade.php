@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $tour->name }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết tour</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.tours.edit', $tour) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.tours.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

        {{-- Thông tin cơ bản --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin cơ bản</h3>

            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $tour->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Slug</dt>
                    <dd class="col-span-2 font-mono text-xs">{{ $tour->slug }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Danh mục</dt>
                    <dd class="col-span-2">{{ $tour->category->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Trạng thái</dt>
                    <dd class="col-span-2">
                        @if ($tour->status === 'published')
                            <x-badge variant="success">Đã xuất bản</x-badge>
                        @else
                            <x-badge variant="default">Nháp</x-badge>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Thông số --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông số hành trình</h3>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-text-muted text-xs mb-1">Số ngày</dt>
                    <dd class="font-medium">{{ $tour->duration_days ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted text-xs mb-1">Số đêm</dt>
                    <dd class="font-medium">{{ $tour->duration_nights ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted text-xs mb-1">Quãng đường</dt>
                    <dd class="font-medium">{{ $tour->distance_km ? $tour->distance_km . ' km' : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted text-xs mb-1">Độ cao</dt>
                    <dd class="font-medium">
                        @if ($tour->altitude_min || $tour->altitude_max)
                            {{ $tour->altitude_min ?? '?' }} - {{ $tour->altitude_max ?? '?' }} m
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-text-muted text-xs mb-1">Độ khó</dt>
                    <dd class="font-medium">{{ $tour->difficulty ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted text-xs mb-1">Giá tham khảo</dt>
                    <dd class="font-medium">
                        {{ $tour->price_reference ? number_format($tour->price_reference, 0, ',', '.') . ' ₫' : '—' }}
                    </dd>
                </div>
            </div>
        </div>

        {{-- Lịch trình --}}
        @if ($tour->itineraries->count() > 0)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Lịch trình ({{ $tour->itineraries->count() }} ngày)</h3>

                <div class="space-y-4">
                    @foreach ($tour->itineraries as $itinerary)
                        <div class="border-l-4 border-primary pl-4 py-1">
                            <div class="font-semibold text-sm mb-1">
                                Ngày {{ $itinerary->day_number }}: {{ $itinerary->title }}
                            </div>
                            @if ($itinerary->description)
                                <p class="text-sm text-text-muted mb-2">{{ $itinerary->description }}</p>
                            @endif
                            <div class="flex flex-wrap gap-3 text-xs text-text-muted">
                                @if ($itinerary->distance_km)
                                    <span>🚶 {{ $itinerary->distance_km }} km</span>
                                @endif
                                @if ($itinerary->altitude)
                                    <span>⛰️ {{ $itinerary->altitude }} m</span>
                                @endif
                                @if ($itinerary->meals)
                                    <span>🍽️ {{ $itinerary->meals }}</span>
                                @endif
                                @if ($itinerary->accommodation)
                                    <span>🏠 {{ $itinerary->accommodation }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- Cột phải --}}
    <div class="space-y-6">

        @if ($tour->featured_image)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) }}"
                     alt="{{ $tour->name }}"
                     class="w-full aspect-video object-cover rounded-md">
            </div>
        @endif

        {{-- Điểm đến --}}
        @if ($tour->destinations->count() > 0)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Điểm đến ({{ $tour->destinations->count() }})</h3>
                <ul class="space-y-2 text-sm">
                    @foreach ($tour->destinations as $destination)
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                            {{ $destination->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

</div>

@endsection