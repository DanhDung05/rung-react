<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ isset($title) ? "$title — Admin" : 'Admin — Rung Adventure' }}</title>
    <meta name="robots" content="noindex, nofollow">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="page-admin font-sans bg-bg-muted text-text antialiased">

<div class="flex h-screen overflow-hidden" x-data="{ mobileOpen: false }" @toggle-mobile-sidebar.window="mobileOpen = !mobileOpen">

    {{-- Sidebar desktop --}}
    @include('layouts.partials.admin-sidebar')

    {{-- Sidebar mobile (overlay) --}}
    <div
        x-show="mobileOpen"
        x-cloak
        @click="mobileOpen = false"
        class="lg:hidden fixed inset-0 bg-text/50 z-fixed"
    ></div>

    <div
        x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="lg:hidden fixed inset-y-0 left-0 w-64 bg-text text-text-inverse z-modal overflow-y-auto"
    >
        @include('layouts.partials.admin-sidebar')
    </div>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        @include('layouts.partials.admin-topbar')

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            @yield('content')
        </main>

    </div>

</div>

{{-- Echo (WebSocket) — chỉ nạp ở admin --}}
@vite('resources/js/echo.js')

@stack('scripts')

</body>
</html>