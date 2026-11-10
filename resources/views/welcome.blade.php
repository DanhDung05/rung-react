@extends('layouts.app')

@section('content')

<section class="container-base py-16 lg:py-24">
    <h1 class="font-heading text-4xl lg:text-6xl font-bold mb-6 text-text" data-aos="fade-up">
        Rung Adventure
    </h1>
    <p class="text-text-muted text-lg lg:text-xl mb-8 max-w-2xl leading-relaxed" data-aos="fade-up" data-aos-delay="100">
        Khám phá những hành trình trekking tuyệt vời. Layout đã sẵn sàng.
    </p>

    <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="200">
        <x-button href="#" variant="primary" size="lg">Khám phá hành trình</x-button>
        <x-button href="#" variant="outline" size="lg">Tìm hiểu thêm</x-button>
    </div>
</section>

<section class="container-base py-16 bg-bg-subtle rounded-xl my-8">
    <h2 class="font-heading text-3xl font-bold mb-8 text-text">Test Components</h2>

    <div class="space-y-6 max-w-2xl">

        <x-alert type="success" title="Thành công!">
            Layout, header, footer, component đều hoạt động.
        </x-alert>

        <div class="flex flex-wrap gap-2">
            <x-badge variant="primary">Primary</x-badge>
            <x-badge variant="success">Success</x-badge>
            <x-badge variant="warning">Warning</x-badge>
            <x-badge variant="danger">Danger</x-badge>
        </div>

        <div class="space-y-4 bg-bg p-6 rounded-lg shadow-sm border border-border">
            <x-input name="name" label="Họ tên" placeholder="Nguyễn Văn A" required />
            <x-input name="email" type="email" label="Email" placeholder="email@example.com" />
            <x-textarea name="message" label="Tin nhắn" rows="3" />
            <x-select name="subject" label="Chủ đề" placeholder="-- Chọn --" :options="['tour' => 'Về tour', 'blog' => 'Về blog', 'other' => 'Khác']" />
            <x-checkbox name="agree" label="Tôi đồng ý với điều khoản" />
            <x-button type="submit" variant="primary">Gửi</x-button>
        </div>

    </div>
</section>

<div style="height: 40vh;"></div>

@endsection