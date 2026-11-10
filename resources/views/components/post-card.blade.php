@props([
    'post',
    'variant' => 'default',
])

@if ($variant === 'horizontal')
    {{-- ============================================================
         HORIZONTAL — Sidebar bài mới
         ============================================================ --}}
    <a href="{{ route('posts.show', $post->slug) }}"
       {{ $attributes->merge(['class' => 'group flex gap-3 p-3 rounded-lg hover:bg-bg-subtle transition-base']) }}>

        @if ($post->featured_image)
            <div class="relative w-20 h-20 flex-shrink-0 rounded-md overflow-hidden">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                     alt="{{ $post->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            </div>
        @else
            <div class="w-20 h-20 flex-shrink-0 rounded-md bg-bg-muted flex items-center justify-center text-text-subtle">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <div class="font-heading text-sm font-semibold text-text line-clamp-2 group-hover:text-primary transition-base mb-1">
                {{ $post->title }}
            </div>
            <div class="text-xs text-text-muted">
                {{ $post->published_at?->format('d/m/Y') }}
            </div>
        </div>
    </a>

@elseif ($variant === 'compact')
    {{-- ============================================================
         COMPACT — Related posts, grid nhỏ
         ============================================================ --}}
    <article {{ $attributes->merge(['class' => 'group bg-bg rounded-lg overflow-hidden shadow-sm border border-border hover:shadow-md transition-all duration-300']) }}>

        <a href="{{ route('posts.show', $post->slug) }}"
           class="block relative aspect-[16/10] overflow-hidden">

            @if ($post->featured_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                     alt="{{ $post->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-bg-muted flex items-center justify-center">
                    <svg class="w-10 h-10 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            @endif
        </a>

        <div class="p-4">
            @if ($post->category)
                <a href="{{ route('posts.category', $post->category->slug) }}"
                   class="text-xs text-primary font-medium uppercase tracking-wide hover:underline">
                    {{ $post->category->name }}
                </a>
            @endif

            <h3 class="font-heading text-sm font-bold mt-1.5 mb-2 line-clamp-2">
                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-primary transition-base">
                    {{ $post->title }}
                </a>
            </h3>

            <div class="flex items-center justify-between text-xs text-text-muted pt-2 border-t border-border">
                <span>{{ $post->published_at?->format('d/m/Y') }}</span>
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ $post->view_count }}
                </span>
            </div>
        </div>
    </article>

@else
    {{-- ============================================================
         DEFAULT — Card tiêu chuẩn
         ============================================================ --}}
    <article {{ $attributes->merge(['class' => 'post-card group bg-bg rounded-xl overflow-hidden shadow-sm border border-border hover:shadow-lg transition-all duration-300']) }}>

        <a href="{{ route('posts.show', $post->slug) }}"
           class="block relative aspect-[16/10] overflow-hidden">

            @if ($post->featured_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                     alt="{{ $post->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-bg-muted flex items-center justify-center">
                    <svg class="w-16 h-16 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            @endif

            @if ($post->is_featured)
                <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-secondary text-text-inverse text-xs font-semibold">
                    Nổi bật
                </span>
            @endif
        </a>

        <div class="p-5">
            @if ($post->category)
                <a href="{{ route('posts.category', $post->category->slug) }}"
                   class="text-xs text-primary font-medium uppercase tracking-wide hover:underline">
                    {{ $post->category->name }}
                </a>
            @endif

            <h3 class="font-heading text-lg font-bold mt-2 mb-3 line-clamp-2">
                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-primary transition-base">
                    {{ $post->title }}
                </a>
            </h3>

            @if ($post->excerpt)
                <p class="text-sm text-text-muted line-clamp-3 mb-4">
                    {{ $post->excerpt }}
                </p>
            @endif

            <div class="flex items-center justify-between text-xs text-text-muted pt-4 border-t border-border">
                <div class="flex items-center gap-2">
                    @if ($post->author)
                        <div class="w-6 h-6 rounded-full bg-primary text-text-inverse flex items-center justify-center text-xs font-semibold">
                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                        </div>
                        <span>{{ $post->author->name }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <span>{{ $post->published_at?->format('d/m/Y') }}</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ $post->view_count }}
                    </span>
                </div>
            </div>
        </div>
    </article>
@endif