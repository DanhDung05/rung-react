@extends('layouts.admin')

@section('content')

{{-- ============ HEADER + ACTION ============ --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Danh mục</h2>
        <p class="text-sm text-text-muted mt-1">Quản lý danh mục hành trình và bài viết</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.categories.trash') }}" variant="outline">
            Thùng rác
        </x-button>
        <x-button href="{{ route('admin.categories.create') }}" variant="primary">
            + Thêm danh mục
        </x-button>
    </div>
</div>

{{-- ============ THÔNG BÁO ============ --}}
@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- ============ FILTER ============ --}}
<div class="bg-bg rounded-lg shadow-sm border border-border p-4 mb-6">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">

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
            <x-input
                name="keyword"
                placeholder="Tìm theo tên..."
                :value="request('keyword')"
            />
        </div>

        <div class="flex gap-2">
            <x-button type="submit" variant="primary" block>Tìm</x-button>
            @if (request()->hasAny(['type', 'keyword']))
                <x-button href="{{ route('admin.categories.index') }}" variant="outline">Xóa lọc</x-button>
            @endif
        </div>

    </form>
</div>

{{-- ============ BẢNG ============ --}}
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
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Số tour</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Số bài</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Trạng thái</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-40">Hành động</th>
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
                                             class="w-10 h-10 rounded object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded bg-bg-muted flex items-center justify-center text-text-subtle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-text">{{ $category->name }}</div>
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

                            <td class="px-4 py-3 text-center text-text-muted">
                                {{ $category->tours_count }}
                            </td>

                            <td class="px-4 py-3 text-center text-text-muted">
                                {{ $category->posts_count }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($category->is_active)
                                    <x-badge variant="success">Hiện</x-badge>
                                @else
                                    <x-badge variant="default">Ẩn</x-badge>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.categories.show', $category) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Xem">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Nút xóa mềm --}}
                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Chuyển danh mục \'{{ $category->name }}\' vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded text-text-muted hover:text-danger hover:bg-danger/10 transition-base"
                                                title="Chuyển vào thùng rác">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="px-4 py-3 border-t border-border">
            <x-pagination :paginator="$categories" />
        </div>
    @else
        {{-- Empty state --}}
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <h3 class="font-heading text-lg font-semibold mb-2">Chưa có danh mục nào</h3>
            <p class="text-text-muted text-sm mb-6">Bắt đầu bằng cách thêm danh mục đầu tiên.</p>
            <x-button href="{{ route('admin.categories.create') }}" variant="primary">
                + Thêm danh mục đầu tiên
            </x-button>
        </div>
    @endif

</div>

@endsection