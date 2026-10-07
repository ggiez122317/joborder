<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\RegistrationLink;
use App\Services\PdsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PublicRegistrationController extends Controller
{
    public function __construct(private readonly PdsDataService $pds)
    {
    }

    public function show(RegistrationLink $link): View|Response
    {
        if (! $link->isValid()) {
            return $this->expired();
        }

        return view('register.form', [
            'link' => $link,
            'offices' => $this->pds->officeOptions(),
        ]);
    }

    public function options(Request $request, RegistrationLink $link): JsonResponse
    {
        abort_unless($link->isValid(), 410);

        $q = trim((string) $request->query('q', ''));

        return response()->json([
            'offices' => $this->filterStartsWith($this->pds->officeOptions(), $q, 30),
            'positions' => $this->positionSuggestions($q),
        ]);
    }

    public function store(Request $request, RegistrationLink $link): RedirectResponse
    {
        if (! $link->isValid()) {
            return redirect()->route('register.form', $link);
        }

        $validated = $request->validate([
            'surname' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'name_extension' => ['nullable', 'string', 'max:20'],
            'nickname' => ['nullable', 'string', 'max:50'],
            'office' => ['required', 'string', Rule::in($this->pds->officeOptions())],
            'job_order' => ['nullable', 'string', 'max:100'],
            'agency_employee_no' => ['nullable', 'string', 'max:100'],
            'sex_at_birth' => ['required', 'in:Male,Female'],
            'mobile_no' => ['required', 'string', 'max:30'],
            'email_address' => ['nullable', 'email', 'max:255'],
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $duplicate = Employee::query()
            ->whereRaw('LOWER(surname) = ?', [mb_strtolower(trim($validated['surname']))])
            ->whereRaw('LOWER(first_name) = ?', [mb_strtolower(trim($validated['first_name']))])
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['surname' => 'A record with this name already exists. Please contact HR instead of registering again.'])
                ->withInput();
        }

        $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');

        try {
            $office = trim($validated['office']);

            $data = $this->pds->defaultData([
            'personal' => [
                'surname' => trim($validated['surname']),
                'first_name' => trim($validated['first_name']),
                'middle_name' => ! empty($validated['middle_name'] ?? null) ? trim($validated['middle_name']) : null,
                'name_extension' => ! empty($validated['name_extension'] ?? null) ? trim($validated['name_extension']) : null,
                'nickname' => ! empty($validated['nickname'] ?? null) ? trim($validated['nickname']) : null,
                'job_order' => ! empty($validated['job_order'] ?? null) ? trim($validated['job_order']) : null,
                'agency_employee_no' => ! empty($validated['agency_employee_no'] ?? null) ? trim($validated['agency_employee_no']) : null,
                'office' => $office,
                'sex_at_birth' => $validated['sex_at_birth'],
                'mobile_no' => trim($validated['mobile_no']),
                'email_address' => ! empty($validated['email_address'] ?? null) ? trim($validated['email_address']) : null,
            ],
            'work_experience' => [
                ['department_agency_office_company' => $office],
            ],
        ]);

        $employee = $this->pds->save($data, null, 'public-registration', $photoPath);
        $employee->update(['needs_review' => true]);
        $link->increment('uses');

        return redirect()
            ->route('register.success', $link)
            ->with('registered_name', trim($validated['first_name']) . ' ' . trim($validated['surname']));
        } catch (\Throwable $e) {
            report($e);

            if (isset($photoPath) && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
            }

            return back()
                ->withErrors(['system' => 'Something went wrong while saving your registration. Please try again or contact HR.'])
                ->withInput();
        }
    }

    public function success(RegistrationLink $link): View
    {
        return view('register.success', [
            'name' => session('registered_name'),
        ]);
    }

    private function expired(): Response
    {
        return response()->view('register.expired', [], 410);
    }

    private function filterStartsWith(array $options, string $q, int $limit): array
    {
        if ($q === '') {
            return array_slice($options, 0, $limit);
        }

        $lower = mb_strtolower($q);

        return collect($options)
            ->filter(fn (string $o) => str_contains(mb_strtolower($o), $lower))
            ->take($limit)
            ->values()
            ->all();
    }

    private function positionSuggestions(string $q): array
    {
        $query = Employee::query()
            ->whereNotNull('position_title')
            ->where('position_title', '<>', '');

        if (trim($q) !== '') {
            $query->where('position_title', 'like', '%' . trim($q) . '%');
        }

        return $query
            ->distinct()
            ->orderBy('position_title')
            ->limit(20)
            ->pluck('position_title')
            ->all();
    }
}
