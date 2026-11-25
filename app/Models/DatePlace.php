<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatePlace extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'type', 'description', 'address', 'lat', 'lng', 'rating', 'cup_offer', 'created_at'];
}
