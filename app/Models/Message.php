<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'image_path',
        'is_read',
        'is_flagged',
        'is_call_request',
        'call_status',
        'deleted_by_sender',
        'deleted_by_receiver',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_flagged' => 'boolean',
            'is_call_request' => 'boolean',
            'deleted_by_sender' => 'boolean',
            'deleted_by_receiver' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
