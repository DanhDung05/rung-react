<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link_url',
        'link_target',
        'position',
        'sort_order',
        'is_active',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
        'start_at'   => 'datetime',
        'end_at'     => 'datetime',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const POSITION_HOME_HERO = 'home_hero';
    public const POSITION_HOME_MIDDLE = 'home_middle';

    public const TARGET_SELF = '_self';
    public const TARGET_BLANK = '_blank';

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Slider đang hoạt động và trong thời gian hiển thị.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('start_at')
                           ->orWhere('start_at', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('end_at')
                           ->orWhere('end_at', '>=', now());
                     });
    }

    public function scopeByPosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * URL đầy đủ của ảnh.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        // Nếu image đã là URL đầy đủ (http...), trả về nguyên
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }
}