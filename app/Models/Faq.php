<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'created_by', 'question', 'answer',
        'category', 'sort_order', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function author() { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->orderBy('sort_order')->orderBy('category');
    }
}
