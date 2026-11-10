{{-- ============================================================
     SIDEBAR — Bài viết (bài mới, tags, categories)
     ============================================================ --}}

<div class="space-y-8 lg:sticky lg:top-24">

    {{-- Recent posts --}}
    @if (isset($recentPosts) && $recentPosts->count() > 0)
        <div class="bg-bg-subtle rounded-xl p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-5 bg-primary rounded-full"></div>
                <div class="font-heading text-base font-bold">
                    Bài viết mới
                </div>
            </div>
            <div class="space-y-1">
                @foreach ($recentPosts as $recentPost)
                    <x-post-card :post="$recentPost" variant="horizontal" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- Categories --}}
    @if (isset($categories) && $categories->count() > 0)
        <div class="bg-bg-subtle rounded-xl p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-5 bg-primary rounded-full"></div>
                <div class="font-heading text-base font-bold">
                    Danh mục
                </div>
            </div>
            <ul class="space-y-1">
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('posts.category', $category->slug) }}"
                           class="flex items-center justify-between py-2 px-3 -mx-3 rounded-md text-sm text-text hover:bg-bg hover:text-primary transition-base">
                            <span>{{ $category->name }}</span>
                            <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Popular tags --}}
    @if (isset($popularTags) && $popularTags->count() > 0)
        <div class="bg-bg-subtle rounded-xl p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-1 h-5 bg-primary rounded-full"></div>
                <div class="font-heading text-base font-bold">
                    Tags phổ biến
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($popularTags as $tag)
                    <a href="{{ route('posts.tag', $tag->slug) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium transition-base
                              bg-bg text-text hover:bg-primary hover:text-text-inverse">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>