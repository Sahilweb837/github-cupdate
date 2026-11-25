<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeReview extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date_place_id',
        'cafe_name',
        'city',
        'rating',
        'safety_tag',
        'review_text',
        'is_approved',
        'is_flagged',
        'shares_count',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
            'is_flagged' => 'boolean',
            'shares_count' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function datePlace(): BelongsTo
    {
        return $this->belongsTo(DatePlace::class, 'date_place_id');
    }
}
