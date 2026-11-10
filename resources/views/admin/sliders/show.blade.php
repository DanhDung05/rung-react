@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $slider->title ?? 'Slider #' . $slider->id }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết slider</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.sliders.edit', $slider) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.sliders.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Ảnh slider</h3>
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slider->image) }}"
                 alt="{{ $slider->title }}"
                 class="w-full rounded-md">
        </div>
    </div>

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
        <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>
        <dl class="space-y-3 text-sm">
            <div><dt class="text-text-muted text-xs mb-1">ID</dt><dd class="font-medium">#{{ $slider->id }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Tiêu đề</dt><dd>{{ $slider->title ?? '—' }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Phụ đề</dt><dd>{{ $slider->subtitle ?? '—' }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Vị trí</dt><dd>{{ $slider->position }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Link</dt><dd class="text-xs">{{ $slider->link_url ?? '—' }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Cách mở</dt><dd>{{ $slider->link_target }}</dd></div>
            <div><dt class="text-text-muted text-xs mb-1">Trạng thái</dt>
                <dd>@if ($slider->is_active) <x-badge variant="success">Hiện</x-badge> @else <x-badge variant="default">Ẩn</x-badge> @endif</dd>
            </div>
        </dl>
    </div>
</div>

@endsection