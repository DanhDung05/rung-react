@extends('layouts.admin')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <h2 class="font-heading text-2xl font-bold text-text">{{ $groupConfig['label'] }}</h2>
        <p class="text-sm text-text-muted mt-1">{{ $groupConfig['description'] ?? '' }}</p>
    </div>

    <x-button href="{{ route('admin.settings.index') }}" variant="outline">← Quay lại</x-button>
</div>

@if (session('success'))
    <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
@endif

@if ($errors->any())
    <x-alert type="error" title="Vui lòng kiểm tra lại" class="mb-6">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

<form action="{{ route('admin.settings.update', $group) }}" method="POST" enctype="multipart/form-data"
      class="space-y-6 max-w-3xl">
    @csrf
    @method('PUT')

    <div class="bg-bg rounded-lg shadow-sm border border-border p-6 space-y-6">
        @foreach ($groupConfig['fields'] as $key => $field)
@include('admin.settings.partials.field', [
    'key'   => $key,
    'field' => $field,
    'value' => $current[$key] ?? null,
])
        @endforeach
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
        <x-button href="{{ route('admin.settings.index') }}" variant="outline">Hủy</x-button>
        <x-button type="submit" variant="primary">Lưu cấu hình</x-button>
    </div>

</form>

@endsection