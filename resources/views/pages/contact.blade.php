@extends('layouts.app')

@php
    $pageKey = 'contact';
    $title = 'Liên hệ';
    $description = 'Liên hệ với Rung Adventure để được tư vấn về các hành trình du lịch.';
@endphp

@section('content')

<x-hero-banner
    variant="compact"
    alignment="left"
    :image="$heroImage"
    title="Liên hệ với chúng tôi"
    :breadcrumb="[['label' => 'Liên hệ']]"
>
    <x-slot:meta>
        <span>Chúng tôi luôn sẵn sàng lắng nghe và tư vấn cho bạn về những hành trình tuyệt vời nhất.</span>
    </x-slot:meta>
</x-hero-banner>

<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            <div class="lg:col-span-2">
                @if (session('success'))
                    <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
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
                <form action="{{ route('contact.store') }}" method="POST" class="bg-bg rounded-xl border border-border shadow-sm p-6 lg:p-8 space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input name="name" label="Họ và tên" required :value="old('name')" placeholder="Nguyễn Văn A" />
                        <x-input name="email" type="email" label="Email" required :value="old('email')" placeholder="email@example.com" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-input name="phone" label="Số điện thoại" :value="old('phone')" placeholder="0123 456 789" />
                        <x-input name="subject" label="Tiêu đề" :value="old('subject')" placeholder="Tư vấn tour Sapa" />
                    </div>
                    <x-textarea name="message" label="Nội dung" required rows="6" :value="old('message')" placeholder="Nhập nội dung bạn muốn liên hệ..." hint="Vui lòng mô tả chi tiết để chúng tôi tư vấn chính xác nhất." />
                    <div class="flex items-center justify-between pt-4 border-t border-border">
                        <p class="text-xs text-text-muted">Chúng tôi sẽ phản hồi trong vòng 24 giờ.</p>
                        <x-button type="submit" variant="primary" size="lg">
                            Gửi liên hệ
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </x-button>
                    </div>
                </form>
            </div>
            <aside class="lg:col-span-1">
                <div class="space-y-6 lg:sticky lg:top-24">
                    <div class="bg-bg-subtle rounded-xl p-6">
                        <div class="font-heading text-lg font-bold mb-5">Thông tin liên hệ</div>
                        <ul class="space-y-4">
                            @php $address = \App\Models\Setting::get('contact_address'); @endphp
                            @if ($address)
                                <li class="flex items-start gap-3">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-primary-light text-primary flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-text-muted uppercase tracking-wide mb-1">Địa chỉ</div>
                                        <div class="text-sm text-text">{{ $address }}</div>
                                    </div>
                                </li>
                            @endif
                            @php $phone = \App\Models\Setting::get('contact_phone'); @endphp
                            @if ($phone)
                                <li class="flex items-start gap-3">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-primary-light text-primary flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-text-muted uppercase tracking-wide mb-1">Điện thoại</div>
                                        <a href="tel:{{ $phone }}" class="text-sm text-text hover:text-primary transition-base">{{ $phone }}</a>
                                    </div>
                                </li>
                            @endif
                            @php $email = \App\Models\Setting::get('contact_email'); @endphp
                            @if ($email)
                                <li class="flex items-start gap-3">
                                    <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-primary-light text-primary flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs text-text-muted uppercase tracking-wide mb-1">Email</div>
                                        <a href="mailto:{{ $email }}" class="text-sm text-text hover:text-primary transition-base break-all">{{ $email }}</a>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @php
                        $socials = [
                            'facebook'  => \App\Models\Setting::get('social_facebook'),
                            'instagram' => \App\Models\Setting::get('social_instagram'),
                            'youtube'   => \App\Models\Setting::get('social_youtube'),
                            'tiktok'    => \App\Models\Setting::get('social_tiktok'),
                        ];
                        $hasSocial = array_filter($socials);
                    @endphp
                    @if (!empty($hasSocial))
                        <div class="bg-bg-subtle rounded-xl p-6">
                            <div class="font-heading text-lg font-bold mb-5">Kết nối với chúng tôi</div>
                            <div class="flex flex-wrap gap-3">
                                @if ($socials['facebook'])
                                    <a href="{{ $socials['facebook'] }}" target="_blank" rel="noopener" class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-[#1877F2] text-white hover:opacity-90 transition-base" aria-label="Facebook"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                                @endif
                                @if ($socials['instagram'])
                                    <a href="{{ $socials['instagram'] }}" target="_blank" rel="noopener" class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-gradient-to-br from-[#F58529] via-[#DD2A7B] to-[#8134AF] text-white hover:opacity-90 transition-base" aria-label="Instagram"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0z"/></svg></a>
                                @endif
                                @if ($socials['youtube'])
                                    <a href="{{ $socials['youtube'] }}" target="_blank" rel="noopener" class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-[#FF0000] text-white hover:opacity-90 transition-base" aria-label="YouTube"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                                @endif
                                @if ($socials['tiktok'])
                                    <a href="{{ $socials['tiktok'] }}" target="_blank" rel="noopener" class="w-10 h-10 inline-flex items-center justify-center rounded-lg bg-text text-text-inverse hover:opacity-90 transition-base" aria-label="TikTok"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.589 6.686a4.793 4.793 0 01-3.77-4.245V2h-3.445v13.672a2.896 2.896 0 01-5.201 1.743l-.002-.001.002.001a2.895 2.895 0 013.183-4.51v-3.5a6.329 6.329 0 00-5.394 10.692 6.33 6.33 0 0010.857-4.424V8.687a8.182 8.182 0 004.773 1.526V6.79a4.831 4.831 0 01-1.003-.104z"/></svg></a>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="bg-primary text-text-inverse rounded-xl p-6">
                        <div class="font-heading text-lg font-bold mb-2">Có câu hỏi khác?</div>
                        <p class="text-sm text-text-inverse/90 mb-4">Xem các câu hỏi thường gặp để được giải đáp nhanh.</p>
                        <x-button href="{{ route('faq') }}" variant="secondary" size="md" block>Xem FAQ</x-button>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection