@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $destination->name }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết điểm đến</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.destinations.edit', $destination) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.destinations.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>

            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $destination->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Slug</dt>
                    <dd class="col-span-2 font-mono text-xs">{{ $destination->slug }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Điểm cha</dt>
                    <dd class="col-span-2">{{ $destination->parent->name ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Vùng miền</dt>
                    <dd class="col-span-2">{{ $destination->region ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Tỉnh/Thành</dt>
                    <dd class="col-span-2">{{ $destination->province ?? '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Tọa độ</dt>
                    <dd class="col-span-2">
                        @if ($destination->latitude && $destination->longitude)
                            {{ $destination->latitude }}, {{ $destination->longitude }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Độ cao</dt>
                    <dd class="col-span-2">{{ $destination->altitude ? $destination->altitude . ' m' : '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Trạng thái</dt>
                    <dd class="col-span-2">
                        @if ($destination->status === 'published')
                            <x-badge variant="success">Đã xuất bản</x-badge>
                        @else
                            <x-badge variant="default">Nháp</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Nổi bật</dt>
                    <dd class="col-span-2">
                        @if ($destination->is_featured)
                            <x-badge variant="warning">Có</x-badge>
                        @else
                            <span class="text-text-subtle">Không</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        @if ($destination->description)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-3">Mô tả</h3>
                <p class="text-sm text-text leading-relaxed">{{ $destination->description }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-6">
        @if ($destination->image)
            <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
                <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                     alt="{{ $destination->name }}"
                     class="w-full aspect-square object-cover rounded-md">
            </div>
        @endif

        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thống kê</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-text-muted">Hành trình</span>
                    <span class="font-semibold">{{ $destination->tours_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-text-muted">Điểm đến con</span>
                    <span class="font-semibold">{{ $destination->children->count() }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection