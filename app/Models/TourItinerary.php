<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourItinerary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tour_id',
        'day_number',
        'title',
        'description',
        'distance_km',
        'altitude',
        'meals',
        'accommodation',
        'sort_order',
    ];

    protected $casts = [
        'tour_id'     => 'integer',
        'day_number'  => 'integer',
        'distance_km' => 'decimal:2',
        'altitude'    => 'integer',
        'sort_order'  => 'integer',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeOrdered($query)
    {
        return $query->orderBy('day_number')->orderBy('sort_order');
    }
}