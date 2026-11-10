@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Thùng rác — Slider</h2>
        <p class="text-sm text-text-muted mt-1">Slider đã xóa mềm</p>
    </div>

    <x-button href="{{ route('admin.sliders.index') }}" variant="outline">← Quay lại</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($sliders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-32">Ảnh</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tiêu đề</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Ngày xóa</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-56">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sliders as $slider)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $slider->id }}</td>
                            <td class="px-4 py-3">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slider->image) }}"
                                     alt="" class="w-24 h-14 object-cover rounded opacity-60">
                            </td>
                            <td class="px-4 py-3 font-medium text-text line-through text-text-muted">{{ $slider->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-text-muted text-xs">{{ $slider->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.sliders.restore', $slider->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium text-success bg-success/10 hover:bg-success/20 transition-base">
                                            Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.sliders.force-destroy', $slider->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('XÓA VĨNH VIỄN slider này?')">
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
            <x-pagination :paginator="$sliders" />
        </div>
    @else
        <div class="p-12 text-center">
            <h3 class="font-heading text-lg font-semibold mb-2">Thùng rác trống</h3>
            <p class="text-text-muted text-sm">Không có slider nào trong thùng rác.</p>
        </div>
    @endif
</div>

@endsection