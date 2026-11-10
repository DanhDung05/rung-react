<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các cột có thể mass assign.
     */
    protected $fillable = [
        'type',
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'sort_order',
        'is_active',
    ];

    /**
     * Cast kiểu dữ liệu.
     */
    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
        'parent_id'  => 'integer',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const TYPE_TOUR = 'tour';
    public const TYPE_POST = 'post';

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Danh mục cha (self-reference).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Danh mục con (self-reference).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Các tour thuộc danh mục này (chỉ khi type = 'tour').
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * Các bài viết thuộc danh mục này (chỉ khi type = 'post').
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Lọc theo loại danh mục.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Chỉ lấy danh mục đang hoạt động.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Chỉ lấy danh mục gốc (không có cha).
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
}