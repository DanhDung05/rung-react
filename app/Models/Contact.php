<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'type',
        'people_count',
        'preferred_date',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'tour_id'       => 'integer',
        'people_count'  => 'integer',
        'preferred_date' => 'date',
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const TYPE_GENERAL = 'general';
    public const TYPE_TOUR_INQUIRY = 'tour_inquiry';

    public const STATUS_NEW = 'new';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_ARCHIVED = 'archived';

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class)->withTrashed();
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }
}