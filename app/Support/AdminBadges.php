<?php

namespace App\Support;

use App\Models\Contact;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;

/**
 * Số liệu badge Admin dùng chung cho middleware và payload broadcast.
 */
class AdminBadges
{
    private const CACHE_KEY = 'admin_badges_counts';

    public static function counts(): array
    {
        return Cache::remember(self::CACHE_KEY, 10, fn () => [
            'contacts_new'     => Contact::where('status', Contact::STATUS_NEW)->count(),
            'reviews_pending'  => Review::pending()->count(),
        ]);
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
