<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['event_id', 'alumni_id', 'token', 'sent_at', 'opened_at'];

    protected $casts = [
        'sent_at'   => 'datetime',
        'opened_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
