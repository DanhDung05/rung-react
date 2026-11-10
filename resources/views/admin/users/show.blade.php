@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $user->name }}</h2>
        <p class="text-sm text-text-muted mt-1">Chi tiết tài khoản</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.users.edit', $user) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.users.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin</h3>
            <dl class="space-y-3 text-sm">
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">ID</dt>
                    <dd class="col-span-2 font-medium">#{{ $user->id }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Email</dt>
                    <dd class="col-span-2">{{ $user->email }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Vai trò</dt>
                    <dd class="col-span-2">
                        @if ($user->role === 'admin')
                            <x-badge variant="danger">Quản trị viên</x-badge>
                        @else
                            <x-badge variant="info">Biên tập viên</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Trạng thái</dt>
                    <dd class="col-span-2">
                        @if ($user->is_active)
                            <x-badge variant="success">Hoạt động</x-badge>
                        @else
                            <x-badge variant="default">Vô hiệu hóa</x-badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Ngày tạo</dt>
                    <dd class="col-span-2">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <dt class="text-text-muted">Cập nhật</dt>
                    <dd class="col-span-2">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Ảnh đại diện</h3>
            @if ($user->avatar)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) }}"
                     alt="{{ $user->name }}"
                     class="w-full aspect-square object-cover rounded-full">
            @else
                <div class="w-full aspect-square rounded-full bg-primary text-text-inverse flex items-center justify-center text-6xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thống kê</h3>
            <div class="flex items-center justify-between text-sm">
                <span class="text-text-muted">Bài viết</span>
                <span class="font-semibold">{{ $user->posts_count ?? 0 }}</span>
            </div>
        </div>
    </div>

</div>

@endsection