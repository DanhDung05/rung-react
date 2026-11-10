@php
    $user = auth()->user();
    $initials = $user ? strtoupper(substr($user->name ?? 'A', 0, 1)) : 'A';
    $avatarUrl = ($user && !empty($user->avatar))
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar)
        : null;

    $newContactsCount = \App\Models\Contact::where('status', \App\Models\Contact::STATUS_NEW)->count();
    $latestContacts = \App\Models\Contact::where('status', \App\Models\Contact::STATUS_NEW)
        ->latestFirst()
        ->take(5)
        ->get();

    $roleLabel = 'Quản trị viên';
    if ($user && $user->role === 'editor') {
        $roleLabel = 'Biên tập viên';
    }
@endphp

<header class="h-16 bg-bg border-b border-border flex items-center justify-between px-4 lg:px-6 flex-shrink-0 sticky top-0 z-sticky">

    {{-- LEFT --}}
    <div class="flex items-center gap-3 min-w-0">
        <button type="button" @click="$dispatch('toggle-mobile-sidebar')"
                class="lg:hidden p-2 rounded-lg text-text-muted hover:text-text hover:bg-bg-subtle transition-colors"
                aria-label="Mở menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="min-w-0">
            <h1 class="font-heading text-lg lg:text-xl font-bold text-text truncate">
                {{ $title ?? 'Dashboard' }}
            </h1>
            @isset($subtitle)
                <p class="text-xs text-text-muted truncate hidden sm:block">{{ $subtitle }}</p>
            @endisset
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-1 lg:gap-2">

        {{-- Search --}}
        <div x-data="{ searching: false }" class="hidden md:block relative">
            <button type="button"
                    @click="searching = !searching; if(searching) $nextTick(() => $refs.searchInput.focus())"
                    class="p-2 rounded-lg text-text-muted hover:text-text hover:bg-bg-subtle transition-colors"
                    aria-label="Tìm kiếm" title="Tìm kiếm (Ctrl+K)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                </svg>
            </button>
            <div x-show="searching" x-cloak @click.away="searching = false" @keydown.escape.window="searching = false"
                 class="absolute right-0 top-full mt-2 w-80 bg-bg rounded-xl shadow-xl border border-border p-3 z-dropdown">
                <form action="{{ route('admin.tours.index') }}" method="GET">
                    <div class="relative">
                        <svg class="w-4 h-4 text-text-muted absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                        <input x-ref="searchInput" type="text" name="keyword" placeholder="Tìm hành trình..." autocomplete="off"
                               class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-border bg-bg-subtle focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </form>
            </div>
        </div>

        {{-- Notification bell --}}
        <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
            <button type="button" @click="open = !open"
                    class="relative p-2 rounded-lg text-text-muted hover:text-text hover:bg-bg-subtle transition-colors"
                    aria-label="Thông báo" title="Thông báo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if ($newContactsCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-danger"></span>
                    <span id="notification-badge" class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-bold bg-danger text-text-inverse">
                        {{ $newContactsCount > 9 ? '9+' : $newContactsCount }}
                    </span>
                @else
                    <span id="notification-badge" class="hidden absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-bold bg-danger text-text-inverse">0</span>
                @endif
            </button>

            <div x-show="open" x-cloak @click.away="open = false" @keydown.escape.window="open = false"
                 class="absolute right-0 mt-2 w-80 bg-bg rounded-xl shadow-xl border border-border overflow-hidden z-dropdown origin-top-right">
                <div class="px-4 py-3 border-b border-border flex items-center justify-between">
                    <span class="font-heading text-sm font-bold">Thông báo</span>
                    @if ($newContactsCount > 0)
                        <span class="text-xs text-text-muted">{{ $newContactsCount }} liên hệ mới</span>
                    @endif
                </div>
                
                {{-- ĐÃ THÊM ID CHO PHẦN DANH SÁCH --}}
                <div id="notification-dropdown-list">
                    @if ($latestContacts->count() > 0)
                        <div class="max-h-80 overflow-y-auto">
                            @foreach ($latestContacts as $contact)
                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                   class="block px-4 py-3 border-b border-border last:border-b-0 hover:bg-bg-subtle transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-danger/10 text-danger flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate">{{ $contact->name }}</p>
                                            <p class="text-xs text-text-muted truncate">{{ $contact->email }}</p>
                                            <p class="text-xs text-text-subtle mt-0.5">{{ $contact->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="px-4 py-8 text-center">
                            <svg class="w-10 h-10 mx-auto text-text-subtle mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <p class="text-sm text-text-muted">Không có thông báo mới</p>
                        </div>
                    @endif
                </div>
                <div class="border-t border-border">
                    <a href="{{ route('admin.contacts.index') }}" class="block text-center py-2.5 text-sm font-medium text-primary hover:bg-bg-subtle transition-colors">
                        Xem tất cả liên hệ →
                    </a>
                </div>
            </div>
        </div>

        {{-- View site --}}
        <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer"
           class="hidden md:inline-flex p-2 rounded-lg text-text-muted hover:text-text hover:bg-bg-subtle transition-colors"
           aria-label="Xem website" title="Xem website">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>

        <div class="hidden md:block w-px h-6 bg-border mx-1"></div>

        {{-- User menu --}}
        <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
            <button type="button" @click="open = !open"
                    class="flex items-center gap-2 p-1.5 pr-2 lg:pr-3 rounded-lg hover:bg-bg-subtle transition-colors"
                    :aria-expanded="open.toString()" aria-haspopup="true">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-primary/20">
                @else
                    <div class="w-8 h-8 rounded-full bg-primary text-text-inverse flex items-center justify-center font-semibold text-sm ring-2 ring-primary/20">
                        {{ $initials }}
                    </div>
                @endif
                <div class="hidden md:block text-left">
                    <div class="text-sm font-medium text-text leading-tight">{{ $user->name ?? 'Admin' }}</div>
                    @if ($user && $user->role)
                        <div class="text-xs text-text-muted leading-tight">{{ $roleLabel }}</div>
                    @endif
                </div>
                <svg class="hidden md:block w-4 h-4 text-text-muted transition-transform duration-200"
                     :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak
                 class="absolute right-0 mt-2 w-64 bg-bg rounded-xl shadow-xl border border-border overflow-hidden z-dropdown origin-top-right">
                <div class="px-4 py-3.5 bg-bg-subtle border-b border-border">
                    <div class="flex items-center gap-3">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary text-text-inverse flex items-center justify-center font-semibold">{{ $initials }}</div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-text truncate">{{ $user->name ?? 'Admin' }}</p>
                            <p class="text-xs text-text-muted truncate">{{ $user->email ?? 'admin@example.com' }}</p>
                        </div>
                    </div>
                </div>
                <div class="py-1.5">
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text hover:bg-bg-subtle transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-bg-subtle flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Hồ sơ cá nhân</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text hover:bg-bg-subtle transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-bg-subtle flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Cấu hình</span>
                    </a>
                    <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 px-4 py-2.5 text-sm text-text hover:bg-bg-subtle transition-colors md:hidden">
                        <div class="w-8 h-8 rounded-lg bg-bg-subtle flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                        <span class="font-medium">Xem website</span>
                    </a>
                </div>
                <div class="py-1.5 border-t border-border">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-danger hover:bg-danger/5 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-danger/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="font-semibold">Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo === 'undefined') return;

    window.Echo.channel('admin-notifications')
        .listen('.new-contact', (data) => {
            showToast('📧 Liên hệ mới từ ' + data.name, data.message);

            // Tăng badge chuông ngay lập tức
            const badge = document.getElementById('notification-badge');
            if (badge) {
                badge.classList.remove('hidden');
                badge.textContent = (parseInt(badge.textContent || 0) + 1);
            }

            // Nếu đang ở trang dashboard → fetch AJAX để cập nhật số liệu
            if (window.location.pathname === '/admin/dashboard' || window.location.pathname === '/admin') {
                refreshDashboardStats();
                refreshContactsDropdown();
            }
        })
        .listen('.new-review', (data) => {
            showToast('⭐ Đánh giá mới cho ' + data.tour_name, data.rating + ' sao - ' + data.comment);
        });
});

/**
 * Fetch AJAX lấy số liệu mới và cập nhật HTML.
 */
function refreshDashboardStats() {
    fetch('{{ route('admin.dashboard.stats') }}', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
    })
    .then(res => res.json())
    .then(payload => {
        const s = payload.stats;

        // Cập nhật các con số trong "Trạng thái liên hệ"
        document.querySelectorAll('[data-stat="contacts_new"]').forEach(el => el.textContent = s.contacts_new);
        document.querySelectorAll('[data-stat="contacts_read"]').forEach(el => el.textContent = s.contacts_read);
        document.querySelectorAll('[data-stat="contacts_replied"]').forEach(el => el.textContent = s.contacts_replied);
        document.querySelectorAll('[data-stat="contacts_archived"]').forEach(el => el.textContent = s.contacts_archived);
        document.querySelectorAll('[data-stat="contacts_total"]').forEach(el => el.textContent = s.contacts_total);

        // Badge "X mới" trên card
        document.querySelectorAll('[data-stat="contacts_new_badge"]').forEach(el => {
            el.classList.toggle('hidden', s.contacts_new <= 0);
        });

        // Render lại danh sách "Liên hệ mới nhất"
        const list = document.getElementById('latest-contacts-list');
        if (list) {
            if (payload.latestContacts.length > 0) {
                list.innerHTML = '<div class="space-y-3">' + payload.latestContacts.map(c => `
                    <a href="${c.url}" class="block p-3 rounded-lg border border-border hover:border-primary/40 hover:bg-bg-subtle transition-base">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <span class="font-medium text-sm truncate">${c.name}</span>
                            <span class="flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-danger/10 text-danger">Mới</span>
                        </div>
                        <div class="text-xs text-text-muted truncate">${c.email}</div>
                        <div class="text-xs text-text-subtle mt-1">${c.time}</div>
                    </a>
                `).join('') + '</div>';
            } else {
                list.innerHTML = '<p class="text-sm text-text-muted text-center py-6">Chưa có liên hệ nào.</p>';
            }
        }
    })
    .catch(err => console.error('Lỗi cập nhật dashboard:', err));
}

/**
 * Render lại danh sách trong dropdown chuông.
 */
function refreshContactsDropdown() {
    fetch('{{ route('admin.dashboard.stats') }}', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
    })
    .then(res => res.json())
    .then(payload => {
        const dropdown = document.getElementById('notification-dropdown-list');
        if (!dropdown) return;

        if (payload.latestContacts.length > 0) {
            dropdown.innerHTML = payload.latestContacts.map(c => `
                <a href="${c.url}" class="block px-4 py-3 border-b border-border last:border-b-0 hover:bg-bg-subtle transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-danger/10 text-danger flex items-center justify-center flex-shrink-0 text-xs font-bold">${c.initial}</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">${c.name}</p>
                            <p class="text-xs text-text-muted truncate">${c.email}</p>
                            <p class="text-xs text-text-subtle mt-0.5">${c.time}</p>
                        </div>
                    </div>
                </a>
            `).join('');
        }
    })
    .catch(err => console.error('Lỗi cập nhật dropdown:', err));
}

function showToast(title, message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-bg border border-border rounded-xl shadow-xl p-4 max-w-sm z-[9999] animate-slide-in';
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-sm text-text">${title}</p>
                <p class="text-xs text-text-muted mt-0.5">${message}</p>
            </div>
            <button onclick="this.closest('div').parentElement.remove()" class="text-text-muted hover:text-text">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}
</script>

<style>
@keyframes slide-in {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.3s ease-out; }
</style>

<script>
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const btn = document.querySelector('[aria-label="Tìm kiếm"]');
        if (btn) btn.click();
    }
});
</script>