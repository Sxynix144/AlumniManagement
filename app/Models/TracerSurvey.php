<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerSurvey extends Model
{
    protected $fillable = ['title', 'description', 'target_years_after', 'is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function responses()
    {
        return $this->hasMany(TracerResponse::class, 'survey_id');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
