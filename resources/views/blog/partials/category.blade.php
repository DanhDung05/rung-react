@extends('layouts.app')

@php
    $pageKey = 'blog-category';
    $title = $category->name;
    $description = $category->description ?? 'Các bài viết trong danh mục ' . $category->name;
@endphp

@section('content')

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<section class="bg-bg-subtle border-b border-border py-12 lg:py-16">
    <div class="container-base">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-text-muted mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-base">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('posts.index') }}" class="hover:text-primary transition-base">Cẩm nang</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-text">{{ $category->name }}</span>
        </nav>

        {{-- Category badge --}}
        <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-light text-primary text-xs font-semibold uppercase tracking-wider mb-4">
            Danh mục
        </div>

        <h1 class="font-heading text-3xl lg:text-4xl font-bold mb-3">
            {{ $category->name }}
        </h1>

        @if ($category->description)
            <p class="text-text-muted max-w-2xl mb-2">
                {{ $category->description }}
            </p>
        @endif

        <p class="text-text-muted text-sm">
            {{ $posts->total() }} bài viết
        </p>

    </div>
</section>

{{-- ============================================================
     MAIN CONTENT
     ============================================================ --}}
<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

            {{-- ============================================================
                 CỘT TRÁI: Danh sách bài viết
                 ============================================================ --}}
            <div class="lg:col-span-2">

                {{-- Category chips --}}
                @if ($categories->count() > 0)
                    <div class="mb-8 flex flex-wrap gap-2">
                        <a href="{{ route('posts.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-base
                                  bg-bg-subtle text-text hover:bg-primary-light hover:text-primary">
                            Tất cả
                        </a>

                        @foreach ($categories as $cat)
                            <a href="{{ route('posts.category', $cat->slug) }}"
                               class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-base
                                      {{ $cat->id === $category->id ? 'bg-primary text-text-inverse' : 'bg-bg-subtle text-text hover:bg-primary-light hover:text-primary' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Posts grid --}}
                @if ($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($posts as $post)
                            <x-post-card :post="$post" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        <x-pagination :paginator="$posts" />
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="w-20 h-20 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="font-heading text-xl font-bold mb-2">
                            Chưa có bài viết nào trong danh mục này
                        </h3>
                        <p class="text-text-muted mb-6">
                            Vui lòng quay lại sau.
                        </p>
                        <x-button href="{{ route('posts.index') }}" variant="primary">
                            Xem tất cả bài viết
                        </x-button>
                    </div>
                @endif

            </div>

            {{-- ============================================================
                 CỘT PHẢI: Sidebar
                 ============================================================ --}}
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