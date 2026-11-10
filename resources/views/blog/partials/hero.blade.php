{{-- ============================================================
     HERO — Magazine style
     Ảnh nền lớn + overlay tối + tiêu đề đè lên ảnh
     ============================================================ --}}

<section class="relative min-h-[500px] lg:min-h-[650px] overflow-hidden">

    {{-- Background image --}}
    @if ($post->featured_image)
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
             alt="{{ $post->title }}"
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-hover"></div>
    @endif

    {{-- Overlay gradient — đậm dần về phía dưới --}}
    <div class="absolute inset-0 bg-gradient-to-t from-text via-text/60 to-text/20"></div>

    {{-- Content --}}
    <div class="relative h-full min-h-[500px] lg:min-h-[650px] container-base flex flex-col justify-end pb-12 lg:pb-16">

               {{-- Breadcrumb (dùng variant="light" cho nền tối) --}}
        <x-breadcrumb variant="light" :items="[
            ['label' => 'Cẩm nang', 'url' => route('posts.index')],
            ...($post->category ? [['label' => $post->category->name, 'url' => route('posts.category', $post->category->slug)]] : []),
            ['label' => $post->title],
        ]" class="mb-4" />

        {{-- Category badge --}}
        @if ($post->category)
            <div class="mb-5">
                <a href="{{ route('posts.category', $post->category->slug) }}"
                   class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary text-text-inverse text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-base">
                    {{ $post->category->name }}
                </a>
            </div>
        @endif

        {{-- Title --}}
        <h1 class="font-heading text-3xl lg:text-5xl xl:text-6xl font-bold text-text-inverse mb-6 leading-[1.1] max-w-5xl">
            {{ $post->title }}
        </h1>

        {{-- Meta line --}}
        <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-text-inverse/90">

            {{-- Author --}}
            @if ($post->author)
                <div class="flex items-center gap-3">
                    @if ($post->author->avatar)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->author->avatar) }}"
                             alt="{{ $post->author->name }}"
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-text-inverse/30">
                    @else
                        <div class="w-10 h-10 rounded-full bg-text-inverse/20 backdrop-blur-sm text-text-inverse flex items-center justify-center font-semibold ring-2 ring-text-inverse/30">
                            {{ strtoupper(substr($post->author->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="text-xs text-text-inverse/60 uppercase tracking-wide">Tác giả</div>
                        <div class="font-medium text-text-inverse">{{ $post->author->name }}</div>
                    </div>
                </div>
            @endif

            {{-- Divider --}}
            <div class="hidden sm:block w-px h-10 bg-text-inverse/20"></div>

            {{-- Date --}}
            <div>
                <div class="text-xs text-text-inverse/60 uppercase tracking-wide">Ngày đăng</div>
                <div class="font-medium text-text-inverse">{{ $post->published_at?->format('d/m/Y') }}</div>
            </div>

            {{-- Divider --}}
            <div class="hidden sm:block w-px h-10 bg-text-inverse/20"></div>

            {{-- Views --}}
            <div>
                <div class="text-xs text-text-inverse/60 uppercase tracking-wide">Lượt xem</div>
                <div class="font-medium text-text-inverse flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ number_format($post->view_count) }}
                </div>
            </div>

        </div>

    </div>
</section>