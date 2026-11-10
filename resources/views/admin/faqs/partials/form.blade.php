@php
    $isEdit = isset($faq) && $faq->exists;
    $action = $isEdit ? route('admin.faqs.update', $faq) : route('admin.faqs.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6 max-w-3xl">
    @csrf
    @method($method)

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
        <h3 class="font-heading text-lg font-semibold mb-2">Nội dung câu hỏi</h3>

        <x-input name="category" label="Nhóm (tùy chọn)"
                 :value="old('category', $faq->category ?? '')"
                 placeholder="Ví dụ: Thanh toán, Đặt tour"
                 hint="Dùng để nhóm các câu hỏi cùng chủ đề." />

        <x-textarea name="question" label="Câu hỏi" required rows="2"
                    :value="old('question', $faq->question ?? '')"
                    placeholder="Ví dụ: Tôi cần chuẩn bị gì trước khi đi trekking?" />

        <x-textarea name="answer" label="Câu trả lời" required rows="6"
                    :value="old('answer', $faq->answer ?? '')"
                    placeholder="Nội dung trả lời..." />
    </div>

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-4">
        <h3 class="font-heading text-lg font-semibold mb-2">Cài đặt</h3>

        <x-input name="sort_order" type="number" label="Thứ tự"
                 :value="old('sort_order', $faq->sort_order ?? 0)"
                 hint="Số nhỏ hiển thị trước." />

        <x-checkbox name="is_active" value="1" label="Hiển thị"
                    :checked="old('is_active', $faq->is_active ?? true)" />
    </div>

    <div class="flex items-center justify-between gap-3 pt-4 border-t border-border">
        <a href="{{ route('admin.faqs.index') }}"
           class="text-sm text-text-muted hover:text-text transition-base">
            ← Quay lại danh sách
        </a>

        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.faqs.index') }}" variant="outline">Hủy</x-button>
            <x-button type="submit" variant="primary">
                {{ $isEdit ? 'Cập nhật' : 'Tạo câu hỏi' }}
            </x-button>
        </div>
    </div>
</form>