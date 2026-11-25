<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Moment extends Model
{
    protected $table = 'posts';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'content',
        'image_url',
        'city',
        'mood',
        'is_approved',
        'is_flagged',
        'shares_count',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'is_flagged' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
