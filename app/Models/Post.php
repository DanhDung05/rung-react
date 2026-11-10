<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
        'published_at',
        'view_count',
    ];

    protected $casts = [
        'user_id'      => 'integer',
        'category_id'  => 'integer',
        'is_featured'  => 'boolean',
        'view_count'   => 'integer',
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
     * Bài viết thuộc 1 tác giả.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Bài viết thuộc 1 danh mục.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Nhiều-nhiều với tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
                    ->withTimestamps();
    }

    /**
     * Media gắn với bài viết (polymorphic).
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

    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('excerpt', 'like', "%{$keyword}%")
              ->orWhere('content', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }
}