<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eligibility extends Model
{
    protected $table = 'eligibility';
    protected $fillable = [
        'employee_id',
        'career_service',
        'rating',
        'examination_date',
        'examination_place',
        'license_number',
        'license_valid_until',
        'sort_order',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
