@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Menu</h2>
        <p class="text-sm text-text-muted mt-1">Quản lý menu điều hướng</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.menus.trash') }}" variant="outline">Thùng rác</x-button>
        <x-button href="{{ route('admin.menus.create') }}" variant="primary">+ Thêm menu</x-button>
    </div>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- Bảng --}}
<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($menus->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tên menu</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-32">Vị trí</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Số item</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Trạng thái</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-56">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $menu->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-text">{{ $menu->name }}</div>
                                @if ($menu->description)
                                    <div class="text-xs text-text-muted">{{ $menu->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <x-badge variant="primary">{{ $menu->location }}</x-badge>
                            </td>
                            <td class="px-4 py-3 text-center text-text-muted">{{ $menu->all_items_count }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($menu->is_active)
                                    <x-badge variant="success">Hiện</x-badge>
                                @else
                                    <x-badge variant="default">Ẩn</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.menus.items', $menu) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                                              text-primary bg-primary-light hover:bg-primary hover:text-text-inverse transition-base"
                                       title="Quản lý items">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                        </svg>
                                        Items
                                    </a>
                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Sửa menu">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Chuyển menu \'{{ $menu->name }}\' vào thùng rác?')">
                                        @csrf @method('DELETE')
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
            <x-pagination :paginator="$menus" />
        </div>
    @else
        <div class="p-12 text-center">
            <h3 class="font-heading text-lg font-semibold mb-2">Chưa có menu nào</h3>
            <p class="text-text-muted text-sm mb-6">Bắt đầu bằng cách thêm menu đầu tiên.</p>
            <x-button href="{{ route('admin.menus.create') }}" variant="primary">+ Thêm menu đầu tiên</x-button>
        </div>
    @endif
</div>

@endsection