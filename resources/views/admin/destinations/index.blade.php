@extends('layouts.admin')

@section('content')

{{-- HEADER --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Điểm đến</h2>
        <p class="text-sm text-text-muted mt-1">Quản lý điểm đến du lịch</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.destinations.trash') }}" variant="outline">
            Thùng rác
        </x-button>
        <x-button href="{{ route('admin.destinations.create') }}" variant="primary">
            + Thêm điểm đến
        </x-button>
    </div>
</div>

{{-- THÔNG BÁO --}}
@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- FILTER --}}
<div class="bg-bg rounded-lg shadow-sm border border-border p-4 mb-6">
    <form method="GET" action="{{ route('admin.destinations.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">

        <div>
            <x-select
                name="status"
                :options="[
                    '' => '-- Trạng thái --',
                    'draft' => 'Nháp',
                    'published' => 'Đã xuất bản',
                ]"
                :selected="request('status')"
            />
        </div>

        <div>
            <x-select
                name="region"
                :options="$regions->mapWithKeys(fn($r) => [$r => $r])->prepend('-- Vùng miền --', '')"
                :selected="request('region')"
            />
        </div>

        <div class="md:col-span-2">
            <x-input name="keyword" placeholder="Tìm theo tên..." :value="request('keyword')" />
        </div>

        <div class="flex gap-2">
            <x-button type="submit" variant="primary" block>Tìm</x-button>
            @if (request()->hasAny(['status', 'region', 'keyword']))
                <x-button href="{{ route('admin.destinations.index') }}" variant="outline">Xóa lọc</x-button>
            @endif
        </div>

    </form>
</div>

{{-- BẢNG --}}
<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">

    @if ($destinations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tên</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-32">Vùng miền</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Điểm cha</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Số tour</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Trạng thái</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-24">Nổi bật</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-40">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($destinations as $destination)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $destination->id }}</td>

                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($destination->image)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($destination->image) }}"
                                             alt="{{ $destination->name }}"
                                             class="w-10 h-10 rounded object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded bg-bg-muted flex items-center justify-center text-text-subtle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-text">{{ $destination->name }}</div>
                                        <div class="text-xs text-text-muted">{{ $destination->slug }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-text-muted">
                                {{ $destination->region ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-text-muted">
                                {{ $destination->parent->name ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-center text-text-muted">
                                {{ $destination->tours_count }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($destination->status === 'published')
                                    <x-badge variant="success">Đã xuất bản</x-badge>
                                @else
                                    <x-badge variant="default">Nháp</x-badge>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if ($destination->is_featured)
                                    <x-badge variant="warning">Nổi bật</x-badge>
                                @else
                                    <span class="text-text-subtle">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.destinations.show', $destination) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Xem">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.destinations.edit', $destination) }}"
                                       class="p-1.5 rounded text-text-muted hover:text-primary hover:bg-primary-light transition-base"
                                       title="Sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.destinations.destroy', $destination) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Chuyển điểm đến \'{{ $destination->name }}\' vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
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
            <x-pagination :paginator="$destinations" />
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="font-heading text-lg font-semibold mb-2">Chưa có điểm đến nào</h3>
            <p class="text-text-muted text-sm mb-6">Bắt đầu bằng cách thêm điểm đến đầu tiên.</p>
            <x-button href="{{ route('admin.destinations.create') }}" variant="primary">
                + Thêm điểm đến đầu tiên
            </x-button>
        </div>
    @endif

</div>

@endsection