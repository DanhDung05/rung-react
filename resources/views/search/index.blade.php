@extends('layouts.app')

@php
    $pageKey = 'search';
    $title = $keyword ? "Tìm kiếm: {$keyword}" : 'Tìm kiếm';
    $description = $keyword
        ? "Kết quả tìm kiếm cho từ khóa '{$keyword}'"
        : 'Tìm kiếm hành trình, điểm đến, bài viết trên Rung Adventure.';
@endphp

@section('content')

{{-- ============================================================
     HEADER
     ============================================================ --}}
<section class="py-10 lg:py-14 border-b border-border">
    <div class="container-base">
        <div class="max-w-3xl mx-auto text-center">

            {{-- Breadcrumb --}}
            <nav class="flex items-center justify-center gap-2 text-sm text-text-muted mb-5">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
                <span>/</span>
                <span class="text-text">Tìm kiếm</span>
            </nav>

            {{-- Title --}}
            <h1 class="font-heading text-2xl lg:text-3xl font-bold mb-3">
                @if ($keyword)
                    Kết quả tìm kiếm
                @else
                    Bạn muốn tìm gì?
                @endif
            </h1>

            <p class="text-text-muted mb-8">
                @if ($keyword)
                    Từ khóa: <span class="font-medium text-text">"{{ $keyword }}"</span>
                @else
                    Nhập từ khóa để tìm hành trình, điểm đến, bài viết và trang nội dung
                @endif
            </p>

            {{-- Search form --}}
            <form method="GET" action="{{ route('search') }}" class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text"
                           name="q"
                           value="{{ $keyword }}"
                           placeholder="Nhập từ khóa..."
                           autocomplete="off"
                           autofocus
                           class="w-full rounded-full border border-border bg-bg pl-5 pr-32 py-3.5 text-base text-text
                                  placeholder-text-subtle transition-colors
                                  focus:outline-none focus:border-primary">
                    <button type="submit"
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 px-5 py-2 rounded-full
                                   bg-primary text-white font-medium text-sm
                                   hover:bg-primary-hover transition-colors">
                        Tìm kiếm
                    </button>
                </div>
            </form>

        </div>
    </div>
</section>

{{-- ============================================================
     MAIN
     ============================================================ --}}
<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">

        {{-- =====================================================
             EMPTY
             ===================================================== --}}
        @if ($keyword === '')
            <div class="max-w-2xl mx-auto text-center">

                <p class="text-sm text-text-muted mb-5">Gợi ý tìm kiếm</p>

                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach (['Sapa', 'Fansipan', 'Trekking', 'Hà Giang', 'Cẩm nang', 'Cắm trại'] as $hint)
                        <a href="{{ route('search', ['q' => $hint]) }}"
                           class="inline-flex items-center px-4 py-2 rounded-full border border-border text-sm text-text
                                  hover:border-primary hover:text-primary transition-colors">
                            {{ $hint }}
                        </a>
                    @endforeach
                </div>

            </div>

        {{-- =====================================================
             NO RESULTS
             ===================================================== --}}
        @elseif ($total === 0)
            <div class="max-w-xl mx-auto text-center py-8">

                <h2 class="font-heading text-xl font-bold mb-3">
                    Không tìm thấy kết quả
                </h2>

                <p class="text-text-muted mb-8">
                    Không có kết quả cho từ khóa <span class="font-medium text-text">"{{ $keyword }}"</span>.
                </p>

                <p class="text-sm text-text-muted mb-4">Thử tìm với:</p>

                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach (['Sapa', 'Fansipan', 'Trekking', 'Hà Giang'] as $hint)
                        <a href="{{ route('search', ['q' => $hint]) }}"
                           class="inline-flex items-center px-4 py-2 rounded-full border border-border text-sm text-text
                                  hover:border-primary hover:text-primary transition-colors">
                            {{ $hint }}
                        </a>
                    @endforeach
                </div>

            </div>

        {{-- =====================================================
             RESULTS
             ===================================================== --}}
        @else

            {{-- Summary --}}
            <div class="max-w-3xl mx-auto mb-10 text-center">
                <p class="text-text-muted">
                    Tìm thấy <span class="font-semibold text-text">{{ $total }}</span> kết quả cho
                    <span class="font-medium text-text">"{{ $keyword }}"</span>
                </p>
            </div>

            {{-- Filter tabs --}}
            @php
                $types = [
                    'all'         => ['label' => 'Tất cả',      'count' => $total],
                    'tour'        => ['label' => 'Hành trình',  'count' => $results['tours']->count()],
                    'destination' => ['label' => 'Điểm đến',    'count' => $results['destinations']->count()],
                    'post'        => ['label' => 'Bài viết',    'count' => $results['posts']->count()],
                    'page'        => ['label' => 'Trang',       'count' => $results['pages']->count()],
                ];
            @endphp

            <div class="flex flex-wrap justify-center gap-2 mb-12">
                @foreach ($types as $key => $data)
                    @if ($key === 'all' || $data['count'] > 0)
                        <a href="{{ route('search', ['q' => $keyword, 'type' => $key]) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm transition-colors
                                  {{ $type === $key
                                      ? 'bg-text text-text-inverse'
                                      : 'border border-border text-text hover:border-text' }}">
                            {{ $data['label'] }}
                            <span class="text-xs opacity-60">{{ $data['count'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Content --}}
            <div class="space-y-20">

                {{-- TOURS --}}
                @if ($results['tours']->count() > 0)
                    <div>
                        <div class="flex items-baseline justify-between gap-4 mb-8 pb-4 border-b border-border">
                            <h2 class="font-heading text-2xl font-bold">
                                Hành trình
                                <span class="text-text-muted font-normal text-lg">/ {{ $results['tours']->count() }}</span>
                            </h2>

                            <a href="{{ route('tours.index', ['q' => $keyword]) }}"
                               class="hidden sm:inline-flex items-center text-sm text-text-muted hover:text-primary transition-colors">
                                Xem tất cả →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($results['tours'] as $tour)
                                <x-tour-card :tour="$tour" />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- DESTINATIONS --}}
                @if ($results['destinations']->count() > 0)
                    <div>
                        <div class="flex items-baseline justify-between gap-4 mb-8 pb-4 border-b border-border">
                            <h2 class="font-heading text-2xl font-bold">
                                Điểm đến
                                <span class="text-text-muted font-normal text-lg">/ {{ $results['destinations']->count() }}</span>
                            </h2>

                            <a href="{{ route('destinations.index', ['q' => $keyword]) }}"
                               class="hidden sm:inline-flex items-center text-sm text-text-muted hover:text-primary transition-colors">
                                Xem tất cả →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($results['destinations'] as $destination)
                                <x-destination-card :destination="$destination" variant="featured" />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- POSTS --}}
                @if ($results['posts']->count() > 0)
                    <div>
                        <div class="flex items-baseline justify-between gap-4 mb-8 pb-4 border-b border-border">
                            <h2 class="font-heading text-2xl font-bold">
                                Bài viết
                                <span class="text-text-muted font-normal text-lg">/ {{ $results['posts']->count() }}</span>
                            </h2>

                            <a href="{{ route('posts.index', ['q' => $keyword]) }}"
                               class="hidden sm:inline-flex items-center text-sm text-text-muted hover:text-primary transition-colors">
                                Xem tất cả →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($results['posts'] as $post)
                                <x-post-card :post="$post" />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- PAGES --}}
                @if ($results['pages']->count() > 0)
                    <div>
                        <div class="flex items-baseline justify-between gap-4 mb-8 pb-4 border-b border-border">
                            <h2 class="font-heading text-2xl font-bold">
                                Trang nội dung
                                <span class="text-text-muted font-normal text-lg">/ {{ $results['pages']->count() }}</span>
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($results['pages'] as $page)
                                <a href="{{ route('pages.show', $page->slug) }}"
                                   class="group block p-6 rounded-xl border border-border hover:border-primary transition-colors">
                                    <div class="font-heading text-lg font-semibold text-text group-hover:text-primary transition-colors mb-1">
                                        {{ $page->title }}
                                    </div>
                                    @if ($page->meta_description)
                                        <p class="text-sm text-text-muted line-clamp-2">
                                            {{ $page->meta_description }}
                                        </p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        @endif

    </div>
</section>

@endsection