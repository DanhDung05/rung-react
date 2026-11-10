@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Thùng rác — Danh mục</h2>
        <p class="text-sm text-text-muted mt-1">Danh mục đã xóa mềm. Có thể khôi phục hoặc xóa vĩnh viễn.</p>
    </div>

    <x-button href="{{ route('admin.categories.index') }}" variant="outline">
        ← Quay lại danh sách
    </x-button>
</div>

{{-- Thông báo --}}
@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- Filter --}}
<div class="bg-bg rounded-lg shadow-sm border border-border p-4 mb-6">
    <form method="GET" action="{{ route('admin.categories.trash') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <x-select
                name="type"
                :options="[
                    '' => '-- Tất cả loại --',
                    'tour' => 'Hành trình',
                    'post' => 'Bài viết',
                ]"
                :selected="request('type')"
            />
        </div>

        <div class="md:col-span-2">
            <x-input name="keyword" placeholder="Tìm theo tên..." :value="request('keyword')" />
        </div>

        <div class="flex gap-2">
            <x-button type="submit" variant="primary" block>Tìm</x-button>
            @if (request()->hasAny(['type', 'keyword']))
                <x-button href="{{ route('admin.categories.trash') }}" variant="outline">Xóa lọc</x-button>
            @endif
        </div>
    </form>
</div>

{{-- Bảng --}}
<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">

    @if ($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tên</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-32">Loại</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Danh mục cha</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Ngày xóa</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-56">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $category->id }}</td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($category->image)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="w-10 h-10 rounded object-cover opacity-60">
                                    @else
                                        <div class="w-10 h-10 rounded bg-bg-muted flex items-center justify-center text-text-subtle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-text line-through text-text-muted">{{ $category->name }}</div>
                                        <div class="text-xs text-text-muted">{{ $category->slug }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                @if ($category->type === 'tour')
                                    <x-badge variant="primary">Hành trình</x-badge>
                                @else
                                    <x-badge variant="secondary">Bài viết</x-badge>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-text-muted">
                                {{ $category->parent->name ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-text-muted text-xs">
                                {{ $category->deleted_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Khôi phục --}}
                                    <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                                                       text-success bg-success/10 hover:bg-success/20 transition-base">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Khôi phục
                                        </button>
                                    </form>

                                    {{-- Xóa cứng --}}
                                    <form action="{{ route('admin.categories.force-destroy', $category->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('XÓA VĨNH VIỄN danh mục \'{{ $category->name }}\'? Hành động này KHÔNG THỂ hoàn tác.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                                                       text-danger bg-danger/10 hover:bg-danger/20 transition-base">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Xóa cứng
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-border">
            <x-pagination :paginator="$categories" />
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            <h3 class="font-heading text-lg font-semibold mb-2">Thùng rác trống</h3>
            <p class="text-text-muted text-sm">Không có danh mục nào trong thùng rác.</p>
        </div>
    @endif

</div>

@endsection