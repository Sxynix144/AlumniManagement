<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniRequest extends Model
{
    protected $table = 'alumni_requests';

    protected $fillable = [
        'alumni_id', 'type', 'notes',
        'status', 'admin_remarks', 'released_at',
    ];

    protected $casts = ['released_at' => 'datetime'];

    // Human-readable labels
    const TYPE_LABELS = [
        'alumni_id'  => '🪪 Alumni ID Card',
        'yearbook'   => '📖 Batch Yearbook',
        'transcript' => '📄 Transcript of Records',
        'certificate'=> '🏅 Certificate / Diploma',
        'other'      => '📋 Other Request',
    ];

    const STATUS_COLORS = [
        'pending'    => 'badge-pending',
        'processing' => 'badge-published',
        'ready'      => 'badge-active',
        'released'   => 'badge-active',
        'rejected'   => 'badge-cancelled',
    ];

    public function alumni() { return $this->belongsTo(Alumni::class); }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'badge-draft';
    }
}
