@php
    $isEdit = isset($menu) && $menu->exists;
    $action = $isEdit ? route('admin.menus.update', $menu) : route('admin.menus.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6 max-w-2xl">
    @csrf
    @method($method)

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
        <h3 class="font-heading text-lg font-semibold mb-2">Thông tin menu</h3>

        <x-input name="name" label="Tên menu" required
                 :value="old('name', $menu->name ?? '')"
                 placeholder="Ví dụ: Menu chính" />

        <x-input name="location" label="Vị trí (location)" required
                 :value="old('location', $menu->location ?? '')"
                 placeholder="main, footer, top"
                 hint="Duy nhất. Dùng để code gọi menu ra view." />

        <x-textarea name="description" label="Mô tả" rows="2"
                    :value="old('description', $menu->description ?? '')" />

        <x-checkbox name="is_active" value="1" label="Kích hoạt"
                    :checked="old('is_active', $menu->is_active ?? true)" />
    </div>

    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.menus.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.menus.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo menu' }}
            </x-button>
        </div>
    </div>
</form>