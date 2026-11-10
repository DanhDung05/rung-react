<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'route_name',
        'target',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'menu_id'    => 'integer',
        'parent_id'  => 'integer',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const TARGET_SELF = '_self';
    public const TARGET_BLANK = '_blank';

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Item thuộc 1 menu.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Item cha (self-reference).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Các item con (self-reference).
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
                    ->orderBy('sort_order');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    /**
     * Lấy URL cuối cùng — ưu tiên route_name, fallback về url.
     */
    public function getResolvedUrlAttribute(): string
    {
        if (!empty($this->route_name)) {
            try {
                return route($this->route_name);
            } catch (\Throwable $e) {
                // Route không tồn tại → fallback về url
            }
        }

        return $this->url ?? '#';
    }
}