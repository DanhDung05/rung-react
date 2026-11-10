@extends('layouts.admin')

@section('content')

{{-- ============================================================
     THỐNG KÊ CHÍNH — 4 CARD
     ============================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">

    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-primary-light text-primary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
            </div>
            <span class="text-xs text-text-muted">{{ $stats['tours_published'] }} đã đăng</span>
        </div>
        <p class="text-sm text-text-muted mb-1">Tổng hành trình</p>
        <p class="font-heading text-3xl font-bold text-text">{{ $stats['tours_total'] }}</p>
    </div>

    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-text-muted mb-1">Điểm đến</p>
        <p class="font-heading text-3xl font-bold text-text">{{ $stats['destinations_total'] }}</p>
    </div>

    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-success/10 text-success flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-xs text-text-muted">{{ $stats['posts_published'] }} đã đăng</span>
        </div>
        <p class="text-sm text-text-muted mb-1">Bài viết</p>
        <p class="font-heading text-3xl font-bold text-text">{{ $stats['posts_total'] }}</p>
    </div>

    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-info/10 text-info flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <span data-stat="contacts_new_badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-danger/10 text-danger {{ $stats['contacts_new'] > 0 ? '' : 'hidden' }}">
                <span data-stat="contacts_new">{{ $stats['contacts_new'] }}</span> mới
            </span>
        </div>
        <p class="text-sm text-text-muted mb-1">Liên hệ</p>
        <p data-stat="contacts_total" class="font-heading text-3xl font-bold text-text">{{ $stats['contacts_total'] }}</p>
    </div>

</div>

{{-- ============================================================
     THỐNG KÊ PHỤ — 5 CARD + TRẠNG THÁI CONTACT + THÙNG RÁC
     ============================================================ --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-bg rounded-lg p-4 shadow-sm border border-border">
        <p class="text-xs text-text-muted mb-1">Danh mục</p>
        <p class="font-heading text-xl font-bold text-text">{{ $stats['categories_total'] }}</p>
    </div>
    <div class="bg-bg rounded-lg p-4 shadow-sm border border-border">
        <p class="text-xs text-text-muted mb-1">FAQ</p>
        <p class="font-heading text-xl font-bold text-text">{{ $stats['faqs_total'] }}</p>
    </div>
    <div class="bg-bg rounded-lg p-4 shadow-sm border border-border">
        <p class="text-xs text-text-muted mb-1">Trang tĩnh</p>
        <p class="font-heading text-xl font-bold text-text">{{ $stats['pages_total'] }}</p>
    </div>
    <div class="bg-bg rounded-lg p-4 shadow-sm border border-border">
        <p class="text-xs text-text-muted mb-1">Slider</p>
        <p class="font-heading text-xl font-bold text-text">{{ $stats['sliders_total'] }}</p>
    </div>
    <div class="bg-bg rounded-lg p-4 shadow-sm border border-border">
        <p class="text-xs text-text-muted mb-1">Người dùng</p>
        <p class="font-heading text-xl font-bold text-text">{{ $stats['users_total'] }}</p>
    </div>
</div>

{{-- ============================================================
     TRẠNG THÁI CONTACT + THÙNG RÁC
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Trạng thái liên hệ --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <h2 class="font-heading text-base font-bold mb-4">Trạng thái liên hệ</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 rounded-lg bg-danger/5 border border-danger/20">
                <p class="text-xs text-text-muted mb-1">Mới</p>
                <p data-stat="contacts_new" class="font-heading text-xl font-bold text-danger">{{ $stats['contacts_new'] }}</p>
            </div>
            <div class="p-3 rounded-lg bg-info/5 border border-info/20">
                <p class="text-xs text-text-muted mb-1">Đã đọc</p>
                <p data-stat="contacts_read" class="font-heading text-xl font-bold text-info">{{ $stats['contacts_read'] }}</p>
            </div>
            <div class="p-3 rounded-lg bg-success/5 border border-success/20">
                <p class="text-xs text-text-muted mb-1">Đã trả lời</p>
                <p data-stat="contacts_replied" class="font-heading text-xl font-bold text-success">{{ $stats['contacts_replied'] }}</p>
            </div>
            <div class="p-3 rounded-lg bg-bg-subtle border border-border">
                <p class="text-xs text-text-muted mb-1">Lưu trữ</p>
                <p data-stat="contacts_archived" class="font-heading text-xl font-bold text-text-muted">{{ $stats['contacts_archived'] }}</p>
            </div>
        </div>
    </div>

    {{-- Thùng rác --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <h2 class="font-heading text-base font-bold mb-4">Thùng rác</h2>
        <div class="grid grid-cols-3 gap-3">
            <a href="{{ route('admin.tours.trash') }}"
               class="p-3 rounded-lg bg-bg-subtle border border-border hover:border-primary/40 transition-base">
                <p class="text-xs text-text-muted mb-1">Hành trình</p>
                <p class="font-heading text-xl font-bold text-text">{{ $trash['tours'] }}</p>
            </a>
            <a href="{{ route('admin.posts.trash') }}"
               class="p-3 rounded-lg bg-bg-subtle border border-border hover:border-primary/40 transition-base">
                <p class="text-xs text-text-muted mb-1">Bài viết</p>
                <p class="font-heading text-xl font-bold text-text">{{ $trash['posts'] }}</p>
            </a>
            <div class="p-3 rounded-lg bg-bg-subtle border border-border">
                <p class="text-xs text-text-muted mb-1">Liên hệ</p>
                <p class="font-heading text-xl font-bold text-text">{{ $trash['contacts'] }}</p>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     BIỂU ĐỒ 7 NGÀY — CONTACT
     ============================================================ --}}
<div class="bg-bg rounded-lg p-6 shadow-sm border border-border mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-heading text-base font-bold">Liên hệ 7 ngày gần nhất</h2>
        <span class="text-xs text-text-muted">Tổng: {{ array_sum(array_column($chartContacts, 'count')) }}</span>
    </div>

    <div class="flex items-end justify-between gap-2 h-32">
        @foreach ($chartContacts as $day)
            @php
                $heightPercent = $maxContact > 0 ? ($day['count'] / $maxContact) * 100 : 0;
                $heightPercent = max($heightPercent, 4);
            @endphp
            <div class="flex-1 flex flex-col items-center gap-2">
                <div class="text-xs font-semibold text-text">{{ $day['count'] }}</div>
                <div class="w-full bg-primary/20 rounded-t flex items-end"
                     style="height: {{ $heightPercent }}%; min-height: 8px;">
                    <div class="w-full bg-primary rounded-t"
                         style="height: {{ $heightPercent }}%; min-height: 8px;"></div>
                </div>
                <div class="text-xs text-text-muted">{{ $day['date'] }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- ============================================================
     HÀNH ĐỘNG NHANH
     ============================================================ --}}
<div class="bg-bg rounded-lg p-6 shadow-sm border border-border mb-6">
    <h2 class="font-heading text-lg font-bold mb-4">Hành động nhanh</h2>
    <div class="flex flex-wrap gap-3">
        <x-button href="{{ route('admin.tours.create') }}" variant="primary" size="md">
            + Thêm hành trình
        </x-button>
        <x-button href="{{ route('admin.posts.create') }}" variant="secondary" size="md">
            + Thêm bài viết
        </x-button>
        <x-button href="{{ route('admin.destinations.create') }}" variant="outline" size="md">
            + Thêm điểm đến
        </x-button>
        <x-button href="{{ route('admin.settings.index') }}" variant="outline" size="md">
            Cấu hình
        </x-button>
    </div>
</div>

{{-- ============================================================
     TOP XEM NHIỀU
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Top tours --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-base font-bold">Hành trình xem nhiều</h2>
            <a href="{{ route('admin.tours.index') }}" class="text-xs text-primary hover:underline">Tất cả →</a>
        </div>

        @if ($topTours->count() > 0)
            <div class="space-y-2">
                @foreach ($topTours as $i => $tour)
                    <a href="{{ route('admin.tours.edit', $tour) }}"
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-bg-subtle transition-base">
                        <span class="w-6 h-6 flex-shrink-0 rounded-full bg-primary/10 text-primary text-xs font-bold flex items-center justify-center">
                            {{ $i + 1 }}
                        </span>
                        <span class="flex-1 truncate text-sm">{{ $tour->name }}</span>
                        <span class="flex-shrink-0 text-xs text-text-muted">{{ number_format($tour->view_count) }} lượt</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-text-muted text-center py-6">Chưa có dữ liệu.</p>
        @endif
    </div>

    {{-- Top posts --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-base font-bold">Bài viết xem nhiều</h2>
            <a href="{{ route('admin.posts.index') }}" class="text-xs text-primary hover:underline">Tất cả →</a>
        </div>

        @if ($topPosts->count() > 0)
            <div class="space-y-2">
                @foreach ($topPosts as $i => $post)
                    <a href="{{ route('admin.posts.edit', $post) }}"
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-bg-subtle transition-base">
                        <span class="w-6 h-6 flex-shrink-0 rounded-full bg-secondary/10 text-secondary text-xs font-bold flex items-center justify-center">
                            {{ $i + 1 }}
                        </span>
                        <span class="flex-1 truncate text-sm">{{ $post->title }}</span>
                        <span class="flex-shrink-0 text-xs text-text-muted">{{ number_format($post->view_count) }} lượt</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-text-muted text-center py-6">Chưa có dữ liệu.</p>
        @endif
    </div>

</div>

{{-- ============================================================
     TOUR THEO ĐỘ KHÓ + MENU
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Tour theo độ khó --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <h2 class="font-heading text-base font-bold mb-4">Hành trình theo độ khó</h2>

        @php
            $difficultyLabels = [
                'easy'     => ['Dễ', 'text-success', 'bg-success/10'],
                'moderate' => ['Trung bình', 'text-info', 'bg-info/10'],
                'hard'     => ['Khó', 'text-warning-dark', 'bg-warning/10'],
                'expert'   => ['Chuyên gia', 'text-danger', 'bg-danger/10'],
            ];
        @endphp

        <div class="space-y-2">
            @foreach ($difficultyLabels as $key => $info)
                @php $count = $tourByDifficulty[$key] ?? 0; @endphp
                <div class="flex items-center justify-between p-3 rounded-lg {{ $info[2] }}">
                    <span class="text-sm font-medium {{ $info[1] }}">{{ $info[0] }}</span>
                    <span class="text-sm font-bold {{ $info[1] }}">{{ $count }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Menu --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-base font-bold">Menu</h2>
            <a href="{{ route('admin.menus.index') }}" class="text-xs text-primary hover:underline">Tất cả →</a>
        </div>

        @if ($menus->count() > 0)
            <div class="space-y-2">
                @foreach ($menus as $menu)
                    <a href="{{ route('admin.menus.items', $menu) }}"
                       class="flex items-center justify-between p-3 rounded-lg hover:bg-bg-subtle transition-base border border-border">
                        <div>
                            <div class="text-sm font-medium">{{ $menu->name }}</div>
                            <div class="text-xs text-text-muted">{{ $menu->location }}</div>
                        </div>
                        <span class="text-xs text-text-muted">{{ $menu->all_items_count }} items</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-text-muted text-center py-6">Chưa có menu nào.</p>
        @endif
    </div>

</div>

{{-- ============================================================
     3 CỘT DANH SÁCH MỚI NHẤT
     ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Liên hệ mới nhất --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-base font-bold">Liên hệ mới nhất</h2>
            <a href="{{ route('admin.contacts.index') }}"
               class="text-xs text-primary hover:underline">Xem tất cả →</a>
        </div>

        <div id="latest-contacts-list">
            @if ($latestContacts->count() > 0)
                <div class="space-y-3">
                    @foreach ($latestContacts as $contact)
                        <a href="{{ route('admin.contacts.show', $contact) }}"
                           class="block p-3 rounded-lg border border-border hover:border-primary/40 hover:bg-bg-subtle transition-base">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <span class="font-medium text-sm truncate">{{ $contact->name }}</span>
                                @if ($contact->status === \App\Models\Contact::STATUS_NEW)
                                    <span class="flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-danger/10 text-danger">Mới</span>
                                @endif
                            </div>
                            <div class="text-xs text-text-muted truncate">{{ $contact->email }}</div>
                            <div class="text-xs text-text-subtle mt-1">{{ $contact->created_at->diffForHumans() }}</div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-text-muted text-center py-6">Chưa có liên hệ nào.</p>
            @endif
        </div>
    </div>

    {{-- Tour mới nhất --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-base font-bold">Hành trình mới</h2>
            <a href="{{ route('admin.tours.index') }}"
               class="text-xs text-primary hover:underline">Xem tất cả →</a>
        </div>

        @if ($latestTours->count() > 0)
            <div class="space-y-3">
                @foreach ($latestTours as $tour)
                    <a href="{{ route('admin.tours.edit', $tour) }}"
                       class="block p-3 rounded-lg border border-border hover:border-primary/40 hover:bg-bg-subtle transition-base">
                        <div class="font-medium text-sm truncate mb-1">{{ $tour->name }}</div>
                        <div class="flex items-center gap-2 text-xs text-text-muted">
                            @if ($tour->category)
                                <span class="truncate">{{ $tour->category->name }}</span>
                                <span>·</span>
                            @endif
                            @if ($tour->status === \App\Models\Tour::STATUS_PUBLISHED)
                                <span class="text-success">Đã đăng</span>
                            @else
                                <span class="text-warning-dark">Nháp</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-text-muted text-center py-6">Chưa có hành trình nào.</p>
        @endif
    </div>

    {{-- Bài viết mới nhất --}}
    <div class="bg-bg rounded-lg p-6 shadow-sm border border-border">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-heading text-base font-bold">Bài viết mới</h2>
            <a href="{{ route('admin.posts.index') }}"
               class="text-xs text-primary hover:underline">Xem tất cả →</a>
        </div>

        @if ($latestPosts->count() > 0)
            <div class="space-y-3">
                @foreach ($latestPosts as $post)
                    <a href="{{ route('admin.posts.edit', $post) }}"
                       class="block p-3 rounded-lg border border-border hover:border-primary/40 hover:bg-bg-subtle transition-base">
                        <div class="font-medium text-sm truncate mb-1">{{ $post->title }}</div>
                        <div class="flex items-center gap-2 text-xs text-text-muted">
                            @if ($post->category)
                                <span class="truncate">{{ $post->category->name }}</span>
                                <span>·</span>
                            @endif
                            @if ($post->status === \App\Models\Post::STATUS_PUBLISHED)
                                <span class="text-success">Đã đăng</span>
                            @else
                                <span class="text-warning-dark">Nháp</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-text-muted text-center py-6">Chưa có bài viết nào.</p>
        @endif
    </div>

</div>

@endsection