<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = ['album_id', 'file_path', 'caption', 'sort_order'];

    public function album() { return $this->belongsTo(GalleryAlbum::class); }
}
