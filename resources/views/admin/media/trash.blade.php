@extends('layouts.admin')

@section('title', 'Thùng rác Media')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold text-text">Thùng rác Media</h1>
            <p class="text-sm text-text-muted mt-1">Ảnh đã xóa mềm — có thể khôi phục hoặc xóa vĩnh viễn</p>
        </div>

        <a href="{{ route('admin.media.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium
                  text-text border border-border hover:bg-bg-subtle transition-base">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-bg rounded-lg shadow-sm border border-border p-4">
        <form method="GET" action="{{ route('admin.media.trash') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-text-muted mb-1">Tìm theo tên file</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                       placeholder="Nhập tên file..."
                       class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                              focus:outline-none focus:ring-1 focus:ring-primary">
            </div>

            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-text-muted mb-1">Collection</label>
                <select name="collection"
                        class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                               focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="">-- Tất cả --</option>
                    @foreach ($collections as $collection)
                        <option value="{{ $collection }}" @selected(request('collection') === $collection)>
                            {{ $collection }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="px-4 py-2 rounded-md text-sm font-medium text-text-inverse bg-primary
                               hover:bg-primary-hover transition-base">
                    Lọc
                </button>
                <a href="{{ route('admin.media.trash') }}"
                   class="px-4 py-2 rounded-md text-sm font-medium text-text-muted border border-border
                          hover:bg-bg-subtle transition-base">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Grid --}}
    @if ($media->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($media as $item)
                <div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
                    <div class="relative aspect-square">
                        <img src="{{ $item->url }}"
                             alt="{{ $item->alt_text ?? $item->file_name }}"
                             class="w-full h-full object-cover opacity-60"
                             loading="lazy">

                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-xs font-medium
                                     bg-danger text-white">
                            Đã xóa
                        </span>
                    </div>

                    <div class="p-2 space-y-1">
                        <p class="text-xs font-medium text-text truncate" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </p>
                        <p class="text-xs text-text-muted">
                            {{ $item->deleted_at->format('d/m/Y H:i') }}
                        </p>

                        <div class="flex items-center gap-1 pt-1">
                            <form action="{{ route('admin.media.restore', $item->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full px-2 py-1 rounded text-xs font-medium
                                               text-primary bg-primary-light hover:bg-primary hover:text-white transition-base">
                                    Khôi phục
                                </button>
                            </form>

                            <form action="{{ route('admin.media.force-destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Xóa vĩnh viễn ảnh này? Không thể khôi phục!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-2 py-1 rounded text-xs font-medium
                                               text-danger bg-danger/10 hover:bg-danger hover:text-white transition-base">
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $media->links() }}</div>
    @else
        <div class="bg-bg rounded-lg shadow-sm border border-border p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-muted/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <p class="text-text-muted">Thùng rác trống.</p>
        </div>
    @endif
</div>
@endsection