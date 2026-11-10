@extends('layouts.app')

@php
    $pageKey = 'destination-list';
    $title = 'Điểm đến';
    $description = 'Khám phá những điểm đến tuyệt đẹp trên khắp Việt Nam cùng Rung Adventure.';
@endphp

@section('content')

<x-hero-banner
    variant="compact"
    alignment="left"
    :image="$heroImage"
    title="Điểm đến"
    :breadcrumb="[['label' => 'Điểm đến']]"
>
    <x-slot:meta>
        <span>Khám phá {{ $destinations->total() }} điểm đến tuyệt đẹp trên khắp Việt Nam</span>
    </x-slot:meta>
</x-hero-banner>

<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <aside class="lg:col-span-1">
                @include('destination.partials.filter', ['regions' => $regions])
            </aside>
            <div class="lg:col-span-3">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-border">
                    <div class="text-sm text-text-muted">
                        Hiển thị <span class="font-semibold text-text">{{ $destinations->firstItem() ?? 0 }}</span> - <span class="font-semibold text-text">{{ $destinations->lastItem() ?? 0 }}</span> trong <span class="font-semibold text-text">{{ $destinations->total() }}</span> điểm đến
                    </div>
                    <form method="GET" action="{{ route('destinations.index') }}" class="flex items-center gap-2">
                        @foreach (request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="text-sm text-text-muted whitespace-nowrap">Sắp xếp:</label>
                        <select name="sort" onchange="this.form.submit()" class="rounded-md border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="default" @selected(request('sort') === 'default')>Mặc định</option>
                            <option value="featured" @selected(request('sort') === 'featured')>Nổi bật trước</option>
                            <option value="name_asc" @selected(request('sort') === 'name_asc')>Tên A → Z</option>
                            <option value="name_desc" @selected(request('sort') === 'name_desc')>Tên Z → A</option>
                        </select>
                    </form>
                </div>
                @if ($destinations->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($destinations as $destination)
                            <x-destination-card :destination="$destination" variant="featured" />
                        @endforeach
                    </div>
                    <div class="mt-10">
                        <x-pagination :paginator="$destinations" />
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="w-20 h-20 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <h3 class="font-heading text-xl font-bold mb-2">Không tìm thấy điểm đến nào</h3>
                        <p class="text-text-muted mb-6">Thử thay đổi bộ lọc hoặc tìm kiếm khác.</p>
                        <x-button href="{{ route('destinations.index') }}" variant="primary">Xem tất cả điểm đến</x-button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection