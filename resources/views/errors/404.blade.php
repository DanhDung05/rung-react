@extends('layouts.app')

@section('title', 'Không tìm thấy trang — Rung Adventure')

@section('content')

<section class="min-h-[70vh] flex items-center justify-center py-16 lg:py-24 bg-bg">
    <div class="container-base">
        <div class="max-w-3xl mx-auto text-center">

            {{-- Illustration --}}
            <div class="relative mb-8">
                {{-- Số 404 lớn --}}
                <div class="font-heading text-[120px] lg:text-[180px] font-extrabold leading-none
                            bg-gradient-to-br from-primary via-primary to-primary-hover
                            bg-clip-text text-transparent select-none">
                    404
                </div>

                {{-- Icon la bàn --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-full bg-bg border-4 border-primary/20
                                flex items-center justify-center shadow-xl">
                        <svg class="w-10 h-10 lg:w-12 lg:h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Message --}}
            <h1 class="font-heading text-3xl lg:text-4xl font-bold text-text mb-4">
                Ối! Bạn đã đi lạc rồi
            </h1>

            <p class="text-base lg:text-lg text-text-muted mb-8 max-w-xl mx-auto">
                Trang bạn đang tìm kiếm không tồn tại, đã bị di chuyển hoặc đổi tên.
                Hãy quay về trang chủ để tiếp tục khám phá nhé.
            </p>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-12">
                <x-button href="{{ url('/') }}" variant="primary" size="lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Về trang chủ
                </x-button>

                <x-button href="{{ url('/tour') }}" variant="outline" size="lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Khám phá tour
                </x-button>
            </div>

            {{-- Quick links --}}
            <div class="border-t border-border pt-8">
                <p class="text-sm text-text-muted mb-4">Hoặc bạn có thể xem:</p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <a href="{{ url('/diem-den') }}"
                       class="px-4 py-2 rounded-full bg-bg-subtle hover:bg-primary hover:text-text-inverse text-sm font-medium transition-base">
                        Điểm đến
                    </a>
                    <a href="{{ url('/bai-viet') }}"
                       class="px-4 py-2 rounded-full bg-bg-subtle hover:bg-primary hover:text-text-inverse text-sm font-medium transition-base">
                        Bài viết
                    </a>
                    <a href="{{ url('/gioi-thieu') }}"
                       class="px-4 py-2 rounded-full bg-bg-subtle hover:bg-primary hover:text-text-inverse text-sm font-medium transition-base">
                        Giới thiệu
                    </a>
                    <a href="{{ url('/faq') }}"
                       class="px-4 py-2 rounded-full bg-bg-subtle hover:bg-primary hover:text-text-inverse text-sm font-medium transition-base">
                        FAQ
                    </a>
                    <a href="{{ url('/lien-he') }}"
                       class="px-4 py-2 rounded-full bg-bg-subtle hover:bg-primary hover:text-text-inverse text-sm font-medium transition-base">
                        Liên hệ
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection