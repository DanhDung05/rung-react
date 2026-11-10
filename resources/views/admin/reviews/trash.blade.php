@extends('layouts.admin')

@section('title', 'Thùng rác đánh giá')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-heading text-2xl font-bold text-text">Thùng rác đánh giá</h1>
            <p class="text-sm text-text-muted mt-1">Đánh giá đã xóa mềm — có thể khôi phục hoặc xóa vĩnh viễn</p>
        </div>

        <a href="{{ route('admin.reviews.index') }}"
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
        <form method="GET" action="{{ route('admin.reviews.trash') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-text-muted mb-1">Tìm theo nội dung</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                       placeholder="Nhập nội dung đánh giá..."
                       class="block w-full rounded-md border border-border bg-bg px-3 py-2 text-sm
                              focus:outline-none focus:ring-1 focus:ring-primary">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="px-4 py-2 rounded-md text-sm font-medium text-text-inverse bg-primary
                               hover:bg-primary-hover transition-base">
                    Lọc
                </button>
                <a href="{{ route('admin.reviews.trash') }}"
                   class="px-4 py-2 rounded-md text-sm font-medium text-text-muted border border-border
                          hover:bg-bg-subtle transition-base">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    @if ($reviews->count() > 0)
        <div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-bg-subtle border-b border-border">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">ID</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Tour</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Người dùng</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Số sao</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Nội dung</th>
                            <th class="text-left px-4 py-3 font-medium text-text-muted">Xóa lúc</th>
                            <th class="text-right px-4 py-3 font-medium text-text-muted">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($reviews as $review)
                            <tr class="hover:bg-bg-subtle/50 transition-base">
                                <td class="px-4 py-3 text-text-muted">{{ $review->id }}</td>
                                <td class="px-4 py-3">
                                    @if ($review->tour)
                                        <span class="text-text">{{ $review->tour->name }}</span>
                                    @else
                                        <span class="text-text-muted italic">Tour đã xóa</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($review->user)
                                        <span class="text-text">{{ $review->user->name }}</span>
                                    @else
                                        <span class="text-text-muted italic">User đã xóa</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-text-muted/30' }}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                        @endfor
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-text max-w-xs truncate" title="{{ $review->comment }}">
                                    {{ Str::limit($review->comment, 60) }}
                                </td>
                                <td class="px-4 py-3 text-text-muted">
                                    {{ $review->deleted_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.reviews.restore', $review->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-3 py-1 rounded text-xs font-medium
                                                           text-primary bg-primary-light hover:bg-primary hover:text-white transition-base">
                                                Khôi phục
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.reviews.force-destroy', $review->id) }}" method="POST"
                                              onsubmit="return confirm('Xóa vĩnh viễn đánh giá này? Không thể khôi phục!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 rounded text-xs font-medium
                                                           text-danger bg-danger/10 hover:bg-danger hover:text-white transition-base">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
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