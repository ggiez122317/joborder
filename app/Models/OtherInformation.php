<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherInformation extends Model
{
    protected $table = 'other_information';
    protected $guarded = [];

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
