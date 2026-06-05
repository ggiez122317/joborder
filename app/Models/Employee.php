<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\Auditable;

class Employee extends Model
{
    use Auditable;

    protected $fillable = [
        'employee_code',
        'full_name',
        'surname',
        'first_name',
        'middle_name',
        'name_extension',
        'nickname',
        'job_order',
        'position_title',
        'office',
        'is_active',
        'sex_at_birth',
        'source_file',
        'qr_code_path',
        'profile_photo_path',
        'e_signature_path',
        'created_by',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personalInformation(): HasOne
    {
        return $this->hasOne(PersonalInformation::class);
    }

    public function familyBackground(): HasOne
    {
        return $this->hasOne(FamilyBackground::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('sort_order');
    }

    public function eligibility(): HasMany
    {
        return $this->hasMany(Eligibility::class)->orderBy('sort_order');
    }

    public function workExperience(): HasMany
    {
        return $this->hasMany(WorkExperience::class)->orderBy('sort_order');
    }

    public function latestWorkExperience(): HasOne
    {
        return $this->hasOne(WorkExperience::class)->latestOfMany();
    }

    public function voluntaryWork(): HasMany
    {
        return $this->hasMany(VoluntaryWork::class)->orderBy('sort_order');
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class)->orderBy('sort_order');
    }

    public function otherInformation(): HasOne
    {
        return $this->hasOne(OtherInformation::class);
    }

    public function importHistories(): HasMany
    {
        return $this->hasMany(ImportHistory::class);
    }

    public function changeLogs(): HasMany
    {
        return $this->hasMany(EmployeeChangeLog::class)->latest();
    }

    public function getEmploymentTypeAttribute(): string
    {
        $status = strtolower((string) optional($this->latestWorkExperience)->status_of_appointment);
        $jobOrder = strtolower(trim((string) $this->job_order));
        $hasJobOrder = $jobOrder !== '' && !in_array($jobOrder, ['n/a', 'na', 'none', 'n / a', 'no']);

        if ($hasJobOrder || str_contains($status, 'job order')) {
            return 'Job Order';
        }

        if (str_contains($status, 'co-terminus') || str_contains($status, 'cotermin')) {
            return 'Co-terminus';
        }

        if (str_contains($status, 'regular') || str_contains($status, 'permanent')) {
            return 'Regular';
        }

        if (str_contains($status, 'plantilla')) {
            return 'Plantilla';
        }

        return 'N/A';
    }

    public function scopeIncomplete($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('office')
                ->orWhere('office', '')
                ->orWhereNull('profile_photo_path')
                ->orWhere('profile_photo_path', '')
                ->orWhereDoesntHave('workExperience')
                ->orWhereHas('personalInformation', function ($inner) {
                    $inner->where(function ($contact) {
                        $contact->whereNull('mobile_no')->orWhere('mobile_no', '');
                    })->where(function ($contact) {
                        $contact->whereNull('email_address')->orWhere('email_address', '');
                    });
                });
        });
    }
}
