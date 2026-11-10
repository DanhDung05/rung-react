@php
    $isEdit = isset($destination) && $destination->exists;
    $action = $isEdit ? route('admin.destinations.update', $destination) : route('admin.destinations.store');
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

                <x-input
                    name="name"
                    label="Tên điểm đến"
                    required
                    :value="old('name', $destination->name ?? '')"
                    placeholder="Ví dụ: Sapa"
                />

                <x-input
                    name="slug"
                    label="Slug (để trống sẽ tự sinh)"
                    :value="old('slug', $destination->slug ?? '')"
                    placeholder="sapa"
                />

                <x-select
                    name="parent_id"
                    label="Điểm đến cha"
                    :options="$parents->pluck('name', 'id')->prepend('-- Không có --', '')"
                    :selected="old('parent_id', $destination->parent_id ?? '')"
                />

                <x-textarea
                    name="description"
                    label="Mô tả ngắn"
                    :value="old('description', $destination->description ?? '')"
                    rows="3"
                />

                <x-textarea
                    name="content"
                    label="Nội dung chi tiết"
                    :value="old('content', $destination->content ?? '')"
                    rows="8"
                    hint="Có thể nhập HTML."
                />
            </div>

            {{-- Vị trí --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Vị trí địa lý</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        name="region"
                        label="Vùng miền"
                        :value="old('region', $destination->region ?? '')"
                        placeholder="Miền Bắc"
                    />

                    <x-input
                        name="province"
                        label="Tỉnh/Thành"
                        :value="old('province', $destination->province ?? '')"
                        placeholder="Lào Cai"
                    />

                    <x-input
                        name="latitude"
                        type="number"
                        step="0.0000001"
                        label="Vĩ độ (Latitude)"
                        :value="old('latitude', $destination->latitude ?? '')"
                        placeholder="22.3364"
                    />

                    <x-input
                        name="longitude"
                        type="number"
                        step="0.0000001"
                        label="Kinh độ (Longitude)"
                        :value="old('longitude', $destination->longitude ?? '')"
                        placeholder="103.8438"
                    />

                    <x-input
                        name="altitude"
                        type="number"
                        label="Độ cao (mét)"
                        :value="old('altitude', $destination->altitude ?? '')"
                        placeholder="1600"
                    />
                </div>
            </div>

            {{-- SEO --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">SEO</h3>

                <x-input
                    name="meta_title"
                    label="Meta title"
                    :value="old('meta_title', $destination->meta_title ?? '')"
                />

                <x-textarea
                    name="meta_description"
                    label="Meta description"
                    :value="old('meta_description', $destination->meta_description ?? '')"
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

                @if ($isEdit && $destination->image)
                    <div class="mb-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                             alt="{{ $destination->name }}"
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
                <p class="text-xs text-text-muted mt-2">JPG, PNG, WEBP. Tối đa 20MB.</p>
            </div>

            {{-- Trạng thái --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Trạng thái & Cài đặt</h3>

                <x-select
                    name="status"
                    label="Trạng thái"
                    required
                    :options="['draft' => 'Nháp', 'published' => 'Đã xuất bản']"
                    :selected="old('status', $destination->status ?? 'draft')"
                />

                <x-input
                    name="published_at"
                    type="datetime-local"
                    label="Thời gian xuất bản"
                    :value="old('published_at', isset($destination) && $destination->published_at ? $destination->published_at->format('Y-m-d\TH:i') : '')"
                    hint="Để trống sẽ dùng thời điểm hiện tại."
                />

                <x-input
                    name="sort_order"
                    type="number"
                    label="Thứ tự hiển thị"
                    :value="old('sort_order', $destination->sort_order ?? 0)"
                />

                <x-checkbox
                    name="is_featured"
                    value="1"
                    label="Đánh dấu nổi bật"
                    :checked="old('is_featured', $destination->is_featured ?? false)"
                />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.destinations.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.destinations.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo điểm đến' }}
            </x-button>
        </div>
    </div>

</form>