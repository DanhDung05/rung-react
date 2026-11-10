<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const LOCATION_MAIN = 'main';
    public const LOCATION_FOOTER = 'footer';
    public const LOCATION_TOP = 'top';

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Menu có nhiều item.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order');
    }

    /**
     * Tất cả item (bao gồm cả con) — dùng khi cần flatten.
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
                    ->orderBy('sort_order');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLocation($query, string $location)
    {
        return $query->where('location', $location);
    }
}