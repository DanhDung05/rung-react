@php
    $isEdit = isset($post) && $post->exists;
    $action = $isEdit ? route('admin.posts.update', $post) : route('admin.posts.store');
    $method = $isEdit ? 'PUT' : 'POST';

    $selectedTags = old('tag_ids', $isEdit
        ? $post->tags->pluck('id')->toArray()
        : []
    );
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cột trái --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Nội dung bài viết</h3>

                <x-input name="title" label="Tiêu đề" required
                         :value="old('title', $post->title ?? '')"
                         placeholder="Ví dụ: 10 kinh nghiệm trekking Sapa" />

                <x-input name="slug" label="Slug (để trống sẽ tự sinh)"
                         :value="old('slug', $post->slug ?? '')" />

                <x-textarea name="excerpt" label="Mô tả ngắn" rows="3"
                            :value="old('excerpt', $post->excerpt ?? '')" />

                <x-tinymce name="content" label="Nội dung"
           :value="old('content', $post->content ?? '')"
           :height="500" />

            {{-- SEO --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">SEO</h3>

                <x-input name="meta_title" label="Meta title"
                         :value="old('meta_title', $post->meta_title ?? '')" />

                <x-textarea name="meta_description" label="Meta description" rows="2"
                            :value="old('meta_description', $post->meta_description ?? '')"
                            hint="Khuyến nghị 150-160 ký tự." />
            </div>

        </div>

        {{-- Cột phải --}}
        <div class="space-y-6">

            {{-- Ảnh --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>

                @if ($isEdit && $post->featured_image)
                    <div class="mb-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) }}"
                             alt="{{ $post->title }}"
                             class="w-full aspect-video object-cover rounded-md border border-border">
                        <p class="text-xs text-text-muted mt-2">Ảnh hiện tại</p>
                    </div>
                @endif

                <input type="file" name="featured_image"
                       accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                              file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                              file:bg-primary file:text-text-inverse file:font-medium
                              hover:file:bg-primary-hover">
                <p class="text-xs text-text-muted mt-2">JPG, PNG, WEBP. Tối đa 20MB.</p>
            </div>

            {{-- Phân loại --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Phân loại</h3>

                <x-select name="user_id" label="Tác giả" required
                          :options="$authors->pluck('name', 'id')->prepend('-- Chọn tác giả --', '')"
                          :selected="old('user_id', $post->user_id ?? '')" />

                <x-select name="category_id" label="Danh mục" required
                          :options="$categories->pluck('name', 'id')->prepend('-- Chọn danh mục --', '')"
                          :selected="old('category_id', $post->category_id ?? '')" />
            </div>

            {{-- Tags --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Tags</h3>

                @if ($tags->count() > 0)
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach ($tags as $tag)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-bg-subtle p-1.5 rounded">
                                <input type="checkbox"
                                       name="tag_ids[]"
                                       value="{{ $tag->id }}"
                                       @checked(in_array($tag->id, $selectedTags))
                                       class="rounded border-border text-primary focus:ring-primary">
                                <span class="text-sm text-text">{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-text-muted">Chưa có tag nào.</p>
                @endif
            </div>

            {{-- Trạng thái --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Trạng thái</h3>

                <x-select name="status" label="Trạng thái" required
                          :options="['draft' => 'Nháp', 'published' => 'Đã xuất bản']"
                          :selected="old('status', $post->status ?? 'draft')" />

                <x-input name="published_at" type="datetime-local" label="Thời gian xuất bản"
                         :value="old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '')" />

                <x-checkbox name="is_featured" value="1" label="Đánh dấu nổi bật"
                            :checked="old('is_featured', $post->is_featured ?? false)" />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.posts.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.posts.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo bài viết' }}
            </x-button>
        </div>
    </div>

</form>