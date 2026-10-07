<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationLink extends Model
{
    protected $fillable = [
        'token',
        'label',
        'expires_at',
        'max_uses',
        'uses',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'uses' => 0,
        'is_active' => true,
    ];

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->uses >= $this->max_uses) {
            return false;
        }

        return true;
    }
}
