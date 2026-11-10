@props(['paginator'])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-text-muted">
                    Hiển thị
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    đến
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    trong
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    kết quả
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-border bg-bg-muted text-text-subtle cursor-not-allowed">
                            <span class="sr-only">Trước</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-border bg-bg text-text-muted hover:bg-bg-subtle">
                            <span class="sr-only">Trước</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-primary bg-primary text-text-inverse font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 border border-border bg-bg text-text-muted hover:bg-bg-subtle">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-border bg-bg text-text-muted hover:bg-bg-subtle">
                            <span class="sr-only">Sau</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-border bg-bg-muted text-text-subtle cursor-not-allowed">
                            <span class="sr-only">Sau</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif