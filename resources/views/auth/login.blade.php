<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Đăng nhập — {{ \App\Models\Setting::get('site_name', 'Rung Adventure') }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-bg text-text antialiased">

<div class="min-h-screen flex items-center justify-center p-4 bg-bg-muted">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-block">
                @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                @if ($logo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logo) }}"
                         alt="{{ \App\Models\Setting::get('site_name', 'Rung Adventure') }}"
                         class="h-12 mx-auto">
                @else
                    <span class="font-heading text-2xl font-bold text-primary">
                        {{ \App\Models\Setting::get('site_name', 'Rung Adventure') }}
                    </span>
                @endif
            </a>
        </div>

        {{-- Card Login --}}
        <div class="bg-bg rounded-xl shadow-lg border border-border p-8">

            <div class="text-center mb-6">
                <h1 class="font-heading text-2xl font-bold text-text mb-2">Đăng nhập</h1>
                <p class="text-sm text-text-muted">Đăng nhập vào khu vực quản trị</p>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <x-alert type="success" class="mb-4">{{ session('success') }}</x-alert>
            @endif

            @if ($errors->any())
                <x-alert type="error" class="mb-4">
                    {{ $errors->first() }}
                </x-alert>
            @endif

            {{-- Form --}}
            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
                @csrf

                <x-input
                    name="email"
                    type="email"
                    label="Email"
                    required
                    autofocus
                    placeholder="admin@example.com"
                />

                <x-input
                    name="password"
                    type="password"
                    label="Mật khẩu"
                    required
                    placeholder="••••••••"
                />

                <x-checkbox
                    name="remember"
                    label="Ghi nhớ đăng nhập"
                    :checked="old('remember')"
                />

                <x-button type="submit" variant="primary" block size="lg">
                    Đăng nhập
                </x-button>
            </form>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-text-muted mt-8">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Rung Adventure') }}
        </p>

    </div>

</div>

</body>
</html>