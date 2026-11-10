{{-- ============================================================
     FILTER — Category chips
     ============================================================ --}}

@if ($categories->count() > 0)
    <div class="mb-6 flex flex-wrap gap-2">
        {{-- All --}}
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-base
                  {{ !request('category') ? 'bg-primary text-text-inverse' : 'bg-bg-subtle text-text hover:bg-primary-light hover:text-primary' }}">
            Tất cả
        </a>

        {{-- Category chips --}}
        @foreach ($categories as $category)
            <a href="{{ route('posts.index', ['category' => $category->slug]) }}"
               class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-base
                      {{ request('category') === $category->slug ? 'bg-primary text-text-inverse' : 'bg-bg-subtle text-text hover:bg-primary-light hover:text-primary' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
@endif