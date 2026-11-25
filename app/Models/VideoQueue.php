<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoQueue extends Model
{
    protected $table = 'video_queue';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'guest_name',
        'peer_id',
        'gender',
        'target_gender',
        'country',
        'status',
        'matched_with_id',
        'call_id',
        'last_ping_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'last_ping_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
