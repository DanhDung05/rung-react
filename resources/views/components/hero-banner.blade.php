<section class="relative {{ $height }} overflow-hidden">
    {{-- Background --}}
    @if ($image)
        <img src="{{ $image }}"
             alt="{{ $title }}"
             class="absolute inset-0 w-full h-full object-cover">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-hover"></div>
    @endif

    {{-- Overlay giống trang chủ: bg-black/30 --}}
    <div class="absolute inset-0 {{ $overlay }}"></div>

    {{-- Content --}}
    <div class="relative h-full container-base flex flex-col justify-center py-8 lg:py-12">

        {{-- Breadcrumb --}}
        @if ($showBreadcrumb && !empty($breadcrumb))
            <div class="mb-4">
                <x-breadcrumb variant="light" :items="$breadcrumb" />
            </div>
        @endif

        {{-- Nội dung chính --}}
        <div class="{{ $alignment === 'center' ? 'text-center mx-auto max-w-3xl' : 'max-w-4xl' }}">

            {{-- Badges (slot) --}}
            @if (isset($badges))
                <div class="flex flex-wrap items-center gap-2 mb-4 {{ $alignment === 'center' ? 'justify-center' : '' }}">
                    {{ $badges }}
                </div>
            @endif

            {{-- Title (Màu trắng + text-shadow giống trang chủ) --}}
            <h1 class="font-heading text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight"
                style="text-shadow: 0 4px 12px rgba(0,0,0,0.9), 0 2px 4px rgba(0,0,0,0.9);">
                {{ $title }}
            </h1>

            {{-- Meta (slot) --}}
            @if (isset($meta))
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-white {{ $alignment === 'center' ? 'justify-center' : '' }}"
                     style="text-shadow: 0 2px 6px rgba(0,0,0,0.8);">
                    {{ $meta }}
                </div>
            @endif

        </div>

    </div>
</section>