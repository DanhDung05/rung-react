@extends('layouts.app')

@php
    $pageKey = 'about';
    $title = $page->meta_title ?? 'Về Rừng Adventure';
    $description = $page->meta_description ?? 'Giới thiệu về Rừng Adventure — Câu chuyện, sứ mệnh, tầm nhìn và giá trị cốt lõi.';
    $image = $page->featured_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($page->featured_image) : null;
@endphp

@section('content')

@php
    $aboutHeroImage = $page->featured_image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($page->featured_image)
        : $heroImage;
@endphp

<x-hero-banner
    variant="compact"
    alignment="left"
    :image="$aboutHeroImage"
    :title="\App\Models\Setting::get('about_hero_title', 'Về Rừng Adventure')"
    :breadcrumb="[['label' => 'Giới thiệu']]"
>
    <x-slot:meta>
        @php $heroSubtitle = \App\Models\Setting::get('about_hero_subtitle'); @endphp
        @if ($heroSubtitle)
            <span>{{ $heroSubtitle }}</span>
        @endif
    </x-slot:meta>
</x-hero-banner>

<section class="py-16 lg:py-24 bg-bg">
    <div class="container-base max-w-4xl">
        <div class="text-center mb-12">
            <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">Về chúng tôi</p>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold">
                {{ \App\Models\Setting::get('about_story_title', 'Câu chuyện của chúng tôi') }}
            </h2>
        </div>
        <div class="prose prose-lg max-w-none post-content text-text">
            {!! $page->content !!}
        </div>
    </div>
</section>

<section class="py-16 lg:py-24 bg-bg-subtle">
    <div class="container-base">
        @php
            $missionTitle   = \App\Models\Setting::get('about_mission_title', 'Sứ mệnh');
            $missionContent = \App\Models\Setting::get('about_mission_content', '');
            $visionTitle    = \App\Models\Setting::get('about_vision_title', 'Tầm nhìn');
            $visionContent  = \App\Models\Setting::get('about_vision_content', '');
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <div class="group bg-bg rounded-2xl p-8 lg:p-10 shadow-sm border border-border hover:shadow-lg transition-all duration-300" data-aos="fade-up">
                <div class="w-16 h-16 rounded-2xl bg-primary-light text-primary flex items-center justify-center mb-6 transition-colors duration-300 group-hover:bg-primary group-hover:text-text-inverse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-heading text-2xl lg:text-3xl font-bold mb-4">{{ $missionTitle }}</h3>
                <p class="text-text-muted leading-relaxed text-lg">{{ $missionContent }}</p>
            </div>
            <div class="group bg-bg rounded-2xl p-8 lg:p-10 shadow-sm border border-border hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mb-6 transition-colors duration-300 group-hover:bg-secondary group-hover:text-text-inverse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="font-heading text-2xl lg:text-3xl font-bold mb-4">{{ $visionTitle }}</h3>
                <p class="text-text-muted leading-relaxed text-lg">{{ $visionContent }}</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 lg:py-24 bg-bg">
    <div class="container-base">
        @php
            $valuesTitle   = \App\Models\Setting::get('about_values_title', 'Giá trị cốt lõi');
            $valuesContent = \App\Models\Setting::get('about_values_content', 'An toàn — Bền vững — Tận tâm — Chuyên nghiệp');
            $values = array_filter(array_map('trim', preg_split('/[—\-,]+/u', $valuesContent)), fn($v) => !empty($v));
            $valueIcons = [
                'an toàn' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                'bền vững' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'tận tâm' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
                'chuyên nghiệp' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            ];
            $defaultIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>';
        @endphp
        <div class="text-center mb-12" data-aos="fade-up">
            <p class="text-primary font-semibold text-sm uppercase tracking-wider mb-2">Chúng tôi tin vào</p>
            <h2 class="font-heading text-3xl lg:text-4xl font-bold">{{ $valuesTitle }}</h2>
        </div>
        @if (count($values) > 0)
            <div class="grid grid-cols-2 md:grid-cols-{{ min(count($values), 4) }} gap-6 lg:gap-8 max-w-5xl mx-auto">
                @foreach ($values as $index => $value)
                    @php
                        $lowerValue = mb_strtolower($value);
                        $icon = $valueIcons[$lowerValue] ?? $defaultIcon;
                    @endphp
                    <div class="group text-center" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-primary-light text-primary flex items-center justify-center transition-all duration-300 group-hover:bg-primary group-hover:text-text-inverse group-hover:scale-110 group-hover:-rotate-3">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                        </div>
                        <div class="font-heading text-lg font-bold text-text">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="py-16 lg:py-20 bg-primary text-text-inverse">
    <div class="container-base">
        <div class="max-w-3xl mx-auto text-center">
            <div class="font-heading text-2xl lg:text-3xl font-bold mb-4">Khám phá cùng chúng tôi</div>
            <p class="text-text-inverse/90 text-lg mb-8">Sẵn sàng cho hành trình tiếp theo của bạn?</p>
            <div class="flex flex-wrap gap-3 justify-center">
                <x-button href="{{ route('tours.index') }}" variant="secondary" size="lg">Xem hành trình</x-button>
                <x-button href="{{ route('contact') }}" variant="outline" size="lg" class="!text-text-inverse !border-text-inverse/40 hover:!bg-text-inverse/10">Liên hệ ngay</x-button>
            </div>
        </div>
    </div>
</section>

@endsection