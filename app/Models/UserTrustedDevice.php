<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTrustedDevice extends Model
{
    protected $fillable = [
        'user_id',
        'token_hash',
        'user_agent',
        'ip_address',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];
}
