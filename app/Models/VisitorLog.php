<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'country',
        'region',
        'city',
        'path',
        'referrer',
        'device_type',
        'last_seen_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
