<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoluntaryWork extends Model
{
    protected $table = 'voluntary_work';
    protected $fillable = [
        'employee_id',
        'organization_name_address',
        'date_from',
        'date_to',
        'number_of_hours',
        'position_nature_of_work',
        'sort_order',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
