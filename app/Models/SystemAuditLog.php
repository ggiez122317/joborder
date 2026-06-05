<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemAuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_role',
        'event_type',
        'action',
        'description',
        'http_method',
        'route_name',
        'path',
        'ip_address',
        'user_agent',
        'target_type',
        'target_id',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
