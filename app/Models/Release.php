<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'feature'     => 'Tính năng mới',
        'improvement' => 'Cải tiến',
        'fix'         => 'Sửa lỗi',
        'security'    => 'Bảo mật',
    ];

    public const STATUSES = [
        'released'    => 'Đã phát hành',
        'development' => 'Đang phát triển',
    ];

    protected $fillable = [
        'version',
        'previous_version',
        'title',
        'category',
        'status',
        'content',
        'released_at',
        'created_by',
    ];

    protected $casts = [
        'released_at' => 'date',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
