@extends('layouts.app')

@php
    $pageKey = 'blog-detail';
    $title = $post->title;
    $description = $post->meta_description ?? $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160);
    $image = $post->featured_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) : null;
    $type = 'article';
@endphp

@section('content')

<article>

    {{-- ============================================================
         HERO — Full-width với ảnh nền lớn
         ============================================================ --}}
    @include('blog.partials.hero', ['post' => $post])

    {{-- ============================================================
         MAIN CONTENT
         ============================================================ --}}
    <section class="py-12 lg:py-16 bg-bg">
        <div class="container-base">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                {{-- ============================================================
                     CỘT TRÁI: Content (chiếm 8/12 cột)
                     ============================================================ --}}
                <div class="lg:col-span-8">

                    {{-- Excerpt nổi bật --}}
                    @if ($post->excerpt)
                        <div class="mb-10 pl-5 border-l-4 border-primary">
                            <p class="text-lg lg:text-xl text-text-muted leading-relaxed italic">
                                {{ $post->excerpt }}
                            </p>
                        </div>
                    @endif

                    {{-- Content --}}
                    @include('blog.partials.content', ['post' => $post])

                    {{-- Tags --}}
                    @if ($post->tags->count() > 0)
                        @include('blog.partials.tags', ['tags' => $post->tags])
                    @endif

                    {{-- Share --}}
                    @include('blog.partials.share', ['post' => $post])

                    {{-- Author box --}}
                    @if ($post->author)
                        @include('blog.partials.author', ['author' => $post->author])
                    @endif

                </div>

                {{-- ============================================================
                     CỘT PHẢI: Sidebar (chiếm 4/12 cột)
                     ============================================================ --}}
                <aside class="lg:col-span-4">
                    @include('blog.partials.sidebar', [
                        'recentPosts' => $recentPosts ?? collect(),
                        'popularTags' => $popularTags ?? collect(),
                        'categories' => $categories ?? collect(),
                    ])
                </aside>

            </div>
        </div>
    </section>

    {{-- ============================================================
         RELATED POSTS
         ============================================================ --}}
    @if ($relatedPosts->count() > 0)
        @include('blog.partials.related', ['posts' => $relatedPosts])
    @endif

</article>

{{-- ============================================================
     STRUCTURED DATA — Article + Breadcrumb
     ============================================================ --}}

{{-- Breadcrumb Schema --}}
@php
    $breadcrumbItems = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Cẩm nang', 'url' => route('posts.index')],
    ];

    if ($post->category) {
        $breadcrumbItems[] = [
            'name' => $post->category->name,
            'url'  => route('posts.category', $post->category->slug),
        ];
    }

    $breadcrumbItems[] = [
        'name' => $post->title,
        'url'  => route('posts.show', $post->slug),
    ];

    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbItems)->map(function ($item, $index) {
            return [
                '@type'    => 'ListItem',
                'position' => $index + 1,
                'name'     => $item['name'],
                'item'     => $item['url'],
            ];
        })->values()->toArray(),
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- Article Schema --}}
@php
    $articleSchema = [
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'headline'         => $post->title,
        'description'      => $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160),
        'datePublished'    => $post->published_at?->toAtomString(),
        'dateModified'     => $post->updated_at->toAtomString(),
        'author'           => $post->author ? [
            '@type' => 'Person',
            'name'  => $post->author->name,
        ] : null,
        'publisher'        => [
            '@type' => 'Organization',
            'name'  => \App\Models\Setting::get('site_name', 'Rung Adventure'),
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => route('posts.show', $post->slug),
        ],
    ];

    if ($post->featured_image) {
        $articleSchema['image'] = \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image);
    }

    // Loại bỏ key null
    $articleSchema = array_filter($articleSchema, fn($v) => $v !== null);
@endphp

<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@push('styles')
    @vite('resources/css/pages/blog.css')
@endpush