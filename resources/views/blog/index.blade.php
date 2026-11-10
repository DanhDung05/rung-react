@extends('layouts.app')

@php
    $pageKey = 'blog-list';
    $title = 'Cẩm nang du lịch';
    $description = 'Blog, cẩm nang, kinh nghiệm và tin tức du lịch từ Rung Adventure.';
@endphp

@section('content')

<x-hero-banner
    variant="compact"
    alignment="left"
    :image="$heroImage"
    title="Cẩm nang du lịch"
    :breadcrumb="[['label' => 'Cẩm nang du lịch']]"
>
    <x-slot:meta>
        <span>{{ $posts->total() }} bài viết về kinh nghiệm, cẩm nang và tin tức du lịch</span>
    </x-slot:meta>
</x-hero-banner>

<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            <div class="lg:col-span-2">
                @include('blog.partials.filter', ['categories' => $categories])
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6 pb-4 border-b border-border">
                    <div class="text-sm text-text-muted">
                        Hiển thị <span class="font-semibold text-text">{{ $posts->firstItem() ?? 0 }}</span> - <span class="font-semibold text-text">{{ $posts->lastItem() ?? 0 }}</span> trong <span class="font-semibold text-text">{{ $posts->total() }}</span> bài viết
                    </div>
                    <form method="GET" action="{{ route('posts.index') }}" class="flex items-center gap-2">
                        @foreach (request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="text-sm text-text-muted whitespace-nowrap">Sắp xếp:</label>
                        <select name="sort" onchange="this.form.submit()" class="rounded-md border border-border bg-bg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="newest" @selected(request('sort') === 'newest')>Mới nhất</option>
                            <option value="oldest" @selected(request('sort') === 'oldest')>Cũ nhất</option>
                            <option value="popular" @selected(request('sort') === 'popular')>Phổ biến</option>
                        </select>
                    </form>
                </div>
                @if ($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($posts as $post)
                            <x-post-card :post="$post" />
                        @endforeach
                    </div>
                    <div class="mt-10">
                        <x-pagination :paginator="$posts" />
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="w-20 h-20 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <h3 class="font-heading text-xl font-bold mb-2">Không tìm thấy bài viết nào</h3>
                        <p class="text-text-muted mb-6">Thử thay đổi bộ lọc hoặc tìm kiếm khác.</p>
                        <x-button href="{{ route('posts.index') }}" variant="primary">Xem tất cả bài viết</x-button>
                    </div>
                @endif
            </div>
            <aside class="lg:col-span-1">
                @include('blog.partials.sidebar', [
                    'categories' => $categories,
                    'recentPosts' => $recentPosts ?? collect(),
                    'popularTags' => $popularTags ?? collect(),
                ])
            </aside>
        </div>
    </div>
</section>

@endsection