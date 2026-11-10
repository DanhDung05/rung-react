@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Tài khoản</h2>
        <p class="text-sm text-text-muted mt-1">Quản lý tài khoản quản trị</p>
    </div>

    <x-button href="{{ route('admin.users.create') }}" variant="primary">+ Thêm tài khoản</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- Filter --}}
<div class="bg-bg rounded-lg shadow-sm border border-border p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <x-select name="role"
                      :options="['' => '-- Vai trò --', 'admin' => 'Quản trị viên', 'editor' => 'Biên tập viên']"
                      :selected="request('role')" />
        </div>
        <div>
            <x-select name="status"
                      :options="['' => '-- Trạng thái --', 'active' => 'Đang hoạt động', 'inactive' => 'Đã vô hiệu hóa']"
                      :selected="request('status')" />
        </div>
        <div>
            <x-input name="keyword" placeholder="Tìm theo tên hoặc email..." :value="request('keyword')" />
        </div>
        <div class="flex gap-2">
            <x-button type="submit" variant="primary" block>Tìm</x-button>
            @if (request()->hasAny(['role', 'status', 'keyword']))
                <x-button href="{{ route('admin.users.index') }}" variant="outline">Xóa</x-button>
            @endif
        </div>
    </form>
</div>

{{-- Bảng --}}
<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tài khoản</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-32">Vai trò</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-32">Trạng thái</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Ngày tạo</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-40">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $user->id }}</td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($user->avatar)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) }}"
                                             alt="{{ $user->name }}"
                                             class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-primary text-text-inverse flex items-center justify-center font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-text">
                                            {{ $user->name }}
                                            @if (auth()->id() === $user->id)
                                                <span class="text-xs text-primary">(bạn)</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                @if ($user->role === 'admin')
                                    <x-badge variant="danger">Quản trị viên</x-badge>
                                @else
                                    <x-badge variant="info">Biên tập viên</x-badge>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($user->is_active)
                                    <x-badge variant="success">Hoạt động</x-badge>
                                @else
                                    <x-badge variant="default">Vô hiệu hóa</x-badge>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-text-muted text-xs">
                             {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Xem">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    {{-- Nút vô hiệu hóa / kích hoạt --}}
                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.toggle-active', $user) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('{{ $user->is_active ? 'Vô hiệu hóa' : 'Kích hoạt' }} tài khoản \'{{ $user->name }}\'?')">
                                            @csrf
                                            @method('PATCH')
                                            @if ($user->is_active)
                                                <button type="submit"
                                                        class="p-1.5 rounded text-text-muted hover:text-danger hover:bg-danger/10 transition-base"
                                                        title="Vô hiệu hóa">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="submit"
                                                        class="p-1.5 rounded text-text-muted hover:text-success hover:bg-success/10 transition-base"
                                                        title="Kích hoạt">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-border">
            <x-pagination :paginator="$users" />
        </div>
    @else
        <div class="p-12 text-center">
            <h3 class="font-heading text-lg font-semibold mb-2">Chưa có tài khoản nào</h3>
            <p class="text-text-muted text-sm mb-6">Bắt đầu bằng cách thêm tài khoản đầu tiên.</p>
            <x-button href="{{ route('admin.users.create') }}" variant="primary">+ Thêm tài khoản đầu tiên</x-button>
        </div>
    @endif
</div>

@endsection