<?php

namespace App\Models;

use App\Helpers\CacheHelper;
use Illuminate\Database\Eloquent\Model;

class GallerySlide extends Model
{
    protected $fillable = [
        'title',
        'category',
        'accent',
        'description',
        'slide_path',
        'full_path',
        'placeholder',
        'crop_x',
        'crop_y',
        'full_width',
        'full_height',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'full_width'  => 'integer',
        'full_height' => 'integer',
        'crop_x'     => 'integer',
        'crop_y'     => 'integer',
        'sort_order'  => 'integer',
        'is_active'   => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => CacheHelper::clearGalleryCarousel());
        static::deleted(fn () => CacheHelper::clearGalleryCarousel());
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Slide đang hiển thị.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Thứ tự ổn định: sort_order tăng dần, sau đó id tăng dần.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
