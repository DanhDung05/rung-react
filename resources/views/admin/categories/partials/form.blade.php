@php
    $isEdit = isset($category) && $category->exists;
    $action = $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cột trái: Thông tin chính --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Thông tin cơ bản</h3>

                <x-select
                    name="type"
                    label="Loại danh mục"
                    required
                    :options="['tour' => 'Hành trình', 'post' => 'Bài viết']"
                    :selected="old('type', $category->type ?? 'tour')"
                    :error="$errors->first('type')"
                />

                <x-input
                    name="name"
                    label="Tên danh mục"
                    required
                    :value="old('name', $category->name ?? '')"
                    placeholder="Ví dụ: Trekking miền Bắc"
                />

                <x-input
                    name="slug"
                    label="Slug (để trống sẽ tự sinh)"
                    :value="old('slug', $category->slug ?? '')"
                    placeholder="trekking-mien-bac"
                    hint="Chỉ dùng chữ thường, số và dấu gạch ngang."
                />

                <x-select
                    name="parent_id"
                    label="Danh mục cha"
                    :options="$parents->pluck('name', 'id')->prepend('-- Không có --', '')"
                    :selected="old('parent_id', $category->parent_id ?? '')"
                />

                <x-textarea
                    name="description"
                    label="Mô tả ngắn"
                    :value="old('description', $category->description ?? '')"
                    rows="3"
                />
            </div>

            {{-- SEO --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">SEO</h3>

                <x-input
                    name="meta_title"
                    label="Meta title"
                    :value="old('meta_title', $category->meta_title ?? '')"
                    hint="Để trống sẽ dùng tên danh mục."
                />

                <x-textarea
                    name="meta_description"
                    label="Meta description"
                    :value="old('meta_description', $category->meta_description ?? '')"
                    rows="2"
                    hint="Khuyến nghị 150-160 ký tự."
                />
            </div>

        </div>

        {{-- Cột phải: Ảnh + Cài đặt --}}
        <div class="space-y-6">

            {{-- Ảnh --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>

                @if ($isEdit && $category->image)
                    <div class="mb-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->image) }}"
                             alt="{{ $category->name }}"
                             class="w-full aspect-square object-cover rounded-md border border-border">
                        <p class="text-xs text-text-muted mt-2">Ảnh hiện tại</p>
                    </div>
                @endif

                <input
                    type="file"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                           file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                           file:bg-primary file:text-text-inverse file:font-medium
                           hover:file:bg-primary-hover"
                >
                <p class="text-xs text-text-muted mt-2">
                    JPG, PNG, WEBP. Tối đa 20MB.
                </p>
                @error('image')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cài đặt --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Cài đặt</h3>

                <x-input
                    name="sort_order"
                    type="number"
                    label="Thứ tự hiển thị"
                    :value="old('sort_order', $category->sort_order ?? 0)"
                    hint="Số nhỏ hiển thị trước."
                />

                <x-checkbox
                    name="is_active"
                    value="1"
                    label="Hiển thị trên website"
                    :checked="old('is_active', $category->is_active ?? true)"
                />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.categories.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.categories.index') }}" variant="outline">
                Hủy
            </x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo danh mục' }}
            </x-button>
        </div>
    </div>

</form>