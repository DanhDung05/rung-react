@extends('layouts.app')

@php
    $pageKey = 'tour-list';
    $title = 'Hành trình';
    $description = 'Khám phá các hành trình trekking, cắm trại và khám phá tuyệt vời của Rung Adventure.';
@endphp

@section('content')

<x-hero-banner
    variant="compact"
    alignment="left"
    :image="$heroImage"
    title="Hành trình"
    :breadcrumb="[['label' => 'Hành trình']]"
>
    <x-slot:meta>
        <span>Khám phá {{ $tours->total() }} hành trình tuyệt vời đang chờ bạn</span>
    </x-slot:meta>
</x-hero-banner>

<section class="py-16 lg:py-24 bg-bg">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <aside class="lg:col-span-1">
                @include('tour.partials.filter', ['categories' => $categories, 'difficulties' => $difficulties])
            </aside>
            <div class="lg:col-span-3">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-border">
                    <div class="text-sm text-text-muted">
                        Hiển thị <span class="font-semibold text-text">{{ $tours->firstItem() ?? 0 }}</span> - <span class="font-semibold text-text">{{ $tours->lastItem() ?? 0 }}</span> trong <span class="font-semibold text-text">{{ $tours->total() }}</span> hành trình
                    </div>
                    <form method="GET" action="{{ route('tours.index') }}" class="flex items-center gap-2">
                        @foreach (request()->except('sort', 'page') as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label class="text-sm text-text-muted whitespace-nowrap">Sắp xếp:</label>
                        <select name="sort" onchange="this.form.submit()" class="rounded-md border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="newest" @selected(request('sort') === 'newest')>Mới nhất</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Giá thấp → cao</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá cao → thấp</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Phổ biến</option>
                        </select>
                    </form>
                </div>
                @if ($tours->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($tours as $tour)
                            <x-tour-card :tour="$tour" />
                        @endforeach
                    </div>
                    <div class="mt-10">
                        <x-pagination :paginator="$tours" />
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="w-20 h-20 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <h3 class="font-heading text-xl font-bold mb-2">Không tìm thấy hành trình nào</h3>
                        <p class="text-text-muted mb-6">Thử thay đổi bộ lọc hoặc tìm kiếm khác.</p>
                        <x-button href="{{ route('tours.index') }}" variant="primary">Xem tất cả hành trình</x-button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
    @vite('resources/css/pages/tour-list.css')
@endpush