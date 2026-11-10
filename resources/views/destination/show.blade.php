@extends('layouts.app')

@php
    $pageKey = 'destination-detail';
    $title = $destination->name;
    $description = $destination->description ?? \Illuminate\Support\Str::limit(strip_tags($destination->content), 160);
    $image = $destination->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) : null;
    $type = 'article';
@endphp

@section('content')

{{-- ============================================================
     HERO BANNER (DÙNG COMPONENT CHUNG)
     ============================================================ --}}
<x-hero-banner
    variant="full"
    alignment="left"
    :image="$destination->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) : null"
    :title="$destination->name"
    :breadcrumb="[
        ['label' => 'Điểm đến', 'url' => route('destinations.index')],
        ...($destination->parent ? [['label' => $destination->parent->name, 'url' => route('destinations.show', $destination->parent->slug)]] : []),
        ['label' => $destination->name],
    ]"
>
    <x-slot:badges>
        @if ($destination->is_featured)
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary text-text-inverse text-xs font-semibold">
                ⭐ Nổi bật
            </span>
        @endif
        @if ($destination->region)
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-text-inverse/20 backdrop-blur-sm text-text-inverse text-xs font-medium">
                {{ $destination->region }}
            </span>
        @endif
    </x-slot:badges>

    <x-slot:meta>
        @if ($destination->province)
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $destination->province }}
            </span>
        @endif
        @if ($destination->altitude)
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                </svg>
                Độ cao {{ number_format($destination->altitude) }} m
            </span>
        @endif
        @if ($destination->tours->count() > 0)
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                {{ $destination->tours->count() }} hành trình
            </span>
        @endif
    </x-slot:meta>
</x-hero-banner>

{{-- ============================================================
     MAIN CONTENT
     ============================================================ --}}
<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

            <div class="lg:col-span-2 space-y-10">
                @if ($destination->description || $destination->content)
                    <div>
                        <h2 class="font-heading text-2xl font-bold mb-4">Tổng quan</h2>
                        @if ($destination->description)
                            <p class="text-lg text-text-muted leading-relaxed mb-4">{{ $destination->description }}</p>
                        @endif
                        @if ($destination->content)
                            <div class="prose prose-lg max-w-none text-text leading-relaxed">
                                {!! $destination->content !!}
                            </div>
                        @endif
                    </div>
                @endif

                @if ($destination->latitude && $destination->longitude)
                    <div>
                        <h2 class="font-heading text-2xl font-bold mb-4">Vị trí</h2>
                        <div class="bg-bg-subtle rounded-lg p-4 flex flex-wrap items-center gap-6 text-sm">
                            <div><span class="text-text-muted">Vĩ độ:</span> <span class="font-mono font-medium">{{ $destination->latitude }}</span></div>
                            <div><span class="text-text-muted">Kinh độ:</span> <span class="font-mono font-medium">{{ $destination->longitude }}</span></div>
                            @if ($destination->altitude)
                                <div><span class="text-text-muted">Độ cao:</span> <span class="font-medium">{{ number_format($destination->altitude) }} m</span></div>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($destination->children->count() > 0)
                    @include('destination.partials.children', ['children' => $destination->children])
                @endif

                @if ($destination->tours->count() > 0)
                    @include('destination.partials.tours', ['tours' => $destination->tours])
                @endif
            </div>

            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <div class="bg-bg rounded-xl border border-border shadow-sm p-6">
                        <div class="font-heading text-base font-semibold mb-4">Thông tin</div>
                        <dl class="space-y-3 text-sm">
                            @if ($destination->region)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Vùng miền</dt>
                                    <dd class="font-medium text-right">{{ $destination->region }}</dd>
                                </div>
                            @endif
                            @if ($destination->province)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Tỉnh/Thành</dt>
                                    <dd class="font-medium text-right">{{ $destination->province }}</dd>
                                </div>
                            @endif
                            @if ($destination->altitude)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Độ cao</dt>
                                    <dd class="font-medium text-right">{{ number_format($destination->altitude) }} m</dd>
                                </div>
                            @endif
                            <div class="flex justify-between gap-3">
                                <dt class="text-text-muted">Số tour</dt>
                                <dd class="font-medium text-right">{{ $destination->tours->count() }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if ($destination->tours->count() > 0)
                        <div class="bg-primary text-text-inverse rounded-xl p-6">
                            <div class="font-heading text-lg font-bold mb-2">Sẵn sàng khám phá?</div>
                            <p class="text-sm text-text-inverse/90 mb-4">Xem các hành trình đến {{ $destination->name }}.</p>
                            <x-button href="#tours-at-destination" variant="secondary" size="md" block>Xem hành trình</x-button>
                        </div>
                    @endif

                    @if ($destination->parent)
                        <div class="bg-bg-subtle rounded-xl p-6">
                            <div class="text-xs text-text-muted uppercase tracking-wide mb-2">Thuộc khu vực</div>
                            <a href="{{ route('destinations.show', $destination->parent->slug) }}"
                               class="font-heading text-base font-semibold text-text hover:text-primary transition-base">
                                {{ $destination->parent->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </aside>

        </div>
    </div>
</section>

{{-- STRUCTURED DATA --}}
@php
    $breadcrumbItems = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Điểm đến', 'url' => route('destinations.index')],
    ];
    if ($destination->parent) {
        $breadcrumbItems[] = ['name' => $destination->parent->name, 'url' => route('destinations.show', $destination->parent->slug)];
    }
    $breadcrumbItems[] = ['name' => $destination->name, 'url' => route('destinations.show', $destination->slug)];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbItems)->map(function ($item, $index) {
            return ['@type' => 'ListItem', 'position' => $index + 1, 'name' => $item['name'], 'item' => $item['url']];
        })->values()->toArray(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@php
    $destinationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TouristDestination',
        'name' => $destination->name,
        'description' => $destination->description ?? \Illuminate\Support\Str::limit(strip_tags($destination->content), 160),
        'address' => array_filter([
            '@type' => 'PostalAddress',
            'addressLocality' => $destination->province,
            'addressRegion' => $destination->region,
            'addressCountry' => 'VN',
        ]),
    ];
    if ($destination->image) {
        $destinationSchema['image'] = \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image);
    }
    if ($destination->latitude && $destination->longitude) {
        $destinationSchema['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $destination->latitude,
            'longitude' => $destination->longitude,
        ];
    }
    $destinationSchema = array_filter($destinationSchema, fn($v) => $v !== null);
@endphp
<script type="application/ld+json">{!! json_encode($destinationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@endsection

@push('styles')
    @vite('resources/css/pages/destination.css')
@endpush