<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\Tour;
use App\Services\NavigationService;

class CacheHelper
{
    public const GALLERY_CAROUSEL_KEY = 'gallery_carousel_data';

    /**
     * Xóa cache homepage.
     */
    public static function clearHomepage(): void
    {
        Cache::forget('home_sliders');
        Cache::forget('home_featured_tours');
        Cache::forget('home_featured_destinations');
        Cache::forget('home_latest_posts');
    }

    /**
     * Xóa cache carousel Thư viện ảnh.
     */
    public static function clearGalleryCarousel(): void
    {
        Cache::forget(self::GALLERY_CAROUSEL_KEY);
    }

    /**
     * Xóa cache destination.
     */
    public static function clearDestinationCaches(): void
    {
        Cache::forget('destination_regions');
        Cache::forget('home_featured_destinations');
    }

    /**
     * Xóa cache tour.
     */
    public static function clearTourCaches(): void
    {
        Cache::forget('tour_categories');
        Cache::forget('home_featured_tours');
    }

    /**
     * Xóa cache "tour liên quan" của một tour và các tour cùng danh mục.
     */
    public static function clearTourRelated(int $tourId, ?int $categoryId = null): void
    {
        Cache::forget("tour_related_{$tourId}");

        if ($categoryId) {
            Tour::withTrashed()
                ->where('category_id', $categoryId)
                ->pluck('id')
                ->each(fn ($id) => Cache::forget("tour_related_{$id}"));
        }
    }

    /**
     * Xóa cache post.
     */
    public static function clearPostCaches(): void
    {
        Cache::forget('post_categories');
        Cache::forget('post_recent');
        Cache::forget('post_popular_tags');
        Cache::forget('home_latest_posts');
    }

    /**
     * Xóa cache menu động (main / footer / top).
     */
    public static function clearMenuCaches(): void
    {
        NavigationService::flush();
    }

    /**
     * Xóa toàn bộ cache.
     */
    public static function clearAll(): void
    {
        self::clearHomepage();
        self::clearGalleryCarousel();
        self::clearDestinationCaches();
        self::clearTourCaches();
        self::clearPostCaches();
        self::clearMenuCaches();
    }
}