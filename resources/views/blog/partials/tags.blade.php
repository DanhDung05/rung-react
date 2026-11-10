{{-- ============================================================
     POST TAGS
     ============================================================ --}}

<div class="mt-10 pt-8 border-t border-border">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-sm font-semibold text-text">Tags:</span>
        <div class="flex flex-wrap gap-2">
            @foreach ($tags as $tag)
                <a href="{{ route('posts.tag', $tag->slug) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium transition-base
                          bg-bg-subtle text-text hover:bg-primary hover:text-text-inverse">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>