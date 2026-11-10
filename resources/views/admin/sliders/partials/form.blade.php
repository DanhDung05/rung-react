@php
    $isEdit = isset($slider) && $slider->exists;
    $action = $isEdit ? route('admin.sliders.update', $slider) : route('admin.sliders.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method($method)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Cột trái --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Nội dung slider</h3>

                <x-input name="title" label="Tiêu đề"
                         :value="old('title', $slider->title ?? '')"
                         placeholder="Khám phá Sapa" />

                <x-input name="subtitle" label="Phụ đề"
                         :value="old('subtitle', $slider->subtitle ?? '')"
                         placeholder="Hành trình trekking 3N2Đ" />
            </div>

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Link</h3>

                <x-input name="link_url" label="URL"
                         :value="old('link_url', $slider->link_url ?? '')"
                         placeholder="/tours/trekking-sapa" />

                <x-select name="link_target" label="Cách mở link" required
                          :options="['_self' => 'Cùng tab', '_blank' => 'Tab mới']"
                          :selected="old('link_target', $slider->link_target ?? '_self')" />
            </div>

            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Thời gian hiển thị (tùy chọn)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input name="start_at" type="datetime-local" label="Bắt đầu hiển thị"
                             :value="old('start_at', isset($slider) && $slider->start_at ? $slider->start_at->format('Y-m-d\TH:i') : '')" />

                    <x-input name="end_at" type="datetime-local" label="Kết thúc hiển thị"
                             :value="old('end_at', isset($slider) && $slider->end_at ? $slider->end_at->format('Y-m-d\TH:i') : '')" />
                </div>
                <p class="text-xs text-text-muted">Để trống cả 2 nếu muốn hiển thị vô thời hạn.</p>
            </div>

        </div>

        {{-- Cột phải --}}
        <div class="space-y-6">

            {{-- Ảnh --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh slider {{ $isEdit ? '' : '*' }}</h3>

                @if ($isEdit && $slider->image)
                    <div class="mb-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slider->image) }}"
                             alt="{{ $slider->title }}"
                             class="w-full aspect-video object-cover rounded-md border border-border">
                        <p class="text-xs text-text-muted mt-2">Ảnh hiện tại</p>
                    </div>
                @endif

                <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                       @if(!$isEdit) required @endif
                       class="block w-full text-sm text-text border border-border rounded-md cursor-pointer
                              file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0
                              file:bg-primary file:text-text-inverse file:font-medium
                              hover:file:bg-primary-hover">
                <p class="text-xs text-text-muted mt-2">
                    JPG, PNG, WEBP. Tối đa 20MB. Khuyến nghị kích thước 1920x800.
                </p>
            </div>

            {{-- Cài đặt --}}
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
                <h3 class="font-heading text-lg font-semibold mb-2">Cài đặt</h3>

                <x-input name="position" label="Vị trí hiển thị" required
                         :value="old('position', $slider->position ?? 'home_hero')"
                         placeholder="home_hero"
                         hint="Ví dụ: home_hero, home_middle" />

                <x-input name="sort_order" type="number" label="Thứ tự"
                         :value="old('sort_order', $slider->sort_order ?? 0)" />

                <x-checkbox name="is_active" value="1" label="Kích hoạt"
                            :checked="old('is_active', $slider->is_active ?? true)" />
            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.sliders.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.sliders.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo slider' }}
            </x-button>
        </div>
    </div>

</form>