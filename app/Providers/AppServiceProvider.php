<?php

namespace App\Providers;

use App\View\Composers\HeroImageComposer;
use App\View\Composers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Menu động
        View::composer(
            ['layouts.partials.header', 'layouts.partials.footer'],
            MenuComposer::class
        );

        // Hero image — chỉ chia sẻ cho các view index/pages dùng <x-hero-banner>
        View::composer([
            'blog.index',
            'tour.index',
            'destination.index',
            'pages.about',
            'pages.contact',
            'pages.faq',
        ], HeroImageComposer::class);
    }
}