<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdTemplate extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'back_image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Resolve the active ID template image URL with a secure fallback.
     *
     * @return string
     */
    public static function getActiveImageUrl(): string
    {
        $active = self::where('is_active', true)->first();

        if ($active && $active->image_path) {
            return asset('storage/' . $active->image_path);
        }

        return asset('assets/idv3.jpg');
    }

    /**
     * Resolve the active ID template Back image URL (returns null if not uploaded).
     *
     * @return string|null
     */
    public static function getActiveBackImageUrl(): ?string
    {
        $active = self::where('is_active', true)->first();

        if ($active && $active->back_image_path) {
            return asset('storage/' . $active->back_image_path);
        }

        return null;
    }
}
