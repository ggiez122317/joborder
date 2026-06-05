<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    protected $table = 'education';
    protected $fillable = [
        'employee_id',
        'level',
        'school_name',
        'degree_course',
        'attendance_from',
        'attendance_to',
        'highest_level_units_earned',
        'year_graduated',
        'honors_received',
        'sort_order',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
