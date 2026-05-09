<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    protected $fillable = [
        'created_by', 'title', 'body',
        'category', 'is_published', 'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('is_published', true)->orderByDesc('published_at');
    }

    public function getExcerptAttribute(): string
    {
        return \Str::limit(strip_tags($this->body), 160);
    }
}
