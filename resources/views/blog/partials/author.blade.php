{{-- ============================================================
     AUTHOR BOX — Magazine style
     ============================================================ --}}

<div class="mt-12 p-8 bg-gradient-to-br from-bg-subtle to-bg-muted rounded-2xl border border-border">
    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

        {{-- Avatar --}}
        @if ($author->avatar)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($author->avatar) }}"
                 alt="{{ $author->name }}"
                 class="w-24 h-24 rounded-full object-cover ring-4 ring-primary/20 flex-shrink-0">
        @else
            <div class="w-24 h-24 rounded-full bg-primary text-text-inverse flex items-center justify-center font-heading text-3xl font-bold ring-4 ring-primary/20 flex-shrink-0">
                {{ strtoupper(substr($author->name, 0, 1)) }}
            </div>
        @endif

        {{-- Info --}}
        <div class="text-center sm:text-left flex-1">
            <div class="text-xs text-primary font-bold uppercase tracking-widest mb-2">
                Về tác giả
            </div>
            <div class="font-heading text-2xl font-bold text-text mb-2">
                {{ $author->name }}
            </div>

            @if ($author->bio ?? false)
                <p class="text-sm text-text-muted leading-relaxed">
                    {{ $author->bio }}
                </p>
            @else
                <p class="text-sm text-text-muted leading-relaxed">
                    Tác giả tại {{ \App\Models\Setting::get('site_name', 'Rung Adventure') }} — chia sẻ những hành trình và trải nghiệm du lịch.
                </p>
            @endif
        </div>

    </div>
</div>