@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $menu->name }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết menu</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.menus.items', $menu) }}" variant="primary">Quản lý items</x-button>
        <x-button href="{{ route('admin.menus.edit', $menu) }}" variant="outline">Sửa menu</x-button>
        <x-button href="{{ route('admin.menus.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
        <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>
        <dl class="space-y-3 text-sm">
            <div><dt class="text-text-muted text-xs mb-1">ID</dt><dd class="font-medium">#{{ $menu->id }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Vị trí</dt><dd><x-badge variant="primary">{{ $menu->location }}</x-badge></dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Số item</dt><dd class="font-medium">{{ $menu->all_items_count }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Trạng thái</dt>
                <dd>@if ($menu->is_active) <x-badge variant="success">Hiện</x-badge> @else <x-badge variant="default">Ẩn</x-badge> @endif</dd>
            </div>
        </dl>
    </div>

    <div class="lg:col-span-2 bg-bg rounded-lg shadow-sm border border-border p-6">
        <h3 class="font-heading text-lg font-semibold mb-4">Cấu trúc menu</h3>

        @if ($menu->items->count() > 0)
            <ul class="space-y-2">
                @foreach ($menu->items as $item)
                    <li>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="font-medium">{{ $item->label }}</span>
                            @if (!$item->is_active)
                                <x-badge variant="default">Ẩn</x-badge>
                            @endif
                        </div>
                        @if ($item->children->count() > 0)
                            <ul class="ml-6 mt-2 space-y-1.5 border-l-2 border-border pl-4">
                                @foreach ($item->children as $child)
                                    <li class="text-sm text-text-muted">
                                        {{ $child->label }}
                                        @if (!$child->is_active)
                                            <span class="text-xs">(ẩn)</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-text-muted">Chưa có item nào. Nhấn "Quản lý items" để thêm.</p>
        @endif
    </div>
</div>

@endsection