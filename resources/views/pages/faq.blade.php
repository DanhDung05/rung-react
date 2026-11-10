@extends('layouts.app')

@php
    $pageKey = 'faq';
    $title = 'Câu hỏi thường gặp';
    $description = 'Các câu hỏi thường gặp về hành trình, thanh toán và dịch vụ của Rung Adventure.';
@endphp

@section('content')

<x-hero-banner
    variant="compact"
    alignment="left"
    :image="$heroImage"
    title="Câu hỏi thường gặp"
    :breadcrumb="[['label' => 'FAQ']]"
>
    <x-slot:meta>
        <span>Khám phá câu trả lời cho những thắc mắc phổ biến nhất của khách hàng</span>
    </x-slot:meta>
</x-hero-banner>

<section class="py-12 lg:py-16 bg-bg">
    <div class="container-base">
        @if ($faqs->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        <div class="bg-bg-subtle rounded-2xl p-6">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-1 h-6 bg-primary rounded-full"></div>
                                <div class="font-heading text-base font-bold">Danh mục</div>
                            </div>
                            <nav class="space-y-1" id="faq-nav">
                                <button type="button" data-faq-filter="all" class="faq-tab w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-all text-left bg-primary text-white shadow-sm">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                        <span>Tất cả câu hỏi</span>
                                    </span>
                                    <span class="text-xs opacity-80 flex-shrink-0">{{ $faqs->flatten()->count() }}</span>
                                </button>
                                @foreach ($faqs as $category => $items)
                                    @if ($category)
                                        <button type="button" data-faq-filter="{{ \Illuminate\Support\Str::slug($category) }}" class="faq-tab w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-sm font-medium transition-all text-left text-text hover:bg-bg hover:text-primary">
                                            <span class="flex items-center gap-2 min-w-0">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>
                                                <span class="truncate">{{ $category }}</span>
                                            </span>
                                            <span class="text-xs text-text-muted flex-shrink-0">{{ $items->count() }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            </nav>
                        </div>
                        <div class="bg-gradient-to-br from-primary to-primary-hover rounded-2xl p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                            <div class="relative">
                                <div class="w-12 h-12 mb-4 rounded-xl bg-white/15 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </div>
                                <div class="font-heading text-lg font-bold mb-2">Không tìm thấy?</div>
                                <p class="text-sm text-white/90 mb-4">Liên hệ với chúng tôi để được giải đáp.</p>
                                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-primary text-sm font-semibold hover:bg-white/90 transition-colors">
                                    Liên hệ ngay
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
                <div class="lg:col-span-3">
                    <div class="mb-8 max-w-xl">
                        <input type="text" id="faq-search" placeholder="Tìm kiếm câu hỏi..." autocomplete="off" class="w-full rounded-xl pl-12 pr-4 py-3 text-base shadow-sm border border-border bg-bg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary placeholder-text-muted">
                        <svg class="w-5 h-5 text-text-muted absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    </div>
                    <div class="space-y-10" id="faq-content">
                        @foreach ($faqs as $category => $items)
                            <div class="faq-group" data-faq-category="{{ $category ? \Illuminate\Support\Str::slug($category) : 'uncategorized' }}">
                                @if ($category)
                                    <div class="flex items-center gap-3 mb-5">
                                        <div class="w-10 h-10 rounded-xl bg-primary-light text-primary flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>
                                        </div>
                                        <div>
                                            <h2 class="font-heading text-xl lg:text-2xl font-bold">{{ $category }}</h2>
                                            <p class="text-sm text-text-muted">{{ $items->count() }} câu hỏi</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="space-y-3">
                                    @foreach ($items as $faq)
                                        <details class="faq-item group bg-bg rounded-xl border border-border transition-all duration-300 hover:border-primary/40 hover:shadow-md" data-faq-question="{{ mb_strtolower($faq->question) }}">
                                            <summary class="flex items-center justify-between gap-4 p-5 cursor-pointer hover:bg-bg-subtle transition-colors list-none">
                                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                                    <div class="w-8 h-8 flex-shrink-0 rounded-lg bg-primary-light text-primary flex items-center justify-center mt-0.5 group-open:bg-primary group-open:text-white transition-colors duration-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01"/></svg>
                                                    </div>
                                                    <div class="font-heading text-base font-semibold text-text flex-1 pt-1">{{ $faq->question }}</div>
                                                </div>
                                                <div class="w-8 h-8 flex-shrink-0 rounded-lg bg-bg-subtle flex items-center justify-center group-open:bg-primary group-open:text-white transition-colors duration-300">
                                                    <svg class="w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </summary>
                                            <div class="px-5 pb-5 pt-0">
                                                <div class="pt-4 pl-12 border-t border-border">
                                                    <div class="text-text leading-relaxed whitespace-pre-line">{!! nl2br(e($faq->answer)) !!}</div>
                                                </div>
                                            </div>
                                        </details>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="faq-no-results" style="display: none;" class="text-center py-16">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-3xl bg-bg-subtle flex items-center justify-center">
                            <svg class="w-10 h-10 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                        </div>
                        <h3 class="font-heading text-lg font-bold mb-2">Không tìm thấy câu hỏi nào</h3>
                        <p class="text-text-muted text-sm">Thử từ khóa khác hoặc liên hệ với chúng tôi.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-bg-subtle flex items-center justify-center">
                    <svg class="w-12 h-12 text-text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-heading text-xl font-bold mb-2">Chưa có câu hỏi nào</h3>
                <p class="text-text-muted mb-6">Vui lòng quay lại sau hoặc liên hệ trực tiếp với chúng tôi.</p>
                <x-button href="{{ route('contact') }}" variant="primary">Liên hệ ngay</x-button>
            </div>
        @endif
    </div>
</section>

<x-breadcrumb-schema :items="[
    ['name' => 'Trang chủ', 'url' => route('home')],
    ['name' => 'Câu hỏi thường gặp', 'url' => route('faq')],
]" />

@php
    $breadcrumbItems = [
        ['name' => 'Trang chủ', 'url' => route('home')],
        ['name' => 'Câu hỏi thường gặp', 'url' => route('faq')],
    ];
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumbItems)->map(function ($item, $index) {
            return ['@type' => 'ListItem', 'position' => $index + 1, 'name' => $item['name'], 'item' => $item['url']];
        })->values()->toArray(),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@php $allFaqs = $faqs->flatten(); @endphp
@if ($allFaqs->count() > 0)
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $allFaqs->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($faq->answer),
                    ],
                ];
            })->values()->toArray(),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endif

@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    function initFaq() {
        const tabs = document.querySelectorAll('.faq-tab');
        const groups = document.querySelectorAll('.faq-group');
        const searchInput = document.getElementById('faq-search');
        const noResults = document.getElementById('faq-no-results');
        if (groups.length === 0) return;
        let currentFilter = 'all';
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                currentFilter = tab.dataset.faqFilter;
                tabs.forEach(function(t) {
                    t.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                    t.classList.add('text-text', 'hover:bg-bg', 'hover:text-primary');
                });
                tab.classList.remove('text-text', 'hover:bg-bg', 'hover:text-primary');
                tab.classList.add('bg-primary', 'text-white', 'shadow-sm');
                applyFilters();
            });
        });
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
            searchInput.addEventListener('keyup', applyFilters);
        }
        function applyFilters() {
            const keyword = searchInput ? searchInput.value.trim().toLowerCase() : '';
            let totalVisible = 0;
            groups.forEach(function(group) {
                const groupCategory = group.dataset.faqCategory || '';
                const categoryMatch = currentFilter === 'all' || groupCategory === currentFilter;
                const items = group.querySelectorAll('.faq-item');
                let visibleInGroup = 0;
                items.forEach(function(item) {
                    const question = (item.dataset.faqQuestion || '').toLowerCase();
                    const questionMatch = keyword === '' || question.indexOf(keyword) !== -1;
                    if (categoryMatch && questionMatch) {
                        item.style.display = '';
                        visibleInGroup++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                if (categoryMatch && visibleInGroup > 0) {
                    group.style.display = '';
                    totalVisible += visibleInGroup;
                } else {
                    group.style.display = 'none';
                }
            });
            if (noResults) {
                noResults.style.display = totalVisible === 0 ? 'block' : 'none';
            }
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFaq);
    } else {
        initFaq();
    }
})();
</script>
@endpush