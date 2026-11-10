@extends('layouts.admin')

@section('title', 'Quản lý Media')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold text-text">Quản lý Media</h1>
            <p class="text-sm text-text-muted mt-1">
                Tất cả ảnh đã upload (chưa xóa). Upload ảnh mới tại trang sửa Tour/Post.
            </p>
        </div>

        <a href="{{ route('admin.media.trash') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium
                  text-danger bg-danger/10 hover:bg-danger hover:text-white transition-base">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Thùng rác
            @if ($trashedCount > 0)
                <span class="ml-1 px-2 py-0.5 rounded-full bg-white/20 text-xs">{{ $trashedCount }}</span>
            @endif
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-bg rounded-lg shadow-sm border border-border p-4">
        <form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-wrap items-end gap-3">
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

            <div class="min-w-[200px]">
                <label class="block text-xs font-medium text-text-muted mb-1">Thuộc về</label>
                <select name="mediable_type"
                        class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                               focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="">-- Tất cả --</option>
                    @foreach ($mediableTypes as $type)
                        <option value="{{ $type }}" @selected(request('mediable_type') === $type)>
                            {{ class_basename($type) }}
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
                <a href="{{ route('admin.media.index') }}"
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
                <div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden group">
                    <div class="relative aspect-square">
                        <img src="{{ $item->url }}"
                             alt="{{ $item->alt_text ?? $item->file_name }}"
                             class="w-full h-full object-cover"
                             loading="lazy">

                        <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-xs font-medium
                                     bg-black/60 text-white">
                            {{ $item->collection }}
                        </span>

                        <button type="button"
                                class="btn-delete-media absolute top-2 right-2 w-7 h-7 rounded-full
                                       bg-danger text-white flex items-center justify-center
                                       opacity-0 group-hover:opacity-100 transition"
                                data-media-id="{{ $item->id }}"
                                data-media-name="{{ $item->file_name }}"
                                title="Xóa">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-2 space-y-1">
                        <p class="text-xs font-medium text-text truncate" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </p>
                        <p class="text-xs text-text-muted">
                            @if ($item->mediable)
                                {{ class_basename($item->mediable_type) }} #{{ $item->mediable_id }}
                            @else
                                Không gắn
                            @endif
                        </p>
                        <p class="text-xs text-text-muted">
                            {{ $item->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $media->links() }}</div>
    @else
        <div class="bg-bg rounded-lg shadow-sm border border-border p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-muted/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-text-muted">Chưa có ảnh nào.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-media').forEach(btn => {
        btn.addEventListener('click', function () {
            const mediaId = this.dataset.mediaId;
            const mediaName = this.dataset.mediaName;

            if (!confirm(`Xóa ảnh "${mediaName}"? Ảnh sẽ vào thùng rác.`)) return;

            fetch(`{{ url('/admin/media') }}/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.closest('.group').remove();
                }
            })
            .catch(err => {
                console.error('Lỗi xóa ảnh:', err);
                alert('Không xóa được ảnh. Vui lòng thử lại.');
            });
        });
    });
});
</script>
@endpush
@endsection