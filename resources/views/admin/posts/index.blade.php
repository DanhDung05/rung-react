@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Bài viết</h2>
        <p class="text-sm text-text-muted mt-1">Quản lý bài viết blog & cẩm nang</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.posts.trash') }}" variant="outline">Thùng rác</x-button>
        <x-button href="{{ route('admin.posts.create') }}" variant="primary">+ Thêm bài viết</x-button>
    </div>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- Filter --}}
<div class="bg-bg rounded-lg shadow-sm border border-border p-4 mb-6">
    <form method="GET" action="{{ route('admin.posts.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div>
            <x-select name="status"
                      :options="['' => '-- Trạng thái --', 'draft' => 'Nháp', 'published' => 'Đã xuất bản']"
                      :selected="request('status')" />
        </div>
        <div>
            <x-select name="category_id"
                      :options="$categories->pluck('name', 'id')->prepend('-- Danh mục --', '')"
                      :selected="request('category_id')" />
        </div>
        <div>
            <x-select name="user_id"
                      :options="$authors->pluck('name', 'id')->prepend('-- Tác giả --', '')"
                      :selected="request('user_id')" />
        </div>
        <div>
            <x-input name="keyword" placeholder="Tìm theo tiêu đề..." :value="request('keyword')" />
        </div>
        <div class="flex gap-2">
            <x-button type="submit" variant="primary" block>Tìm</x-button>
            @if (request()->hasAny(['status', 'category_id', 'user_id', 'keyword']))
                <x-button href="{{ route('admin.posts.index') }}" variant="outline">Xóa</x-button>
            @endif
        </div>
    </form>
</div>

{{-- Bảng --}}
<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($posts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tiêu đề</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Tác giả</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Danh mục</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Tags</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Lượt xem</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-32">Trạng thái</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-40">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $post->id }}</td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($post->featured_image)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                                             alt="{{ $post->title }}"
                                             class="w-12 h-12 rounded object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded bg-bg-muted flex items-center justify-center text-text-subtle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-text">{{ $post->title }}</div>
                                        <div class="text-xs text-text-muted">{{ $post->slug }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-text-muted">
                                {{ $post->author->name ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-text-muted">
                                {{ $post->category->name ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-center text-text-muted">
                                {{ $post->tags_count }}
                            </td>

                            <td class="px-4 py-3 text-center text-text-muted">
                                {{ $post->view_count }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($post->status === 'published')
                                    <x-badge variant="success">Đã xuất bản</x-badge>
                                @else
                                    <x-badge variant="default">Nháp</x-badge>
                                @endif
                                @if ($post->is_featured)
                                    <x-badge variant="warning" class="ml-1">Nổi bật</x-badge>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.posts.show', $post) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Xem">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.posts.destroy', $post) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Chuyển bài viết \'{{ $post->title }}\' vào thùng rác?')">
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

        <div class="px-4 py-3 border-t border-border">
            <x-pagination :paginator="$posts" />
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="font-heading text-lg font-semibold mb-2">Chưa có bài viết nào</h3>
            <p class="text-text-muted text-sm mb-6">Bắt đầu bằng cách thêm bài viết đầu tiên.</p>
            <x-button href="{{ route('admin.posts.create') }}" variant="primary">+ Thêm bài viết đầu tiên</x-button>
        </div>
    @endif
</div>

@endsection