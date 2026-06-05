<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyBackground extends Model
{
    protected $table = 'family_background';
    protected $fillable = [
        'employee_id',
        'spouse_surname',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_name_extension',
        'spouse_occupation',
        'spouse_employer_business_name',
        'spouse_business_address',
        'spouse_telephone_no',
        'children',
        'father_surname',
        'father_first_name',
        'father_middle_name',
        'father_name_extension',
        'mother_maiden_name',
        'mother_surname',
        'mother_first_name',
        'mother_middle_name',
    ];

    protected $casts = [
        'children' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
