@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Liên hệ</h2>
        <p class="text-sm text-text-muted mt-1">
            Danh sách liên hệ từ khách hàng
            @if ($newCount > 0)
                — <span class="text-primary font-medium">{{ $newCount }} liên hệ mới</span>
            @endif
        </p>
    </div>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

{{-- Filter --}}
<div class="bg-bg rounded-lg shadow-sm border border-border p-4 mb-6">
    <form method="GET" action="{{ route('admin.contacts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <x-select name="status"
                      :options="[
                          '' => '-- Tất cả (chưa lưu trữ) --',
                          'new' => 'Chưa đọc',
                          'read' => 'Đã đọc',
                          'replied' => 'Đã trả lời',
                          'archived' => 'Đã lưu trữ',
                      ]"
                      :selected="request('status')" />
        </div>
        <div class="md:col-span-2">
            <x-input name="keyword" placeholder="Tìm theo tên, email, tiêu đề..." :value="request('keyword')" />
        </div>
        <div class="flex gap-2">
            <x-button type="submit" variant="primary" block>Tìm</x-button>
            @if (request()->hasAny(['status', 'keyword']))
                <x-button href="{{ route('admin.contacts.index') }}" variant="outline">Xóa</x-button>
            @endif
        </div>
    </form>
</div>

{{-- Bảng --}}
<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($contacts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Người gửi</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Tiêu đề</th>
                        <th class="text-center px-4 py-3 font-semibold text-text w-32">Trạng thái</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Ngày gửi</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-32">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
                        <tr class="border-b border-border hover:bg-bg-subtle/50 {{ $contact->status === 'new' ? 'bg-primary-light/30' : '' }}">
                            <td class="px-4 py-3 text-text-muted">#{{ $contact->id }}</td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-text">
                                    {{ $contact->name }}
                                    @if ($contact->status === 'new')
                                        <span class="inline-block w-2 h-2 rounded-full bg-primary ml-1"></span>
                                    @endif
                                </div>
                                <div class="text-xs text-text-muted">{{ $contact->email }}</div>
                                @if ($contact->phone)
                                    <div class="text-xs text-text-muted">{{ $contact->phone }}</div>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium text-text {{ $contact->status === 'new' ? 'font-semibold' : '' }}">
                                    {{ $contact->subject ?? '(Không có tiêu đề)' }}
                                </div>
                                <div class="text-xs text-text-muted line-clamp-1">
                                    {{ Str::limit($contact->message, 80) }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-center">
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
                            </td>

                            <td class="px-4 py-3 text-text-muted text-xs">
                                {{ $contact->created_at ? $contact->created_at->format('d/m/Y H:i') : '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.contacts.show', $contact) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium
                                              text-primary bg-primary-light hover:bg-primary hover:text-text-inverse transition-base"
                                       title="Xem chi tiết">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Xem
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-border">
            <x-pagination :paginator="$contacts" />
        </div>
    @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-text-subtle mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="font-heading text-lg font-semibold mb-2">Chưa có liên hệ nào</h3>
            <p class="text-text-muted text-sm">Khi khách gửi liên hệ từ trang công khai, sẽ hiển thị ở đây.</p>
        </div>
    @endif
</div>

@endsection