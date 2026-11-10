@extends('layouts.app')

@php
    $pageKey = 'tour-detail';
    $title = $tour->name;
    $description = $tour->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($tour->content), 160);
    $image = $tour->featured_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) : null;
    $type = 'article';
@endphp

@section('content')

{{-- HERO BANNER --}}
<x-hero-banner
    variant="full"
    alignment="left"
    :image="$tour->featured_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image) : null"
    :title="$tour->name"
    :breadcrumb="[
        ['label' => 'Hành trình', 'url' => route('tours.index')],
        ...($tour->category ? [['label' => $tour->category->name, 'url' => route('tours.index', ['category' => $tour->category->slug])]] : []),
        ['label' => $tour->name],
    ]"
>
    <x-slot:badges>
        @if ($tour->is_featured)
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary text-white text-xs font-semibold shadow-sm">
                ⭐ Nổi bật
            </span>
        @endif

        @if ($tour->difficulty)
            @php
                $diffMap = [
                    'easy' => ['label' => 'Dễ', 'class' => 'bg-success'],
                    'moderate' => ['label' => 'Trung bình', 'class' => 'bg-info'],
                    'hard' => ['label' => 'Khó', 'class' => 'bg-warning'],
                    'expert' => ['label' => 'Chuyên gia', 'class' => 'bg-danger'],
                ];
                $diff = $diffMap[$tour->difficulty] ?? null;
            @endphp
            @if ($diff)
                <span class="inline-flex items-center px-3 py-1 rounded-full {{ $diff['class'] }} text-white text-xs font-semibold shadow-sm">
                    {{ $diff['label'] }}
                </span>
            @endif
        @endif
    </x-slot:badges>

    <x-slot:meta>
        @if ($tour->duration_days)
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $tour->duration_days }} ngày {{ $tour->duration_nights }} đêm
            </span>
        @endif

        @if ($tour->distance_km)
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                {{ number_format($tour->distance_km, 0) }} km
            </span>
        @endif

        @if ($tour->altitude_max)
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                </svg>
                Đỉnh {{ number_format($tour->altitude_max) }} m
            </span>
        @endif

        <span class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            {{ number_format($tour->view_count) }} lượt xem
        </span>
    </x-slot:meta>
</x-hero-banner>

{{-- MAIN CONTENT --}}
<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

            {{-- CỘT TRÁI --}}
            <div class="lg:col-span-2 space-y-12"> {{-- Tăng space-y lên 12 cho thoáng --}}

                {{-- Tổng quan --}}
                @if ($tour->excerpt || $tour->content)
                    <div>
                        <h2 class="font-heading text-2xl font-bold mb-4">Tổng quan</h2>
                        @if ($tour->excerpt)
                            <p class="text-lg text-text-muted leading-relaxed mb-6">{{ $tour->excerpt }}</p>
                        @endif
                        @if ($tour->content)
                            {{-- Đã cấu hình kỹ prose để hiển thị đẹp --}}
                            <div class="prose prose-lg max-w-none text-text
                                        prose-headings:font-heading prose-headings:text-text
                                        prose-p:leading-relaxed prose-p:mb-4
                                        prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                                        prose-img:rounded-xl prose-img:shadow-md
                                        prose-ul:list-disc prose-ul:pl-5 prose-ul:my-4
                                        prose-ol:list-decimal prose-ol:pl-5 prose-ol:my-4
                                        prose-li:my-1
                                        prose-blockquote:border-l-4 prose-blockquote:border-primary prose-blockquote:pl-4 prose-blockquote:italic prose-blockquote:text-text-muted">
                                {!! $tour->content !!}
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Thông số hành trình --}}
                <div>
                    <h2 class="font-heading text-2xl font-bold mb-6">Thông số hành trình</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if ($tour->duration_days)
                            <div class="bg-bg-subtle rounded-xl p-5 text-center hover:bg-primary-light transition-base border border-border">
                                <svg class="w-8 h-8 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div class="text-xs text-text-muted mb-1">Thời gian</div>
                                <div class="font-heading font-bold text-lg">{{ $tour->duration_days }}N{{ $tour->duration_nights }}Đ</div>
                            </div>
                        @endif
                        @if ($tour->distance_km)
                            <div class="bg-bg-subtle rounded-xl p-5 text-center hover:bg-primary-light transition-base border border-border">
                                <svg class="w-8 h-8 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <div class="text-xs text-text-muted mb-1">Quãng đường</div>
                                <div class="font-heading font-bold text-lg">{{ number_format($tour->distance_km, 0) }} km</div>
                            </div>
                        @endif
                        @if ($tour->altitude_max)
                            <div class="bg-bg-subtle rounded-xl p-5 text-center hover:bg-primary-light transition-base border border-border">
                                <svg class="w-8 h-8 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/></svg>
                                <div class="text-xs text-text-muted mb-1">Độ cao</div>
                                <div class="font-heading font-bold text-lg">{{ number_format($tour->altitude_max) }} m</div>
                            </div>
                        @endif
                        @if ($tour->difficulty)
                            <div class="bg-bg-subtle rounded-xl p-5 text-center hover:bg-primary-light transition-base border border-border">
                                <svg class="w-8 h-8 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <div class="text-xs text-text-muted mb-1">Độ khó</div>
                                <div class="font-heading font-bold text-lg">{{ $diff['label'] ?? $tour->difficulty }}</div>
                            </div>
                        @endif
                        @if ($tour->start_point)
                            <div class="bg-bg-subtle rounded-xl p-5 text-center hover:bg-primary-light transition-base border border-border">
                                <svg class="w-8 h-8 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <div class="text-xs text-text-muted mb-1">Khởi hành</div>
                                <div class="font-heading font-bold text-sm">{{ $tour->start_point }}</div>
                            </div>
                        @endif
                        @if ($tour->best_time)
                            <div class="bg-bg-subtle rounded-xl p-5 text-center hover:bg-primary-light transition-base border border-border">
                                <svg class="w-8 h-8 mx-auto mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <div class="text-xs text-text-muted mb-1">Thời điểm</div>
                                <div class="font-heading font-bold text-sm">{{ $tour->best_time }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Gallery --}}
                @if ($tour->media->count() > 0)
                    @include('tour.partials.gallery', ['tour' => $tour])
                @endif

                {{-- Lịch trình --}}
                @if ($tour->itineraries->count() > 0)
                    @include('tour.partials.itinerary', ['itineraries' => $tour->itineraries])
                @endif

                {{-- Điểm đến --}}
                @if ($tour->destinations->count() > 0)
                    @include('tour.partials.destinations', ['destinations' => $tour->destinations])
                @endif

                {{-- Lưu ý --}}
                @if ($tour->notes)
                    <div>
                        <h2 class="font-heading text-2xl font-bold mb-4">Lưu ý & Chuẩn bị</h2>
                        <div class="bg-warning/10 border-l-4 border-warning rounded-xl p-6">
                            <div class="prose prose-sm max-w-none text-text leading-relaxed">
                                {!! nl2br(e($tour->notes)) !!}
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- CỘT PHẢI --}}
            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-6">

                    {{-- Price + CTA --}}
                    <div class="bg-bg rounded-xl border border-border shadow-sm p-6">
                        <div class="mb-4">
                            <p class="text-sm text-text-muted mb-1">Giá tham khảo</p>
                            @if ($tour->price_reference)
                                <div class="font-heading text-3xl font-bold text-primary">
                                    {{ number_format($tour->price_reference, 0, ',', '.') }}₫
                                </div>
                                <p class="text-xs text-text-muted mt-1">/ người</p>
                            @else
                                <div class="font-heading text-2xl font-bold text-text">Liên hệ</div>
                            @endif
                        </div>

                        <div class="space-y-2 pt-4 border-t border-border">
                            <x-button href="/lien-he" variant="primary" size="lg" block>
                                Liên hệ tư vấn
                            </x-button>
                            <a href="tel:{{ \App\Models\Setting::get('contact_phone') }}"
                               class="block w-full text-center px-6 py-3 rounded-lg border-2 border-primary text-primary font-semibold hover:bg-primary hover:text-white transition-colors">
                                Gọi ngay
                            </a>
                        </div>
                    </div>

                    {{-- Quick info --}}
                    <div class="bg-bg-subtle rounded-xl p-6">
                        <div class="font-heading text-base font-semibold mb-4">Thông tin nhanh</div>
                        <dl class="space-y-3 text-sm">
                            @if ($tour->category)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Danh mục</dt>
                                    <dd class="font-medium text-right">{{ $tour->category->name }}</dd>
                                </div>
                            @endif
                            @if ($tour->difficulty)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Độ khó</dt>
                                    <dd class="font-medium text-right">{{ $diff['label'] ?? $tour->difficulty }}</dd>
                                </div>
                            @endif
                            @if ($tour->start_point)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Khởi hành</dt>
                                    <dd class="font-medium text-right">{{ $tour->start_point }}</dd>
                                </div>
                            @endif
                            @if ($tour->end_point)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Kết thúc</dt>
                                    <dd class="font-medium text-right">{{ $tour->end_point }}</dd>
                                </div>
                            @endif
                            @if ($tour->best_time)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-text-muted">Thời điểm</dt>
                                    <dd class="font-medium text-right">{{ $tour->best_time }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                </div>
            </aside>

        </div>
    </div>
</section>

{{-- RELATED TOURS --}}
@if ($relatedTours->count() > 0)
    @include('tour.partials.related', ['tours' => $relatedTours])
@endif

{{-- STRUCTURED DATA --}}
@php
    $breadcrumbItems = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Hành trình', 'url' => route('tours.index')],
    ];
    if ($tour->category) {
        $breadcrumbItems[] = ['name' => $tour->category->name, 'url' => route('tours.index', ['category' => $tour->category->slug])];
    }
    $breadcrumbItems[] = ['name' => $tour->name, 'url' => route('tours.show', $tour->slug)];

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
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $tour->name,
        'description' => $tour->excerpt ?? 'Tour du lịch tại Rung Adventure',
        'sku' => 'TOUR-' . $tour->id,
        'brand' => ['@type' => 'Organization', 'name' => \App\Models\Setting::get('site_name', 'Rung Adventure')],
        'offers' => [
            '@type' => 'Offer',
            'price' => $tour->price_reference ?: 0,
            'priceCurrency' => 'VND',
            'availability' => 'https://schema.org/InStock',
            'url' => route('tours.show', $tour->slug),
            'seller' => ['@type' => 'Organization', 'name' => \App\Models\Setting::get('site_name', 'Rung Adventure')],
        ],
    ];
    if ($tour->featured_image) {
        $productSchema['image'] = \Illuminate\Support\Facades\Storage::disk('public')->url($tour->featured_image);
    }
@endphp
<script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@endsection

@push('styles')
    @vite('resources/css/pages/tour-detail.css')
@endpush