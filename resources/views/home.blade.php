@extends('layouts.app')

@php
    $pageKey = 'home';
@endphp

@section('content')

    @include('partials.home.hero', ['sliders' => $sliders])
    @include('partials.home.intro')
    @include('partials.home.featured-tours', ['tours' => $featuredTours])
    @include('partials.home.featured-destinations', ['destinations' => $featuredDestinations])
    @include('partials.home.latest-posts', ['posts' => $latestPosts])
    @include('partials.home.why-choose')
    @include('partials.home.gallery')
    @include('partials.home.contact-cta')

@endsection

@push('styles')
    @vite('resources/css/pages/home.css')
@endpush