<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = ['event_id', 'alumni_id', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
