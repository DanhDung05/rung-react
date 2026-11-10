@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Thùng rác — FAQ</h2>
        <p class="text-sm text-text-muted mt-1">Câu hỏi đã xóa mềm</p>
    </div>

    <x-button href="{{ route('admin.faqs.index') }}" variant="outline">← Quay lại</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
@endif

<div class="bg-bg rounded-lg shadow-sm border border-border overflow-hidden">
    @if ($faqs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-bg-subtle border-b border-border">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-text w-16">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-text">Câu hỏi</th>
                        <th class="text-left px-4 py-3 font-semibold text-text w-40">Ngày xóa</th>
                        <th class="text-right px-4 py-3 font-semibold text-text w-56">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($faqs as $faq)
                        <tr class="border-b border-border hover:bg-bg-subtle/50">
                            <td class="px-4 py-3 text-text-muted">#{{ $faq->id }}</td>
                            <td class="px-4 py-3 text-text-muted line-through">{{ Str::limit($faq->question, 80) }}</td>
                            <td class="px-4 py-3 text-text-muted text-xs">{{ $faq->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.faqs.restore', $faq->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-medium text-success bg-success/10 hover:bg-success/20 transition-base">
                                            Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.faqs.force-destroy', $faq->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('XÓA VĨNH VIỄN câu hỏi này?')">
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
            <x-pagination :paginator="$faqs" />
        </div>
    @else
        <div class="p-12 text-center">
            <h3 class="font-heading text-lg font-semibold mb-2">Thùng rác trống</h3>
            <p class="text-text-muted text-sm">Không có câu hỏi nào trong thùng rác.</p>
        </div>
    @endif
</div>

@endsection