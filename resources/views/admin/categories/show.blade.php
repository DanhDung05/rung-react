@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $category->name }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết danh mục</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.categories.edit', $category) }}" variant="primary">
            Sửa
        </x-button>
        <x-button href="{{ route('admin.categories.index') }}" variant="outline">
            Quay lại
        </x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Cột trái --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>

            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $category->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Loại</dt>
                    <dd class="col-span-2">
                        @if ($category->type === 'tour')
                            <x-badge variant="primary">Hành trình</x-badge>
                        @else
                            <x-badge variant="secondary">Bài viết</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Slug</dt>
                    <dd class="col-span-2 font-mono text-xs">{{ $category->slug }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Danh mục cha</dt>
                    <dd class="col-span-2">{{ $category->parent->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Trạng thái</dt>
                    <dd class="col-span-2">
                        @if ($category->is_active)
                            <x-badge variant="success">Hiển thị</x-badge>
                        @else
                            <x-badge variant="default">Ẩn</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Thứ tự</dt>
                    <dd class="col-span-2">{{ $category->sort_order }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Ngày tạo</dt>
                    <dd class="col-span-2">{{ $category->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>

        @if ($category->description)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-3">Mô tả</h3>
                <p class="text-sm text-text leading-relaxed">{{ $category->description }}</p>
            </div>
        @endif
    </div>

    {{-- Cột phải --}}
    <div class="space-y-6">

        @if ($category->image)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->image) }}"
                     alt="{{ $category->name }}"
                     class="w-full aspect-square object-cover rounded-md">
            </div>
        @endif

        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thống kê</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-text-muted">Hành trình</span>
                    <span class="font-semibold">{{ $category->tours_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-text-muted">Bài viết</span>
                    <span class="font-semibold">{{ $category->posts_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-text-muted">Danh mục con</span>
                    <span class="font-semibold">{{ $category->children->count() }}</span>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection