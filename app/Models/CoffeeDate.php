<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoffeeDate extends Model
{
    protected $fillable = ['requester_id', 'partner_id', 'city', 'venue_suggestion', 'preferred_date', 'meeting_preferences', 'status'];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
