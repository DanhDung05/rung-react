@php
    $isEdit = isset($page) && $page->exists;
    $action = $isEdit ? route('admin.pages.update', $page) : route('admin.pages.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cột trái --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Nội dung trang</h3>

                <x-input name="title" label="Tiêu đề" required
                         :value="old('title', $page->title ?? '')"
                         placeholder="Ví dụ: Giới thiệu" />

                <x-input name="slug" label="Slug (để trống sẽ tự sinh)"
                         :value="old('slug', $page->slug ?? '')"
                         placeholder="gioi-thieu" />

               <x-tinymce name="content" label="Nội dung"
           :value="old('content', $page->content ?? '')" />

            {{-- SEO --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">SEO</h3>

                <x-input name="meta_title" label="Meta title"
                         :value="old('meta_title', $page->meta_title ?? '')" />

                <x-textarea name="meta_description" label="Meta description" rows="2"
                            :value="old('meta_description', $page->meta_description ?? '')"
                            hint="Khuyến nghị 150-160 ký tự." />
            </div>

        </div>

        {{-- Cột phải --}}
        <div class="space-y-6">

            {{-- Ảnh --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>

                @if ($isEdit && $page->featured_image)
                    <div class="mb-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->featured_image) }}"
                             alt="{{ $page->title }}"
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

            {{-- Trạng thái --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Trạng thái & Cài đặt</h3>

                <x-select name="status" label="Trạng thái" required
                          :options="['draft' => 'Nháp', 'published' => 'Đã xuất bản']"
                          :selected="old('status', $page->status ?? 'draft')" />

                <x-input name="published_at" type="datetime-local" label="Thời gian xuất bản"
                         :value="old('published_at', isset($page) && $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '')" />

                <x-select name="template" label="Template"
                          :options="['default' => 'Mặc định', 'fullwidth' => 'Toàn chiều rộng']"
                          :selected="old('template', $page->template ?? 'default')"
                          hint="Mẫu hiển thị trang." />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.pages.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.pages.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo trang' }}
            </x-button>
        </div>
    </div>

</form>