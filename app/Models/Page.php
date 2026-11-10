<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'template',
        'meta_title',
        'meta_description',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    public const TEMPLATE_DEFAULT = 'default';

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeByTemplate($query, string $template)
    {
        return $query->where('template', $template);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image)) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        return Storage::disk('public')->url($this->featured_image);
    }
}