@php
    $isEdit = isset($tag) && $tag->exists;
    $action = $isEdit ? route('admin.tags.update', $tag) : route('admin.tags.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @method($method)

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4 max-w-2xl">
        <h3 class="font-heading text-lg font-semibold mb-2">Thông tin tag</h3>

        <x-input name="name" label="Tên tag" required
                 :value="old('name', $tag->name ?? '')"
                 placeholder="Ví dụ: trekking" />

        <x-input name="slug" label="Slug (để trống sẽ tự sinh)"
                 :value="old('slug', $tag->slug ?? '')"
                 placeholder="trekking" />

        <x-textarea name="description" label="Mô tả" rows="3"
                    :value="old('description', $tag->description ?? '')" />
    </div>

    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border max-w-2xl">
        <a href="{{ route('admin.tags.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.tags.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo tag' }}
            </x-button>
        </div>
    </div>
</form>