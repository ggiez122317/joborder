<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalInformation extends Model
{
    protected $table = 'personal_information';
    protected $fillable = [
        'employee_id',
        'surname',
        'first_name',
        'middle_name',
        'name_extension',
        'nickname',
        'date_of_birth',
        'place_of_birth',
        'sex_at_birth',
        'civil_status',
        'citizenship',
        'citizenship_basis',
        'dual_citizenship_country',
        'height_m',
        'weight_kg',
        'blood_type',
        'umid_id_no',
        'pagibig_id_no',
        'philhealth_no',
        'philsys_no',
        'tin_no',
        'agency_employee_no',
        'telephone_no',
        'mobile_no',
        'email_address',
        'job_order',
        'office',
        'residential_house_no',
        'residential_street',
        'residential_subdivision',
        'residential_barangay',
        'residential_city',
        'residential_province',
        'residential_zip_code',
        'permanent_house_no',
        'permanent_street',
        'permanent_subdivision',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_zip_code',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
