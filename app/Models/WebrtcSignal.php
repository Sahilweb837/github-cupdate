<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebrtcSignal extends Model
{
    protected $fillable = ['video_call_id', 'sender_id', 'type', 'payload'];
}
