@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Chi tiết liên hệ #{{ $contact->id }}</h2>
        <p class="text-sm text-text-muted mt-1">
            {{ $contact->created_at ? $contact->created_at->format('d/m/Y H:i') : '—' }}
        </p>
    </div>

    <x-button href="{{ route('admin.contacts.index') }}" variant="outline">← Quay lại</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Cột trái: Nội dung --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Thông tin người gửi --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin người gửi</h3>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-text-muted text-xs mb-1">Tên</dt>
                    <dd class="font-medium text-text">{{ $contact->name }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted text-xs mb-1">Email</dt>
                    <dd>
                        <a href="mailto:{{ $contact->email }}" class="text-primary hover:underline">
                            {{ $contact->email }}
                        </a>
                    </dd>
                </div>
                @if ($contact->phone)
                    <div>
                        <dt class="text-text-muted text-xs mb-1">Điện thoại</dt>
                        <dd>
                            <a href="tel:{{ $contact->phone }}" class="text-primary hover:underline">
                                {{ $contact->phone }}
                            </a>
                        </dd>
                    </div>
                @endif
                <div>
                    <dt class="text-text-muted text-xs mb-1">Tiêu đề</dt>
                    <dd class="font-medium text-text">{{ $contact->subject ?? '(Không có tiêu đề)' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Nội dung --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Nội dung</h3>
            <div class="text-sm text-text leading-relaxed whitespace-pre-wrap">{{ $contact->message }}</div>
        </div>

        {{-- Trả lời nhanh --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Trả lời</h3>
            <p class="text-sm text-text-muted mb-3">
                Nhấn nút bên dưới để mở email trả lời trong ứng dụng email mặc định.
            </p>
            <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject ?? 'Liên hệ') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-primary text-text-inverse hover:bg-primary-hover transition-base text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Trả lời qua Email
            </a>
        </div>

    </div>

    {{-- Cột phải: Trạng thái + Audit --}}
    <div class="space-y-6">

        {{-- Trạng thái --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Trạng thái</h3>

            <div class="mb-4">
                @switch($contact->status)
                    @case('new')
                        <x-badge variant="primary">Chưa đọc</x-badge>
                        @break
                    @case('read')
                        <x-badge variant="info">Đã đọc</x-badge>
                        @break
                    @case('replied')
                        <x-badge variant="success">Đã trả lời</x-badge>
                        @break
                    @case('archived')
                        <x-badge variant="default">Đã lưu trữ</x-badge>
                        @break
                @endswitch
            </div>

            <div class="space-y-2">
                @foreach (['read' => 'Đánh dấu đã đọc', 'replied' => 'Đánh dấu đã trả lời', 'archived' => 'Lưu trữ', 'new' => 'Đánh dấu chưa đọc'] as $status => $label)
                    @if ($contact->status !== $status)
                        <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 rounded-md text-sm transition-base
                                           {{ $status === 'archived' ? 'text-danger hover:bg-danger/10' : 'text-text hover:bg-bg-subtle' }}">
                                {{ $label }}
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Audit --}}
        <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
            <h3 class="font-heading text-lg font-semibold mb-4">Thông tin kỹ thuật</h3>
            <dl class="space-y-3 text-xs">
                <div>
                    <dt class="text-text-muted mb-1">IP</dt>
                    <dd class="font-mono">{{ $contact->ip_address ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted mb-1">User Agent</dt>
                    <dd class="font-mono break-all">{{ $contact->user_agent ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted mb-1">Cập nhật cuối</dt>
                    <dd>{{ $contact->updated_at ? $contact->updated_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
            </dl>
        </div>

    </div>

</div>

@endsection