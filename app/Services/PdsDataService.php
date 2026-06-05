<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdsDataService
{
    public const EDUCATION_LEVELS = [
        'ELEMENTARY',
        'SECONDARY',
        'VOCATIONAL / TRADE COURSE',
        'COLLEGE',
        'GRADUATE STUDIES',
    ];

    public const QUESTION_FIELDS = [
        'related_third_degree',
        'related_fourth_degree_lgu',
        'related_details',
        'administrative_offense',
        'administrative_offense_details',
        'criminally_charged',
        'criminal_date_filed',
        'criminal_case_status',
        'convicted_crime',
        'convicted_crime_details',
        'separated_service',
        'separated_service_details',
        'candidate_last_year',
        'candidate_details',
        'resigned_before_election',
        'resigned_details',
        'immigrant_status',
        'immigrant_country',
        'indigenous_group',
        'indigenous_group_details',
        'person_with_disability',
        'pwd_id_no',
        'solo_parent',
        'solo_parent_id_no',
    ];

    public function defaultData(array $overrides = []): array
    {
        $data = [
            'personal' => array_fill_keys($this->personalFields(), null),
            'family' => array_fill_keys($this->familyFields(), null),
            'children' => array_fill(0, 12, ['name' => null, 'date_of_birth' => null]),
            'education' => collect(self::EDUCATION_LEVELS)->map(fn ($level, $index) => [
                'level' => $level,
                'school_name' => null,
                'degree_course' => null,
                'attendance_from' => null,
                'attendance_to' => null,
                'highest_level_units_earned' => null,
                'year_graduated' => null,
                'honors_received' => null,
                'sort_order' => $index,
            ])->all(),
            'eligibility' => array_fill(0, 7, $this->emptyEligibilityRow()),
            'work_experience' => array_fill(0, 28, $this->emptyWorkRow()),
            'voluntary_work' => array_fill(0, 7, $this->emptyVoluntaryRow()),
            'trainings' => array_fill(0, 21, $this->emptyTrainingRow()),
            'other' => [
                'special_skills_hobbies' => array_fill(0, 7, null),
                'non_academic_distinctions' => array_fill(0, 7, null),
                'memberships' => array_fill(0, 7, null),
                'questions' => array_fill_keys(self::QUESTION_FIELDS, null),
                'references' => array_fill(0, 3, ['name' => null, 'address' => null, 'contact' => null]),
                'government_id_type' => null,
                'government_id_no' => null,
                'government_id_date_place_issued' => null,
                'date_accomplished' => null,
                'signature_name' => null,
                'visibility' => [
                    'show_contact' => false,
                    'show_identifiers' => false,
                ],
            ],
        ];

        $data = array_replace_recursive($data, $overrides);

        if (blank(data_get($data, 'personal.office'))) {
            $fallbackOffice = collect($data['work_experience'] ?? [])
                ->pluck('department_agency_office_company')
                ->map(fn ($office) => trim((string) $office))
                ->first(fn (string $office) => $office !== '');

            if ($fallbackOffice) {
                $data['personal']['office'] = $fallbackOffice;
            }
        }

        return $data;
    }

    public function fromEmployee(Employee $employee): array
    {
        $employee->load([
            'personalInformation',
            'familyBackground',
            'education',
            'eligibility',
            'workExperience',
            'voluntaryWork',
            'trainings',
            'otherInformation',
        ]);

        $other = $employee->otherInformation;
        $personal = $employee->personalInformation;

        $personalData = $personal?->toArray() ?? [];
        // Ensure dates are string for the form
        if (isset($personalData['date_of_birth']) && $personalData['date_of_birth']) {
            $personalData['date_of_birth'] = $personal->date_of_birth->format('Y-m-d');
        }

        return $this->defaultData([
            'personal' => array_merge(
                Arr::only($personalData, $this->personalFields()),
                [
                    'surname' => $employee->surname,
                    'first_name' => $employee->first_name,
                    'middle_name' => $employee->middle_name,
                    'name_extension' => $employee->name_extension,
                    'nickname' => $employee->nickname,
                    'sex_at_birth' => $employee->sex_at_birth,
                    'office' => $employee->office,
                    'job_order' => $employee->job_order,
                    'agency_employee_no' => $employee->employee_code,
                ]
            ),
            'family' => $employee->familyBackground?->only($this->familyFields()) ?? [],
            'children' => $employee->familyBackground?->children ?? [],
            'education' => $employee->education->map->only($this->educationFields())->all(),
            'eligibility' => $employee->eligibility->map->only($this->eligibilityFields())->all(),
            'work_experience' => $employee->workExperience->map->only($this->workFields())->all(),
            'voluntary_work' => $employee->voluntaryWork->map->only($this->voluntaryFields())->all(),
            'trainings' => $employee->trainings->map->only($this->trainingFields())->all(),
            'other' => $other ? [
                'special_skills_hobbies' => $other->special_skills_hobbies ?? [],
                'non_academic_distinctions' => $other->non_academic_distinctions ?? [],
                'memberships' => $other->memberships ?? [],
                'questions' => $other->questions ?? [],
                'references' => $other->references ?? [],
                'government_id_type' => $other->government_id_type,
                'government_id_no' => $other->government_id_no,
                'government_id_date_place_issued' => $other->government_id_date_place_issued,
                'date_accomplished' => optional($other->date_accomplished)->format('Y-m-d'),
                'signature_name' => $other->signature_name,
                'visibility' => $other->visibility ?? [],
            ] : [],
        ]);
    }

    public function validationRules(): array
    {
        return [
            'profile_photo' => ['nullable', 'image', 'max:4096'],
            'personal.surname' => ['required', 'string', 'max:255'],
            'personal.first_name' => ['required', 'string', 'max:255'],
            'personal.middle_name' => ['nullable', 'string', 'max:255'],
            'personal.name_extension' => ['nullable', 'string', 'max:50'],
            'personal.date_of_birth' => ['nullable', 'date'],
            'personal.email_address' => ['nullable', 'email', 'max:255'],
            'personal.sex_at_birth' => ['nullable', 'in:Male,Female'],
            'personal.job_order' => ['nullable', 'string', 'max:255'],
            'personal.office' => ['nullable', 'string', 'max:255'],
            'personal.*' => ['nullable'],
            'family.*' => ['nullable'],
            'children' => ['array'],
            'children.*.name' => ['nullable', 'string', 'max:255'],
            'children.*.date_of_birth' => ['nullable', 'string', 'max:255'],
            'education' => ['array'],
            'eligibility' => ['array'],
            'work_experience' => ['array'],
            'voluntary_work' => ['array'],
            'trainings' => ['array'],
            'other.special_skills_hobbies' => ['array'],
            'other.non_academic_distinctions' => ['array'],
            'other.memberships' => ['array'],
            'other.questions' => ['array'],
            'other.references' => ['array'],
            'other.date_accomplished' => ['nullable', 'date'],
        ];
    }

    public function officeOptions(?string $selected = null): array
    {
        // Fallback or legacy static offices
        $staticOffices = config('offices', []);
        
        // Fetch dynamic offices from the database
        $dynamicOffices = [];
        if (Schema::hasTable('offices')) {
            $dynamicOffices = DB::table('offices')->pluck('name')->toArray();
        }

        $allOffices = array_merge($staticOffices, $dynamicOffices);

        $offices = collect($allOffices)
            ->map(fn ($office) => trim((string) $office))
            ->filter()
            ->when(filled($selected), fn (Collection $items) => $items->push(trim((string) $selected)))
            ->unique(fn (string $office) => mb_strtolower($office))
            ->sort(function (string $first, string $second) {
                return strcasecmp($first, $second);
            })
            ->values();

        return $offices->all();
    }

    public function officeCounts(): array
    {
        $counts = Employee::query()
            ->select('office', DB::raw('count(*) as total'))
            ->whereNotNull('office')
            ->where('office', '<>', '')
            ->groupBy('office')
            ->pluck('total', 'office');

        return collect($this->officeOptions())
            ->map(fn (string $office) => [
                'name' => $office,
                'count' => (int) ($counts[$office] ?? 0),
            ])
            ->all();
    }

    public function save(array $data, ?User $user = null, ?string $sourceFile = null, ?string $profilePhotoPath = null, ?string $eSignaturePath = null): Employee
    {
        $data = $this->defaultData($data);

        return DB::transaction(function () use ($data, $user, $sourceFile, $profilePhotoPath, $eSignaturePath) {
            $personal = Arr::only($data['personal'], $this->personalFields());
            $personalRecord = Arr::only($data['personal'], $this->personalPersistFields());
            $name = $this->fullName($personal);
            $latestWork = collect($data['work_experience'])
                ->first(fn ($row) => filled($row['position_title'] ?? null) || filled($row['department_agency_office_company'] ?? null));

            $employee = Employee::create([
                'employee_code' => $personal['agency_employee_no'] ?? null,
                'full_name' => $name,
                'surname' => $personal['surname'] ?? null,
                'first_name' => $personal['first_name'] ?? null,
                'middle_name' => $personal['middle_name'] ?? null,
                'name_extension' => $personal['name_extension'] ?? null,
                'nickname' => $personal['nickname'] ?? null,
                'job_order' => $personal['job_order'] ?? null,
                'position_title' => $latestWork['position_title'] ?? null,
                'office' => $personal['office'] ?? $latestWork['department_agency_office_company'] ?? null,
                'sex_at_birth' => $personal['sex_at_birth'] ?? null,
                'source_file' => $sourceFile,
                'profile_photo_path' => $profilePhotoPath,
                'e_signature_path' => $eSignaturePath,
                'created_by' => $user?->id,
                'user_id' => $user?->isUser() ? $user->id : null,
            ]);

            $employee->personalInformation()->create($personalRecord);

            $employee->familyBackground()->create(array_merge(
                Arr::only($data['family'], $this->familyFields()),
                ['children' => $this->filterNestedRows($data['children'] ?? [], ['name', 'date_of_birth'])]
            ));

            $employee->education()->createMany($this->rowsWithOrder($data['education'], $this->educationFields(), true));
            $employee->eligibility()->createMany($this->rowsWithOrder($data['eligibility'], $this->eligibilityFields()));
            $employee->workExperience()->createMany($this->rowsWithOrder($data['work_experience'], $this->workFields()));
            $employee->voluntaryWork()->createMany($this->rowsWithOrder($data['voluntary_work'], $this->voluntaryFields()));
            $employee->trainings()->createMany($this->rowsWithOrder($data['trainings'], $this->trainingFields()));

            $other = $data['other'];
            $employee->otherInformation()->create([
                'special_skills_hobbies' => $this->filterFlatRows($other['special_skills_hobbies'] ?? []),
                'non_academic_distinctions' => $this->filterFlatRows($other['non_academic_distinctions'] ?? []),
                'memberships' => $this->filterFlatRows($other['memberships'] ?? []),
                'questions' => Arr::only($other['questions'] ?? [], self::QUESTION_FIELDS),
                'references' => $this->filterNestedRows($other['references'] ?? [], ['name', 'address', 'contact']),
                'government_id_type' => $other['government_id_type'] ?? null,
                'government_id_no' => $other['government_id_no'] ?? null,
                'government_id_date_place_issued' => $other['government_id_date_place_issued'] ?? null,
                'date_accomplished' => $other['date_accomplished'] ?? null,
                'signature_name' => $other['signature_name'] ?? null,
                'visibility' => $other['visibility'] ?? [],
            ]);

            $employee->update(['qr_code_path' => $this->generateQr($employee)]);

            return $employee->fresh();
        });
    }

    public function update(Employee $employee, array $data, ?string $sourceFile = null, ?string $profilePhotoPath = null, ?User $user = null, ?string $eSignaturePath = null): Employee
    {
        $data = $this->defaultData($data);

        return DB::transaction(function () use ($employee, $data, $sourceFile, $profilePhotoPath, $user, $eSignaturePath) {
            $employee->load([
                'personalInformation',
                'familyBackground',
                'education',
                'eligibility',
                'workExperience',
                'voluntaryWork',
                'trainings',
                'otherInformation',
            ]);

            $personal = Arr::only($data['personal'], $this->personalFields());
            $personalRecord = Arr::only($data['personal'], $this->personalPersistFields());
            $name = $this->fullName($personal);
            $latestWork = collect($data['work_experience'])
                ->first(fn ($row) => filled($row['position_title'] ?? null) || filled($row['department_agency_office_company'] ?? null));

            $employee->update([
                'employee_code' => $personal['agency_employee_no'] ?? null,
                'full_name' => $name,
                'surname' => $personal['surname'] ?? null,
                'first_name' => $personal['first_name'] ?? null,
                'middle_name' => $personal['middle_name'] ?? null,
                'name_extension' => $personal['name_extension'] ?? null,
                'nickname' => $personal['nickname'] ?? null,
                'job_order' => $personal['job_order'] ?? null,
                'position_title' => $latestWork['position_title'] ?? null,
                'office' => $personal['office'] ?? $latestWork['department_agency_office_company'] ?? null,
                'sex_at_birth' => $personal['sex_at_birth'] ?? null,
                'source_file' => $sourceFile ?: $employee->source_file,
                'profile_photo_path' => $profilePhotoPath ?: $employee->profile_photo_path,
                'e_signature_path' => $eSignaturePath ?: $employee->e_signature_path,
                'user_id' => $employee->user_id ?: ($user?->isUser() ? $user->id : null),
            ]);

            $employee->personalInformation()->updateOrCreate(
                ['employee_id' => $employee->id],
                $personalRecord
            );

            $employee->familyBackground()->updateOrCreate(
                ['employee_id' => $employee->id],
                array_merge(
                    Arr::only($data['family'], $this->familyFields()),
                    ['children' => $this->filterNestedRows($data['children'] ?? [], ['name', 'date_of_birth'])]
                )
            );

            $this->replaceOrderedRows($employee->education(), $data['education'], $this->educationFields(), true);
            $this->replaceOrderedRows($employee->eligibility(), $data['eligibility'], $this->eligibilityFields());
            $this->replaceOrderedRows($employee->workExperience(), $data['work_experience'], $this->workFields());
            $this->replaceOrderedRows($employee->voluntaryWork(), $data['voluntary_work'], $this->voluntaryFields());
            $this->replaceOrderedRows($employee->trainings(), $data['trainings'], $this->trainingFields());

            $other = $data['other'];
            $employee->otherInformation()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'special_skills_hobbies' => $this->filterFlatRows($other['special_skills_hobbies'] ?? []),
                    'non_academic_distinctions' => $this->filterFlatRows($other['non_academic_distinctions'] ?? []),
                    'memberships' => $this->filterFlatRows($other['memberships'] ?? []),
                    'questions' => Arr::only($other['questions'] ?? [], self::QUESTION_FIELDS),
                    'references' => $this->filterNestedRows($other['references'] ?? [], ['name', 'address', 'contact']),
                    'government_id_type' => $other['government_id_type'] ?? null,
                    'government_id_no' => $other['government_id_no'] ?? null,
                    'government_id_date_place_issued' => $other['government_id_date_place_issued'] ?? null,
                    'date_accomplished' => $other['date_accomplished'] ?? null,
                    'signature_name' => $other['signature_name'] ?? null,
                    'visibility' => $other['visibility'] ?? [],
                ]
            );

            $employee->update(['qr_code_path' => $this->generateQr($employee)]);

            return $employee->fresh();
        });
    }

    public function personalFields(): array
    {
        return [
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
    }

    public function personalPersistFields(): array
    {
        return collect($this->personalFields())
            ->reject(fn (string $field) => $field === 'office' && !Schema::hasColumn('personal_information', 'office'))
            ->values()
            ->all();
    }

    public function familyFields(): array
    {
        return [
            'spouse_surname',
            'spouse_first_name',
            'spouse_middle_name',
            'spouse_name_extension',
            'spouse_occupation',
            'spouse_employer_business_name',
            'spouse_business_address',
            'spouse_telephone_no',
            'father_surname',
            'father_first_name',
            'father_middle_name',
            'father_name_extension',
            'mother_maiden_name',
            'mother_surname',
            'mother_first_name',
            'mother_middle_name',
        ];
    }

    public function educationFields(): array
    {
        return [
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
    }

    public function eligibilityFields(): array
    {
        return [
            'career_service',
            'rating',
            'examination_date',
            'examination_place',
            'license_number',
            'license_valid_until',
            'sort_order',
        ];
    }

    public function workFields(): array
    {
        return [
            'date_from',
            'date_to',
            'position_title',
            'department_agency_office_company',
            'status_of_appointment',
            'government_service',
            'sort_order',
        ];
    }

    public function voluntaryFields(): array
    {
        return [
            'organization_name_address',
            'date_from',
            'date_to',
            'number_of_hours',
            'position_nature_of_work',
            'sort_order',
        ];
    }

    public function trainingFields(): array
    {
        return [
            'title',
            'date_from',
            'date_to',
            'number_of_hours',
            'type_of_ld',
            'conducted_sponsored_by',
            'sort_order',
        ];
    }

    private function fullName(array $personal): string
    {
        $parts = array_filter([
            $personal['first_name'] ?? null,
            $personal['middle_name'] ?? null,
            $personal['surname'] ?? null,
            $personal['name_extension'] ?? null,
        ], fn ($part) => filled($part));

        return trim(implode(' ', $parts));
    }

    public function generateQr(Employee $employee): string
    {
        $path = "qr/pds-{$employee->id}.svg";
        $url = rtrim(config('app.url'), '/') . route('profile.public', $employee, false);
        $svg = QrCode::format('svg')
            ->size(180)
            ->margin(1)
            ->backgroundColor(255, 255, 255, 0)
            ->generate($url);

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    private function rowsWithOrder(array $rows, array $fields, bool $keepAll = false): array
    {
        $prepared = [];

        foreach (array_values($rows) as $index => $row) {
            $row = Arr::only($row, $fields);
            $row['sort_order'] = $index;

            if ($keepAll || collect(Arr::except($row, ['sort_order', 'level']))->contains(fn ($value) => filled($value))) {
                $prepared[] = $row;
            }
        }

        return $prepared;
    }

    private function replaceOrderedRows($relation, array $rows, array $fields, bool $keepAll = false): void
    {
        $existingIds = $relation->pluck('id')->toArray();
        $keepIds = [];
        $dataToInsert = [];

        foreach (array_values($rows) as $index => $row) {
            $rowData = Arr::only($row, $fields);
            $rowData['sort_order'] = $index;

            $hasContent = $keepAll || collect(Arr::except($rowData, ['sort_order', 'level']))
                ->contains(fn ($value) => filled($value));

            if ($hasContent) {
                if (!empty($row['id']) && in_array($row['id'], $existingIds)) {
                    $relation->where('id', $row['id'])->update($rowData);
                    $keepIds[] = $row['id'];
                } else {
                    $dataToInsert[] = $rowData;
                }
            }
        }

        $relation->whereNotIn('id', $keepIds)->delete();

        if (!empty($dataToInsert)) {
            $relation->createMany($dataToInsert);
        }
    }

    private function filterFlatRows(array $rows): array
    {
        return collect($rows)->filter(fn ($value) => filled($value))->values()->all();
    }

    private function filterNestedRows(array $rows, array $fields): array
    {
        return collect($rows)
            ->map(fn ($row) => Arr::only($row, $fields))
            ->filter(fn ($row) => collect($row)->contains(fn ($value) => filled($value)))
            ->values()
            ->all();
    }

    private function emptyEligibilityRow(): array
    {
        return array_fill_keys($this->eligibilityFields(), null);
    }

    private function emptyWorkRow(): array
    {
        return array_fill_keys($this->workFields(), null);
    }

    private function emptyVoluntaryRow(): array
    {
        return array_fill_keys($this->voluntaryFields(), null);
    }

    private function emptyTrainingRow(): array
    {
        return array_fill_keys($this->trainingFields(), null);
    }
}
