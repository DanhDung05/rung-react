@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Thùng rác — Menu</h2>
        <p class="text-sm text-text-muted mt-1">Menu đã xóa mềm</p>
    </div>

    <x-button href="{{ route('admin.menus.index') }}" variant="outline">← Quay lại</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($menus->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tên menu</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-32">Vị trí</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Ngày xóa</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-56">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menus as $menu)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $menu->id }}</td>
                            <td class="px-4 py-3 font-medium text-text line-through text-text-muted">{{ $menu->name }}</td>
                            <td class="px-4 py-3"><x-badge variant="primary">{{ $menu->location }}</x-badge></td>
                            <td class="px-4 py-3 text-text-muted text-xs">{{ $menu->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.menus.restore', $menu->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium text-success bg-success/10 hover:bg-success/20 transition-base">
                                            Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.menus.force-destroy', $menu->id) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('XÓA VĨNH VIỄN menu \'{{ $menu->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium text-danger bg-danger/10 hover:bg-danger/20 transition-base">
                                            Xóa cứng
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
            <h3 class="font-heading text-lg font-semibold mb-2">Thùng rác trống</h3>
            <p class="text-text-muted text-sm">Không có menu nào trong thùng rác.</p>
        </div>
    @endif
</div>

@endsection