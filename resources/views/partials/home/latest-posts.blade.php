{{-- ============================================================
     LATEST POSTS
     ============================================================ --}}

@if ($posts->count() > 0)
    <section class="py-16 lg:py-24 bg-bg-subtle">
        <div class="container-base">

            {{-- Header --}}
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div data-aos="fade-up">
                    <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">
                        Blog
                    </p>
                    <h2 class="font-heading text-3xl lg:text-4xl font-bold">
                        Cẩm nang du lịch
                    </h2>
                </div>

                <x-button href="{{ route('posts.index') }}" variant="outline" data-aos="fade-up">
                    Xem tất cả
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </x-button>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <article class="post-card group bg-bg rounded-xl overflow-hidden shadow-sm border border-border hover:shadow-lg transition-all duration-300 h-full flex flex-col"
                             data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                        {{-- Thumbnail --}}
                        <a href="{{ route('posts.show', $post->slug) }}" class="block relative aspect-[16/10] overflow-hidden">
                            @if ($post->featured_image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=600&h=400&fit=crop"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @endif

                            @if ($post->category)
                                <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full bg-primary text-white text-xs font-semibold">
                                    {{ $post->category->name }}
                                </span>
                            @endif
                        </a>

                        {{-- Content --}}
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="font-heading text-lg font-bold mb-2 line-clamp-2">
                                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-primary transition-base">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            @if ($post->excerpt)
                                <p class="text-sm text-text-muted line-clamp-2 mb-4 flex-1">
                                    {{ $post->excerpt }}
                                </p>
                            @endif

                            {{-- Meta --}}
                            <div class="flex items-center gap-4 text-xs text-text-muted pt-4 border-t border-border mt-auto">
                                @if ($post->author)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $post->author->name }}
                                    </span>
                                @endif
                                @if ($post->published_at)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $post->published_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

        </div>
    </section>
@endif