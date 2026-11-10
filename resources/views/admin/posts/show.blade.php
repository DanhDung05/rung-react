@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $post->title }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết bài viết</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.posts.edit', $post) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.posts.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>

            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $post->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Slug</dt>
                    <dd class="col-span-2 font-mono text-xs">{{ $post->slug }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Tác giả</dt>
                    <dd class="col-span-2">{{ $post->author->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Danh mục</dt>
                    <dd class="col-span-2">{{ $post->category->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Trạng thái</dt>
                    <dd class="col-span-2">
                        @if ($post->status === 'published')
                            <x-badge variant="success">Đã xuất bản</x-badge>
                        @else
                            <x-badge variant="default">Nháp</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Lượt xem</dt>
                    <dd class="col-span-2">{{ number_format($post->view_count) }}</dd>
                </div>
            </dl>
        </div>

        @if ($post->excerpt)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-3">Mô tả ngắn</h3>
                <p class="text-sm text-text leading-relaxed">{{ $post->excerpt }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        @if ($post->featured_image)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                     alt="{{ $post->title }}"
                     class="w-full aspect-video object-cover rounded-md">
            </div>
        @endif

        @if ($post->tags->count() > 0)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Tags ({{ $post->tags->count() }})</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <x-badge variant="primary">{{ $tag->name }}</x-badge>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>

@endsection