<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các cột có thể mass assign.
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'content',
        'image',
        'region',
        'province',
        'latitude',
        'longitude',
        'altitude',
        'meta_title',
        'meta_description',
        'is_featured',
        'sort_order',
        'status',
        'published_at',
    ];

    /**
     * Cast kiểu dữ liệu.
     */
    protected $casts = [
        'is_featured'  => 'boolean',
        'sort_order'   => 'integer',
        'parent_id'    => 'integer',
        'latitude'     => 'decimal:7',
        'longitude'    => 'decimal:7',
        'altitude'     => 'integer',
        'published_at' => 'datetime',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Điểm đến cha (self-reference).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'parent_id');
    }

    /**
     * Các điểm đến con (self-reference).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Destination::class, 'parent_id');
    }

    /**
     * Nhiều-nhiều với tours.
     */
    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_destination')
                    ->withTimestamps();
    }

    /**
     * Media gắn với điểm đến (polymorphic).
     */
    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Chỉ lấy điểm đến đã xuất bản.
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Chỉ lấy điểm đến nổi bật.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Lọc theo vùng miền.
     */
    public function scopeRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Chỉ lấy điểm đến gốc.
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Sắp xếp mặc định.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Tìm kiếm theo tên/mô tả.
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }
}