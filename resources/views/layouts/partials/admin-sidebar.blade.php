@php
    // Menu admin — nhóm theo chức năng
    $menuGroups = [
        [
            'label' => 'Tổng quan',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ],
        ],
        [
            'label' => 'Nội dung',
            'items' => [
                ['label' => 'Hành trình',  'route' => 'admin.tours.index',        'icon' => 'map',      'match' => 'admin.tours.'],
                ['label' => 'Điểm đến',    'route' => 'admin.destinations.index', 'icon' => 'pin',      'match' => 'admin.destinations.'],
                ['label' => 'Danh mục',    'route' => 'admin.categories.index',   'icon' => 'folder',   'match' => 'admin.categories.'],
                ['label' => 'Bài viết',    'route' => 'admin.posts.index',        'icon' => 'document', 'match' => 'admin.posts.'],
                ['label' => 'Tags',        'route' => 'admin.tags.index',         'icon' => 'tag',      'match' => 'admin.tags.'],
                ['label' => 'Đánh giá', 'route' => 'admin.reviews.index', 'icon' => 'star', 'match' => 'admin.reviews.'],
            ],
        ],
        [
            'label' => 'Giao diện',
            'items' => [
                ['label' => 'Slider', 'route' => 'admin.sliders.index', 'icon' => 'photo', 'match' => 'admin.sliders.'],
                ['label' => 'Menu',   'route' => 'admin.menus.index',   'icon' => 'menu',  'match' => 'admin.menus.'],
                ['label' => 'Media',  'route' => 'admin.media.index',   'icon' => 'media', 'match' => 'admin.media.'],
            ],
        ],
        [
            'label' => 'Nội dung tĩnh',
            'items' => [
                ['label' => 'Trang tĩnh', 'route' => 'admin.pages.index',    'icon' => 'document', 'match' => 'admin.pages.'],
                ['label' => 'FAQ',        'route' => 'admin.faqs.index',     'icon' => 'help',     'match' => 'admin.faqs.'],
                ['label' => 'Liên hệ',    'route' => 'admin.contacts.index', 'icon' => 'mail',     'match' => 'admin.contacts.', 'badge' => 'contacts_new'],
            ],
        ],
        [
            'label' => 'Hệ thống',
            'items' => [
                ['label' => 'Cấu hình',  'route' => 'admin.settings.index', 'icon' => 'settings', 'match' => 'admin.settings.'],
                ['label' => 'Tài khoản', 'route' => 'admin.users.index',    'icon' => 'user',     'match' => 'admin.users.'],
            ],
        ],
    ];

    // Badge số liệu — đếm contact mới
    $badges = [
        'contacts_new' => \App\Models\Contact::where('status', \App\Models\Contact::STATUS_NEW)->count(),
    ];

    // SVG icons inline — không cần thư viện ngoài
    $icons = [
        'home'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
        'map'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>',
        'pin'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'folder'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>',
        'document' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
        'tag'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/>',
        'photo'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
        'media'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
        'menu'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>',
        'help'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'mail'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
        'settings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'user'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
        'external' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>',
        'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
    ];
@endphp

<aside
    x-data="{ collapsed: false }"
    :class="collapsed ? 'lg:w-20' : 'lg:w-64'"
    class="hidden lg:flex flex-col bg-text text-text-inverse transition-all duration-300 ease-in-out flex-shrink-0"
>
    {{-- Logo --}}
    <div class="h-16 flex items-center justify-center border-b border-text-inverse/10 px-4">
        <a href="{{ route('admin.dashboard') }}" class="font-heading text-lg font-bold text-text-inverse truncate">
            <span x-show="!collapsed">Rung Admin</span>
            <span x-show="collapsed" x-cloak class="text-xl">RA</span>
        </a>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-6 sidebar-scroll">
        @foreach ($menuGroups as $group)
            <div>
                <p x-show="!collapsed"
                   x-cloak
                   class="px-3 text-xs font-semibold uppercase tracking-wider text-text-inverse/40 mb-2">
                    {{ $group['label'] }}
                </p>

                <ul class="space-y-1">
                    @foreach ($group['items'] as $item)
                        @php
                            $isActive = isset($item['match'])
                                ? request()->routeIs($item['match'] . '*')
                                : request()->routeIs($item['route']);
                            $url = route($item['route']);
                            $badgeKey = $item['badge'] ?? null;
                            $badgeCount = $badgeKey ? ($badges[$badgeKey] ?? 0) : 0;
                        @endphp
                        <li>
                            <a href="{{ $url }}"
                               class="group flex items-center gap-3 px-3 py-2 rounded-md text-sm transition-base
                                      {{ $isActive
                                          ? 'bg-primary text-text-inverse shadow-sm'
                                          : 'text-text-inverse/70 hover:bg-text-inverse/10 hover:text-text-inverse' }}"
                               title="{{ $item['label'] }}">
                                <span class="relative flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $icons[$item['icon']] ?? '' !!}
                                    </svg>
                                    {{-- Badge chấm đỏ khi collapse --}}
                                    @if ($badgeCount > 0)
                                        <span x-show="collapsed" x-cloak
                                              class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-danger"></span>
                                    @endif
                                </span>

                                <span x-show="!collapsed" class="flex-1 truncate">{{ $item['label'] }}</span>

                                {{-- Badge số --}}
                                @if ($badgeCount > 0)
                                    <span x-show="!collapsed"
                                          class="flex-shrink-0 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-semibold
                                                 {{ $isActive ? 'bg-text-inverse/20 text-text-inverse' : 'bg-danger text-text-inverse' }}">
                                        {{ $badgeCount > 99 ? '99+' : $badgeCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        {{-- Link ra frontend --}}
        <div class="pt-2 border-t border-text-inverse/10">
            <ul class="space-y-1">
                <li>
                    <a href="{{ url('/') }}" target="_blank" rel="noopener"
                       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm text-text-inverse/70 hover:bg-text-inverse/10 hover:text-text-inverse transition-base"
                       title="Xem website">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icons['external'] !!}
                        </svg>
                        <span x-show="!collapsed" class="truncate">Xem website</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Toggle collapse --}}
    <div class="border-t border-text-inverse/10 p-2">
        <button
            type="button"
            @click="collapsed = !collapsed"
            class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-md text-sm text-text-inverse/70 hover:bg-text-inverse/10 hover:text-text-inverse transition-base"
            title="Thu gọn/Mở rộng"
        >
            <svg class="w-5 h-5 transition-transform" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span x-show="!collapsed" x-cloak>Thu gọn</span>
        </button>
    </div>
</aside>

<style>
    .sidebar-scroll::-webkit-scrollbar { width: 6px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }
</style>