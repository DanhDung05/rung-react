@extends('layouts.admin')

@section('content')

<div class="mb-6">
    <h2 class="font-heading text-2xl font-bold text-text">Thêm điểm đến</h2>
    <p class="text-sm text-text-muted mt-1">Tạo điểm đến mới</p>
</div>

@if ($errors->any())
    <x-alert type="error" title="Vui lòng kiểm tra lại" class="mb-6">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

@include('admin.destinations.partials.form')

@endsection