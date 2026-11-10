@php
    // ============================================================
    // SITE INFO
    // ============================================================
    $siteName = \App\Models\Setting::get('site_name', 'Rung Adventure');
    $siteDesc = \App\Models\Setting::get('site_description', 'Khám phá những hành trình trekking tuyệt vời.');

    $pageTitle = isset($title) && $title ? "$title — $siteName" : $siteName;
    $pageDesc  = $description ?? $siteDesc;
    $canonical = url()->current();

    // ============================================================
    // THEME — MÀU CHUNG
    // ============================================================
    $primaryColor   = \App\Models\Setting::get('theme_primary_color', '#307a55');
    $secondaryColor = \App\Models\Setting::get('theme_secondary_color', '#f97316');

    // ============================================================
    // THEME — HEADINGS H1-H6
    // ============================================================
    $headingConfigs = [];
    foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
        $headingConfigs[$tag] = [
            'color' => \App\Models\Setting::get("theme_{$tag}_color", '#0f172a'),
            'font'  => \App\Models\Setting::get("theme_{$tag}_font", 'Plus Jakarta Sans'),
        ];
    }

    // Danh sách font cần load
    $fontsToLoad = collect($headingConfigs)->pluck('font')->unique()->filter()->values()->toArray();

    // ============================================================
    // HELPERS
    // ============================================================
    $darken = function ($hex, $amount = 40) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return $hex;
        $r = max(0, hexdec(substr($hex, 0, 2)) - $amount);
        $g = max(0, hexdec(substr($hex, 2, 2)) - $amount);
        $b = max(0, hexdec(substr($hex, 4, 2)) - $amount);
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    };

    $lighten = function ($hex, $ratio = 0.9) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return $hex;
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $r = (int)($r + (255 - $r) * $ratio);
        $g = (int)($g + (255 - $g) * $ratio);
        $b = (int)($b + (255 - $b) * $ratio);
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    };

    $primaryHover   = $darken($primaryColor);
    $primaryLight   = $lighten($primaryColor);
    $secondaryHover = $darken($secondaryColor);
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

{{-- SEO --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDesc }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:type" content="{{ $type ?? 'website' }}">
<meta property="og:url" content="{{ $canonical }}">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">

{{-- Favicon --}}
@php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
@if ($favicon)
    <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($favicon) }}">
@endif

{{-- Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

@php
    // Khởi tạo mảng font với Inter và Caveat (font viết tay)
    $googleFonts = [
        'Inter'  => 'wght@400;500;600;700',
        'Caveat' => 'wght@400;600;700', // Font viết tay cho tiêu đề
    ];

    foreach ($fontsToLoad as $font) {
        // Nếu font chưa có trong mảng và không phải Inter/Caveat thì thêm vào
        if ($font !== 'Inter' && $font !== 'Caveat') {
            $googleFonts[$font] = 'wght@400;500;600;700;800';
        }
    }

    $fontParams = [];
    foreach ($googleFonts as $font => $weights) {
        $fontParams[] = 'family=' . urlencode($font) . ':' . $weights;
    }
    $fontUrl = 'https://fonts.googleapis.com/css2?' . implode('&', $fontParams) . '&display=swap';
@endphp

<link href="{{ $fontUrl }}" rel="stylesheet">

{{-- Vite --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- ============================================================
     DYNAMIC THEME — Inline CSS variables
     ============================================================ --}}
<style id="theme-variables">
    :root {
        --color-primary:         {{ $primaryColor }};
        --color-primary-hover:   {{ $primaryHover }};
        --color-primary-light:   {{ $primaryLight }};
        --color-secondary:       {{ $secondaryColor }};
        --color-secondary-hover: {{ $secondaryHover }};

        --color-h1: {{ $headingConfigs['h1']['color'] }};
        --font-h1:  '{{ $headingConfigs['h1']['font'] }}', 'Inter', sans-serif;

        --color-h2: {{ $headingConfigs['h2']['color'] }};
        --font-h2:  '{{ $headingConfigs['h2']['font'] }}', 'Inter', sans-serif;

        --color-h3: {{ $headingConfigs['h3']['color'] }};
        --font-h3:  '{{ $headingConfigs['h3']['font'] }}', 'Inter', sans-serif;

        --color-h4: {{ $headingConfigs['h4']['color'] }};
        --font-h4:  '{{ $headingConfigs['h4']['font'] }}', 'Inter', sans-serif;

        --color-h5: {{ $headingConfigs['h5']['color'] }};
        --font-h5:  '{{ $headingConfigs['h5']['font'] }}', 'Inter', sans-serif;

        --color-h6: {{ $headingConfigs['h6']['color'] }};
        --font-h6:  '{{ $headingConfigs['h6']['font'] }}', 'Inter', sans-serif;
    }
</style>

{{-- ============================================================
     FORCE HEADING STYLES — !important để chắc chắn thắng mọi CSS
     ============================================================ --}}
<style id="theme-headings-force">
    h1 {
        color: {{ $headingConfigs['h1']['color'] }} !important;
        font-family: '{{ $headingConfigs['h1']['font'] }}', 'Inter', sans-serif !important;
    }
    h2 {
        color: {{ $headingConfigs['h2']['color'] }} !important;
        font-family: '{{ $headingConfigs['h2']['font'] }}', 'Inter', sans-serif !important;
    }
    h3 {
        color: {{ $headingConfigs['h3']['color'] }} !important;
        font-family: '{{ $headingConfigs['h3']['font'] }}', 'Inter', sans-serif !important;
    }
    h4 {
        color: {{ $headingConfigs['h4']['color'] }} !important;
        font-family: '{{ $headingConfigs['h4']['font'] }}', 'Inter', sans-serif !important;
    }
    h5 {
        color: {{ $headingConfigs['h5']['color'] }} !important;
        font-family: '{{ $headingConfigs['h5']['font'] }}', 'Inter', sans-serif !important;
    }
    h6 {
        color: {{ $headingConfigs['h6']['color'] }} !important;
        font-family: '{{ $headingConfigs['h6']['font'] }}', 'Inter', sans-serif !important;
    }
</style>

@stack('styles')

<style>[x-cloak]{display:none!important;}</style>