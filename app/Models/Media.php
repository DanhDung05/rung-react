<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mediable_type',
        'mediable_id',
        'collection',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'alt_text',
        'caption',
        'sort_order',
    ];

    protected $casts = [
        'mediable_id' => 'integer',
        'file_size'   => 'integer',
        'sort_order'  => 'integer',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const COLLECTION_DEFAULT = 'default';
    public const COLLECTION_FEATURED = 'featured';
    public const COLLECTION_GALLERY = 'gallery';
    public const COLLECTION_BANNER = 'banner';
    public const COLLECTION_CONTENT = 'content';

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Media thuộc về 1 đối tượng (Tour, Post, Destination, ...).
     */
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeCollection($query, string $collection)
    {
        return $query->where('collection', $collection);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeOrphan($query)
    {
        return $query->whereNull('mediable_type')
                     ->whereNull('mediable_id');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * URL đầy đủ của file (dùng disk public).
     */
    public function getUrlAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }
}