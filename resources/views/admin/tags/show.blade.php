@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $tag->name }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết tag</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.tags.edit', $tag) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.tags.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>

            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $tag->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Slug</dt>
                    <dd class="col-span-2 font-mono text-xs">{{ $tag->slug }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Số bài viết</dt>
                    <dd class="col-span-2 font-medium">{{ $tag->posts_count }}</dd>
                </div>
            </dl>
        </div>

        @if ($tag->description)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-3">Mô tả</h3>
                <p class="text-sm text-text leading-relaxed">{{ $tag->description }}</p>
            </div>
        @endif
    </div>

    @if ($tag->posts->count() > 0)
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Bài viết gần đây</h3>
            <ul class="space-y-3 text-sm">
                @foreach ($tag->posts as $post)
                    <li>
                        <a href="{{ route('admin.posts.show', $post) }}"
                           class="text-text hover:text-primary transition-base">
                            {{ $post->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>

@endsection