<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $fillable = [
        'created_by', 'event_id', 'title',
        'description', 'cover_photo', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function author()   { return $this->belongsTo(User::class, 'created_by'); }
    public function event()    { return $this->belongsTo(Event::class); }
    public function photos()   { return $this->hasMany(GalleryPhoto::class, 'album_id')->orderBy('sort_order'); }

    public function getCoverAttribute(): ?string
    {
        return $this->cover_photo ?? $this->photos()->value('file_path');
    }
}
