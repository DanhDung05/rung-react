@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $page->title }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết trang</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.pages.edit', $page) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.pages.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>
            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $page->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Slug</dt>
                    <dd class="col-span-2 font-mono text-xs">{{ $page->slug }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Template</dt>
                    <dd class="col-span-2">{{ $page->template }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Trạng thái</dt>
                    <dd class="col-span-2">
                        @if ($page->status === 'published')
                            <x-badge variant="success">Đã xuất bản</x-badge>
                        @else
                            <x-badge variant="default">Nháp</x-badge>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @if ($page->featured_image)
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->featured_image) }}"
                 alt="{{ $page->title }}"
                 class="w-full aspect-video object-cover rounded-md">
        </div>
    @endif
</div>

@endsection