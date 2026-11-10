@extends('layouts.app')

@php
    $pageKey = 'page-' . $page->slug;
    $title = $page->meta_title ?? $page->title;
    $description = $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->content), 160);
    $image = $page->featured_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($page->featured_image) : null;
@endphp

@section('content')

{{-- ============================================================
     HEADER — đơn giản hơn
     ============================================================ --}}
<section class="bg-bg-subtle border-b border-border py-12 lg:py-16">
    <div class="container-base max-w-4xl">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-text-muted mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-base">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-text">{{ $page->title }}</span>
        </nav>

        <h1 class="font-heading text-3xl lg:text-4xl font-bold mb-4">
            {{ $page->title }}
        </h1>

    </div>
</section>

{{-- ============================================================
     FEATURED IMAGE (nếu có)
     ============================================================ --}}
@if ($page->featured_image)
    <div class="container-base max-w-4xl py-8">
        <div class="aspect-[16/9] rounded-xl overflow-hidden shadow-lg">
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->featured_image) }}"
                 alt="{{ $page->title }}"
                 class="w-full h-full object-cover">
        </div>
    </div>
@endif

{{-- ============================================================
     CONTENT
     ============================================================ --}}
<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base max-w-4xl">

        <div class="prose prose-lg max-w-none post-content text-text">
            {!! $page->content !!}
        </div>

    </div>
</section>


@endsection