<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    protected $table = 'trainings';
    protected $fillable = [
        'employee_id',
        'title',
        'date_from',
        'date_to',
        'number_of_hours',
        'type_of_ld',
        'conducted_sponsored_by',
        'sort_order',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
