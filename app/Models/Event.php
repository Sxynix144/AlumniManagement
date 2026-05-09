<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'description',
        'event_date',
        'venue',
        'banner_image',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function batches()
    {
        return $this->hasMany(EventBatch::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function attendingRsvps()
    {
        return $this->hasMany(Rsvp::class)->where('status', 'attending');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function alumni()
    {
        return $this->belongsToMany(Alumni::class, 'rsvps')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function getGraduationYears(): array
    {
        return $this->batches()->pluck('graduation_year')->toArray();
    }

    public function getAttendingCount(): int
    {
        return $this->attendingRsvps()->count();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
