<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'sender_id', 'sender_name', 'sender_avatar', 'message', 'status', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
