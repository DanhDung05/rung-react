@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Quản lý đánh giá</h2>
        <p class="text-sm text-text-muted mt-1">Duyệt hoặc từ chối đánh giá từ khách hàng.</p>
    </div>

    <a href="{{ route('admin.reviews.trash') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium
              text-danger bg-danger/10 hover:bg-danger hover:text-white transition-base">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Thùng rác
    </a>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

{{-- Bộ lọc --}}
<div class="mb-4 flex items-center gap-2">
    <a href="{{ route('admin.reviews.index') }}"
       class="px-3 py-1.5 rounded-md text-sm font-medium {{ !request('status') ? 'bg-primary text-white' : 'bg-bg-subtle text-text hover:bg-bg-subtle/70' }}">
        Tất cả
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
       class="px-3 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'pending' ? 'bg-primary text-white' : 'bg-bg-subtle text-text hover:bg-bg-subtle/70' }}">
        Chờ duyệt
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
       class="px-3 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'approved' ? 'bg-primary text-white' : 'bg-bg-subtle text-text hover:bg-bg-subtle/70' }}">
        Đã duyệt
    </a>
    <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}"
       class="px-3 py-1.5 rounded-md text-sm font-medium {{ request('status') === 'rejected' ? 'bg-primary text-white' : 'bg-bg-subtle text-text hover:bg-bg-subtle/70' }}">
        Từ chối
    </a>
</div>

<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-bg-subtle border-b border-border">
            <tr>
                <th class="text-left px-4 py-3 font-medium">Tour</th>
                <th class="text-left px-4 py-3 font-medium">Người đánh giá</th>
                <th class="text-left px-4 py-3 font-medium">Số sao</th>
                <th class="text-left px-4 py-3 font-medium">Nội dung</th>
                <th class="text-left px-4 py-3 font-medium">Trạng thái</th>
                <th class="text-right px-4 py-3 font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @forelse ($reviews as $review)
                <tr class="hover:bg-bg-subtle/50">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tours.edit', $review->tour) }}" class="text-primary hover:underline">
                            {{ $review->tour->name ?? '—' }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $review->user->name ?? 'Ẩn danh' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-0.5 text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-border' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <p class="max-w-xs truncate">{{ $review->comment }}</p>
                    </td>
                    <td class="px-4 py-3">
                        @if ($review->status === 'pending')
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-warning/10 text-warning">Chờ duyệt</span>
                        @elseif ($review->status === 'approved')
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-success/10 text-success">Đã duyệt</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-danger/10 text-danger">Từ chối</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if ($review->status !== 'approved')
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-success hover:bg-success/10 p-1.5 rounded" title="Duyệt">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                            @endif
                            @if ($review->status !== 'rejected')
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-warning hover:bg-warning/10 p-1.5 rounded" title="Từ chối">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Xóa đánh giá này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-danger hover:bg-danger/10 p-1.5 rounded" title="Xóa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-text-muted">Chưa có đánh giá nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $reviews->links() }}
</div>

@endsection