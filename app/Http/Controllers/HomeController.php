<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use App\Models\Destination;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Tour;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Trang chủ.
     */
    public function index()
    {
        // Cache 10 phút
        $sliders = Cache::remember('home_sliders', 600, function () {
            return Slider::active()
                ->byPosition('home_hero')
                ->ordered()
                ->get();
        });

        $featuredTours = Cache::remember('home_featured_tours', 600, function () {
            return Tour::published()
                ->featured()
                ->with(['category'])   // Bỏ 'destinations' — không dùng trong card
                ->ordered()
                ->limit(6)
                ->get();
        });

        $featuredDestinations = Cache::remember('home_featured_destinations', 600, function () {
            return Destination::published()
                ->featured()
                ->withCount('tours')
                ->ordered()
                ->limit(6)
                ->get();
        });

        $latestPosts = Cache::remember('home_latest_posts', 600, function () {
            return Post::published()
                ->with(['category', 'author'])
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();
        });

        return Inertia::render('Home', compact(
            'sliders',
            'featuredTours',
            'featuredDestinations',
            'latestPosts'
        ));
    }
}