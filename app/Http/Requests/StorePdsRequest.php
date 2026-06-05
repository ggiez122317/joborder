<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_photo' => ['nullable', 'image', 'max:4096'],
            'e_signature' => ['nullable', 'image', 'max:4096'],
            'drawn_signature' => ['nullable', 'string'],
            'personal.surname' => ['required', 'string', 'max:255'],
            'personal.first_name' => ['required', 'string', 'max:255'],
            'personal.middle_name' => ['nullable', 'string', 'max:255'],
            'personal.name_extension' => ['nullable', 'string', 'max:50'],
            'personal.nickname' => ['nullable', 'string', 'max:255'],
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
            'import_history_id' => ['nullable', 'integer'],
            'source_file' => ['nullable', 'string'],
        ];
    }
}
