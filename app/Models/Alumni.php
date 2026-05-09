<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'student_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'graduation_year',
        'course',
        'current_job',
        'company',
        'city',
        'bio',
        'profile_photo',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'rsvps')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopePlaceholder(Builder $query): Builder
    {
        return $query->where('status', 'placeholder');
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('graduation_year', $year);
    }

public function scopeSearch(Builder $query, string $term): Builder
{
    $lower = strtolower($term);

    return $query->where(function ($q) use ($lower) {
        $q->whereRaw("LOWER(first_name) LIKE ?", ["%{$lower}%"])
          ->orWhereRaw("LOWER(last_name) LIKE ?", ["%{$lower}%"])
          ->orWhereRaw("LOWER(student_number) LIKE ?", ["%{$lower}%"])
          ->orWhereRaw("LOWER(first_name || ' ' || last_name) LIKE ?", ["%{$lower}%"])
          ->orWhereRaw("LOWER(last_name || ' ' || first_name) LIKE ?", ["%{$lower}%"]);
    });
}

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isClaimed(): bool
    {
        return $this->user_id !== null;
    }
}
