<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Các cột có thể mass assign.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
    ];

    /**
     * Các cột ẩn khi serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast kiểu dữ liệu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Một user viết nhiều bài post.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Chỉ lấy user đang hoạt động.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Lọc theo role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    /**
     * Kiểm tra có phải admin không.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra có phải editor không.
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }
}