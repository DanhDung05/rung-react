{{-- ============================================================
     SHARE BUTTONS
     ============================================================ --}}

@php
    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($post->title);
@endphp

<div class="mt-8 pt-6 border-t border-border">
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-sm font-semibold text-text">Chia sẻ:</span>

        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
           target="_blank" rel="noopener noreferrer"
           class="w-9 h-9 inline-flex items-center justify-center rounded-full bg-[#1877F2] text-white hover:opacity-90 transition-base"
           aria-label="Chia sẻ Facebook">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </a>

        {{-- Twitter / X --}}
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
           target="_blank" rel="noopener noreferrer"
           class="w-9 h-9 inline-flex items-center justify-center rounded-full bg-text text-text-inverse hover:opacity-90 transition-base"
           aria-label="Chia sẻ Twitter">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
        </a>

        {{-- Copy link --}}
        <button type="button"
                onclick="navigator.clipboard.writeText('{{ url()->current() }}'); this.textContent = 'Đã sao chép!';"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-bg-subtle text-text text-sm font-medium hover:bg-primary hover:text-text-inverse transition-base">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <span>Sao chép link</span>
        </button>
    </div>
</div>