<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    @include('layouts.partials.head')
</head>
<body class="page-{{ $pageKey ?? 'default' }} font-sans bg-bg text-text antialiased">

    @include('layouts.partials.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>