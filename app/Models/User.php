<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    // Use our branded verification email
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function alumniProfile()   { return $this->hasOne(Alumni::class); }
    public function createdEvents()   { return $this->hasMany(Event::class, 'created_by'); }

    public function isAdmin(): bool       { return $this->role === 'admin'; }
    public function isCoordinator(): bool { return $this->role === 'coordinator'; }
    public function isAlumni(): bool      { return $this->role === 'alumni'; }
    public function isStaff(): bool       { return in_array($this->role, ['admin', 'coordinator']); }
}
