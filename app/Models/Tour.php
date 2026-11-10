<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'duration_days',
        'duration_nights',
        'distance_km',
        'altitude_min',
        'altitude_max',
        'difficulty',
        'price_reference',
        'start_point',
        'end_point',
        'best_time',
        'notes',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
        'published_at',
        'sort_order',
        'view_count',
    ];

    protected $casts = [
        'is_featured'     => 'boolean',
        'sort_order'      => 'integer',
        'view_count'      => 'integer',
        'category_id'     => 'integer',
        'duration_days'   => 'integer',
        'duration_nights' => 'integer',
        'distance_km'     => 'decimal:2',
        'altitude_min'    => 'integer',
        'altitude_max'    => 'integer',
        'price_reference' => 'integer',
        'published_at'    => 'datetime',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const DIFFICULTY_EASY = 'easy';
    public const DIFFICULTY_MODERATE = 'moderate';
    public const DIFFICULTY_HARD = 'hard';
    public const DIFFICULTY_EXPERT = 'expert';

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Tour thuộc 1 danh mục.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Nhiều-nhiều với destinations.
     */
    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'tour_destination')
                    ->withTimestamps();
    }

    /**
     * Một tour có nhiều ngày/chặng.
     */
    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItinerary::class)
                    ->orderBy('day_number')
                    ->orderBy('sort_order');
    }


    /**
 * Các đánh giá đã được duyệt của tour.
 */
public function reviews(): HasMany
{
    return $this->hasMany(Review::class)
                ->where('status', Review::STATUS_APPROVED)
                ->latest();
}

/**
 * Tất cả đánh giá (kể cả pending) — dùng cho admin.
 */
public function allReviews(): HasMany
{
    return $this->hasMany(Review::class);
}

/**
 * Điểm đánh giá trung bình (chỉ tính đã duyệt).
 */
public function getAverageRatingAttribute(): float
{
    return round($this->reviews()->avg('rating') ?? 0, 1);
}

/**
 * Tổng số đánh giá đã duyệt.
 */
public function getReviewsCountAttribute(): int
{
    return $this->reviews()->count();
}

    /**
     * Media gắn với tour (polymorphic).
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeDifficulty($query, string $level)
    {
        return $query->where('difficulty', $level);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('excerpt', 'like', "%{$keyword}%")
              ->orWhere('content', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at');
    }
}