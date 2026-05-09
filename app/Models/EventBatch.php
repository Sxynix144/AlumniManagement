<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBatch extends Model
{
    public $timestamps = false;

    protected $fillable = ['event_id', 'graduation_year'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
