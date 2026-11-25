<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;

    protected $fillable = ['user_id', 'from_user_id', 'type', 'title', 'content', 'is_read', 'created_at'];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
