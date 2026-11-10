@props([
    'title'       => null,
    'description' => null,
    'keywords'    => null,
    'image'       => null,
    'type'        => 'website',    // website | article
    'noindex'     => false,
])

@php
    $siteName    = \App\Models\Setting::get('site_name', 'Rung Adventure');
    $defaultDesc = \App\Models\Setting::get('seo_default_description', 'Khám phá những hành trình trekking tuyệt vời cùng Rung Adventure.');
    $defaultImg  = \App\Models\Setting::get('site_logo', null);

    $pageTitle   = $title ? "$title — $siteName" : $siteName;
    $pageDesc    = $description ?? $defaultDesc;
    $pageImage   = $image ?? $defaultImg;
    $currentUrl  = url()->current();
@endphp

{{-- Title --}}
<title>{{ $pageTitle }}</title>

{{-- Meta cơ bản --}}
<meta name="description" content="{{ $pageDesc }}">
@if ($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
@if ($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow">
@endif

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $currentUrl }}">
@if ($pageImage)
    <meta property="og:image" content="{{ $pageImage }}">
@endif

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
@if ($pageImage)
    <meta name="twitter:image" content="{{ $pageImage }}">
@endif

{{-- Canonical --}}
<link rel="canonical" href="{{ $currentUrl }}">

{{-- Favicon --}}
@php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
@if ($favicon)
    <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($favicon) }}">
@endif