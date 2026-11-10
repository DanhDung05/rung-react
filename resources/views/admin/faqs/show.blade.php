@extends('layouts.admin')

@section('content')

<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">Chi tiết câu hỏi</h2>
        <p class="text-sm text-text-muted mt-1">#{{ $faq->id }}</p>
    </div>

    <div class="flex items-center gap-2">
        <x-button href="{{ route('admin.faqs.edit', $faq) }}" variant="primary">Sửa</x-button>
        <x-button href="{{ route('admin.faqs.index') }}" variant="outline">Quay lại</x-button>
    </div>
</div>

<div class="max-w-3xl space-y-6">

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6">
        <div class="flex items-center gap-2 mb-4">
            @if ($faq->category)
                <x-badge variant="default">{{ $faq->category }}</x-badge>
            @endif
            @if ($faq->is_active)
                <x-badge variant="success">Hiển thị</x-badge>
            @else
                <x-badge variant="default">Ẩn</x-badge>
            @endif
        </div>

        <h3 class="font-heading text-lg font-semibold text-text mb-4">{{ $faq->question }}</h3>

        <div class="prose prose-sm max-w-none text-text leading-relaxed">
            {!! nl2br(e($faq->answer)) !!}
        </div>
    </div>

</div>

@endsection