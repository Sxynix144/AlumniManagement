<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $table    = 'notifications_log';
    protected $fillable = ['user_id','type','title','message','link','is_read'];
    protected $casts    = ['is_read' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }

    // ── Static helper to fire a notification ─────────────────────────────────
    public static function send(int $userId, string $type, string $title, string $message, ?string $link = null): void
    {
        static::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
            'is_read' => false,
        ]);
    }

    // ── Icon per type ─────────────────────────────────────────────────────────
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'announcement'     => '📢',
            'request_update'   => '📋',
            'event_invite'     => '📅',
            'approval'         => '✅',
            'tracer_survey'    => '📊',
            default            => '🔔',
        };
    }
}
