<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Swipe extends Model
{
    public $timestamps = false;

    protected $fillable = ['swiper_id', 'swipee_id', 'type', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
