{{-- ============================================================
     FILTER SIDEBAR — Điểm đến
     ============================================================ --}}

<div class="lg:sticky lg:top-24 space-y-6">

    {{-- Mobile toggle --}}
    <button type="button"
            id="dest-filter-toggle"
            class="lg:hidden w-full inline-flex items-center justify-between px-4 py-3 bg-bg-subtle border border-border rounded-lg">
        <span class="font-medium text-text flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Bộ lọc
        </span>
        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Filter form --}}
    <form method="GET"
          action="{{ route('destinations.index') }}"
          id="dest-filter-form"
          class="hidden lg:block bg-bg rounded-xl border border-border p-5 space-y-6">

        {{-- Search --}}
        <div>
            <label class="block text-sm font-semibold text-text mb-2">
                Tìm kiếm
            </label>
            <div class="relative">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Tên điểm đến..."
                       class="w-full rounded-md border border-border bg-bg pl-10 pr-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                <svg class="w-4 h-4 text-text-subtle absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
            </div>
        </div>

        {{-- Region --}}
        @if ($regions->count() > 0)
            <div>
                <label class="block text-sm font-semibold text-text mb-2">
                    Vùng miền
                </label>
                <div class="space-y-1.5">
                    <label class="flex items-center gap-2 cursor-pointer py-1 px-2 rounded hover:bg-bg-subtle">
                        <input type="radio"
                               name="region"
                               value=""
                               @checked(!request('region'))
                               class="text-primary focus:ring-primary">
                        <span class="text-sm">Tất cả</span>
                    </label>
                    @foreach ($regions as $region)
                        <label class="flex items-center gap-2 cursor-pointer py-1 px-2 rounded hover:bg-bg-subtle">
                            <input type="radio"
                                   name="region"
                                   value="{{ $region }}"
                                   @checked(request('region') === $region)
                                   class="text-primary focus:ring-primary">
                            <span class="text-sm">{{ $region }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Featured --}}
        <div>
            <label class="flex items-center gap-2 cursor-pointer py-1 px-2 rounded hover:bg-bg-subtle">
                <input type="checkbox"
                       name="featured"
                       value="1"
                       @checked(request('featured') === '1')
                       class="rounded border-border text-primary focus:ring-primary">
                <span class="text-sm">Chỉ điểm đến nổi bật</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="pt-4 border-t border-border space-y-2">
            <x-button type="submit" variant="primary" block>
                Áp dụng bộ lọc
            </x-button>

            @if (request()->hasAny(['q', 'region', 'featured']))
                <x-button href="{{ route('destinations.index') }}" variant="outline" block>
                    Xóa tất cả bộ lọc
                </x-button>
            @endif
        </div>

    </form>

</div>

{{-- Mobile toggle script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('dest-filter-toggle');
    const form = document.getElementById('dest-filter-form');

    if (toggle && form) {
        toggle.addEventListener('click', () => {
            form.classList.toggle('hidden');
        });
    }
});
</script>