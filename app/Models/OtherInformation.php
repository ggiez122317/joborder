<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherInformation extends Model
{
    protected $table = 'other_information';
    protected $fillable = [
        'employee_id',
        'special_skills_hobbies',
        'non_academic_distinctions',
        'memberships',
        'questions',
        'references',
        'government_id_type',
        'government_id_no',
        'government_id_date_place_issued',
        'date_accomplished',
        'signature_name',
        'visibility',
    ];

    protected $casts = [
        'special_skills_hobbies' => 'array',
        'non_academic_distinctions' => 'array',
        'memberships' => 'array',
        'questions' => 'array',
        'references' => 'array',
        'visibility' => 'array',
        'date_accomplished' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
