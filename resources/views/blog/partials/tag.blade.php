@extends('layouts.app')

@php
    $pageKey = 'blog-tag';
    $title = '#' . $tag->name;
    $description = 'Các bài viết với tag #' . $tag->name;
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
            <span class="text-text">Tag</span>
        </nav>

        {{-- Tag badge --}}
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-light text-primary text-xs font-semibold uppercase tracking-wider mb-4">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Tag
        </div>

        <h1 class="font-heading text-3xl lg:text-4xl font-bold mb-3">
            #{{ $tag->name }}
        </h1>

        @if ($tag->description)
            <p class="text-text-muted max-w-2xl mb-2">
                {{ $tag->description }}
            </p>
        @endif

        <p class="text-text-muted text-sm">
            {{ $posts->total() }} bài viết với tag này
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

                {{-- Popular tags chips --}}
                @if ($popularTags->count() > 0)
                    <div class="mb-8">
                        <div class="text-sm font-semibold text-text mb-3">
                            Tags phổ biến:
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($popularTags as $t)
                                <a href="{{ route('posts.tag', $t->slug) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium transition-base
                                          {{ $t->id === $tag->id ? 'bg-primary text-text-inverse' : 'bg-bg-subtle text-text hover:bg-primary hover:text-text-inverse' }}">
                                    #{{ $t->name }}
                                    <span class="opacity-70">({{ $t->posts_count }})</span>
                                </a>
                            @endforeach
                        </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3 class="font-heading text-xl font-bold mb-2">
                            Chưa có bài viết nào với tag này
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