<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkExperience extends Model
{
    protected $table = 'work_experience';
    protected $fillable = [
        'employee_id',
        'date_from',
        'date_to',
        'position_title',
        'department_agency_office_company',
        'status_of_appointment',
        'government_service',
        'sort_order',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
