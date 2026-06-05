<?php

namespace App\Http\Controllers;

use App\Exports\AnalyticsReportExport;
use App\Models\Employee;
use App\Models\ImportHistory;
use App\Services\AuditLogService;
use App\Services\PdsAdminService;
use App\Services\PdsDataService;
use App\Services\PdsFileParser;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StorePdsRequest;
use App\Http\Requests\UpdatePdsRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly PdsDataService $pds,
        private readonly PdsFileParser $parser,
        private readonly PdsAdminService $admin,
        private readonly AuditLogService $audit
    ) {
    }

    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q'));
        $office = trim((string) $request->query('office'));
        $scope = trim((string) $request->query('scope'));

        $employees = Employee::query()
            ->with(['personalInformation', 'workExperience', 'latestWorkExperience', 'user'])
            ->when($office !== '', function ($builder) use ($office) {
                $builder->where('office', $office);
            })
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($where) use ($query) {
                    $where->where('full_name', 'like', "%{$query}%")
                        ->orWhere('job_order', 'like', "%{$query}%")
                        ->orWhere('position_title', 'like', "%{$query}%")
                        ->orWhere('office', 'like', "%{$query}%");
                });
            })
            ->when($scope === 'incomplete', function (Builder $builder) {
                $builder->incomplete();
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $employees->getCollection()->transform(function (Employee $employee) {
            $employee->incomplete_fields = $this->admin->incompleteFields($employee);
            $employee->record_status = $this->admin->isComplete($employee) ? 'complete' : 'incomplete';

            return $employee;
        });

        return view('pds.records', [
            'employees' => $employees,
            'query' => $query,
            'office' => $office,
            'scope' => $scope,
        ]);
    }

    public function reportAnalytics(Request $request): View
    {
        $filters = $this->analyticsFilters($request);
        $query = $this->filteredAnalyticsQuery($filters);
        $employees = $query->get();

        return view('pds.report-analytics', [
            'filters' => $filters,
            'officeOptions' => $this->pds->officeOptions($filters['office']),
            'analytics' => $this->analyticsPayload($employees),
            'employees' => $employees->take(15),
            'filteredCount' => $employees->count(),
        ]);
    }

    public function exportAnalyticsExcel(Request $request)
    {
        $filters = $this->analyticsFilters($request);
        $employees = $this->filteredAnalyticsQuery($filters)->get();

        return Excel::download(
            new AnalyticsReportExport($employees, $filters),
            'pds-report-analytics-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }

    public function create(): View
    {
        return view('pds.form', [
            'data' => old() ?: $this->pds->defaultData(),
            'sourceFile' => null,
            'employee' => null,
            'profilePhotoUrl' => null,
            'mode' => 'create',
            'officeOptions' => $this->pds->officeOptions(),
        ]);
    }

    public function store(StorePdsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $importHistoryId = $request->integer('import_history_id') ?: null;
        $profilePhotoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('profile-photos', 'public')
            : null;

        $eSignaturePath = null;
        if ($request->hasFile('e_signature')) {
            $eSignaturePath = $request->file('e_signature')->store('e-signatures', 'public');
        } elseif ($request->filled('drawn_signature')) {
            $base64Data = $request->input('drawn_signature');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['png', 'jpg', 'jpeg', 'webp'])) {
                    $decodedData = base64_decode($base64Data);
                    if ($decodedData !== false) {
                        $fileName = 'e-signatures/' . uniqid() . '.' . $type;
                        Storage::disk('public')->put($fileName, $decodedData);
                        $eSignaturePath = $fileName;
                    }
                }
            }
        }

        $employee = $this->pds->save(
            $validated,
            $request->user(),
            $request->input('source_file'),
            $profilePhotoPath,
            $eSignaturePath
        );
        $this->admin->createChangeLog(
            $employee,
            $request->user(),
            'created',
            [],
            $this->pds->fromEmployee($employee)
        );
        $this->completeImportHistory($importHistoryId, $employee);

        return redirect()
            ->route('profile.show', $employee)
            ->with('status', 'Personal Data Sheet saved and QR code generated.');
    }

    public function edit(Request $request, Employee $employee): View
    {
        $compareEmployee = null;
        $duplicateContext = null;

        if ($request->filled('compare_with')) {
            $compareEmployee = Employee::query()
                ->with('personalInformation')
                ->find($request->integer('compare_with'));
        }

        if ($compareEmployee && $compareEmployee->isNot($employee)) {
            $duplicateContext = [
                'reason' => (string) $request->query('duplicate_reason', 'Possible duplicate'),
                'match_key' => (string) $request->query('match_key', ''),
                'fields' => $this->duplicateHighlightFields((string) $request->query('duplicate_reason', '')),
                'compare_employee' => $compareEmployee,
            ];
        }

        return view('pds.form', [
            'data' => old() ?: $this->pds->fromEmployee($employee),
            'sourceFile' => $employee->source_file,
            'employee' => $employee,
            'profilePhotoUrl' => route('profile.photo', $employee),
            'mode' => 'edit',
            'officeOptions' => $this->pds->officeOptions($employee->office),
            'duplicateContext' => $duplicateContext,
            'portalMode' => 'admin',
        ]);
    }

    public function update(UpdatePdsRequest $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validated();
        $importHistoryId = $request->integer('import_history_id') ?: null;
        $before = $this->pds->fromEmployee($employee);

        $profilePhotoPath = $employee->profile_photo_path;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');

            if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }
        }

        $eSignaturePath = $employee->e_signature_path;
        if ($request->hasFile('e_signature')) {
            $eSignaturePath = $request->file('e_signature')->store('e-signatures', 'public');

            if ($employee->e_signature_path && Storage::disk('public')->exists($employee->e_signature_path)) {
                Storage::disk('public')->delete($employee->e_signature_path);
            }
        } elseif ($request->filled('drawn_signature')) {
            $base64Data = $request->input('drawn_signature');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['png', 'jpg', 'jpeg', 'webp'])) {
                    $decodedData = base64_decode($base64Data);
                    if ($decodedData !== false) {
                        $fileName = 'e-signatures/' . uniqid() . '.' . $type;
                        Storage::disk('public')->put($fileName, $decodedData);
                        $eSignaturePath = $fileName;

                        if ($employee->e_signature_path && Storage::disk('public')->exists($employee->e_signature_path)) {
                            Storage::disk('public')->delete($employee->e_signature_path);
                        }
                    }
                }
            }
        }

        $employee = $this->pds->update(
            $employee,
            $validated,
            $request->input('source_file'),
            $profilePhotoPath,
            $request->user(),
            $eSignaturePath
        );
        $this->admin->createChangeLog(
            $employee,
            $request->user(),
            'updated',
            $before,
            $this->pds->fromEmployee($employee)
        );
        $this->completeImportHistory($importHistoryId, $employee);

        return redirect()
            ->route('profile.show', $employee)
            ->with('status', 'Personal Data Sheet updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $targetId = $employee->id;

        if ($employee->qr_code_path && Storage::disk('public')->exists($employee->qr_code_path)) {
            Storage::disk('public')->delete($employee->qr_code_path);
        }

        if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
            Storage::disk('public')->delete($employee->profile_photo_path);
        }

        if ($employee->e_signature_path && Storage::disk('public')->exists($employee->e_signature_path)) {
            Storage::disk('public')->delete($employee->e_signature_path);
        }

        $employee->delete();

        return redirect()
            ->route('records.index')
            ->with('status', 'PDS record deleted successfully.');
    }

    public function toggleActive(Employee $employee): RedirectResponse
    {
        $employee->update(['is_active' => !$employee->is_active]);

        $label = $employee->is_active ? 'active' : 'inactive';

        return redirect()
            ->back()
            ->with('status', "Record for {$employee->full_name} is now {$label}.");
    }

    public function regenerateQr(Employee $employee): RedirectResponse
    {
        $employee->update(['qr_code_path' => $this->pds->generateQr($employee)]);

        return redirect()
            ->back()
            ->with('status', 'QR code regenerated with transparency.');
    }

    public function upload(): View
    {
        return view('pds.upload');
    }

    public function parseUpload(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'pds_file' => ['required', 'file', 'mimes:xlsx,pdf', 'max:10240'],
        ]);

        $file = $validated['pds_file'];
        $sourceFile = $file->store('uploads', 'public');

        try {
            $data = $this->parser->parse($file);
            $importHistory = $this->admin->createImportHistory([
                'original_filename' => $file->getClientOriginalName(),
                'stored_path' => $sourceFile,
                'file_type' => strtolower((string) $file->getClientOriginalExtension()),
                'status' => 'reviewed',
                'total_rows' => 1,
                'success_rows' => 1,
                'failed_rows' => 0,
                'notes' => 'Upload parsed and mapped for admin review.',
                'created_by' => $request->user()?->id,
            ]);
        } catch (Throwable $exception) {
            $errorReportPath = $this->admin->storeErrorReport($file->getClientOriginalName(), $exception->getMessage());

            $failedImport = $this->admin->createImportHistory([
                'original_filename' => $file->getClientOriginalName(),
                'stored_path' => $sourceFile,
                'file_type' => strtolower((string) $file->getClientOriginalExtension()),
                'status' => 'failed',
                'total_rows' => 1,
                'success_rows' => 0,
                'failed_rows' => 1,
                'notes' => 'Upload parsing failed.',
                'error_report_path' => $errorReportPath,
                'created_by' => $request->user()?->id,
            ]);

            return back()->withErrors([
                'pds_file' => 'The uploaded file could not be parsed. Check the generated error report in Import History.',
            ]);
        }

        return view('pds.form', [
            'data' => $data,
            'sourceFile' => $sourceFile,
            'employee' => null,
            'profilePhotoUrl' => null,
            'mode' => 'upload',
            'uploadNotice' => 'Fields were mapped from the uploaded file. Review and edit before saving.',
            'officeOptions' => $this->pds->officeOptions(data_get($data, 'personal.office')),
            'importHistoryId' => $importHistory->id,
        ]);
    }

    public function show(Employee $employee): View
    {
        return view('pds.profile', $this->profilePayload($employee));
    }

    public function viewIdCard(Employee $employee)
    {
        $employee->load(['personalInformation', 'familyBackground', 'otherInformation']);
        return view('pds.id-card', compact('employee'));
    }

    public function batchViewIdCards(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No records selected.');
        }

        $employees = Employee::whereIn('id', $ids)
            ->with(['personalInformation', 'familyBackground', 'otherInformation'])
            ->get();

        return view('pds.batch-id-cards', compact('employees'));
    }

    public function viewValidIdCard(Employee $employee)
    {
        $employee->load(['personalInformation', 'familyBackground', 'otherInformation']);
        return view('pds.valid-id', compact('employee'));
    }

    public function batchViewValidIdCards(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No records selected.');
        }

        $employees = Employee::whereIn('id', $ids)
            ->with(['personalInformation', 'familyBackground', 'otherInformation'])
            ->get();

        return view('pds.batch-valid-ids', compact('employees'));
    }

    public function publicShow(Employee $employee): View
    {
        abort_unless($employee->is_active, 404);

        return view('pds.profile', $this->profilePayload($employee, true));
    }

    public function publicPrint(Employee $employee): View
    {
        abort_unless($employee->is_active, 404);

        return view('pds.profile', array_merge($this->profilePayload($employee, true), [
            'printMode' => true,
        ]));
    }

    public function photo(Employee $employee): Response
    {
        if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
            return Storage::disk('public')->response($employee->profile_photo_path, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return response()->file(public_path('assets/profile-placeholder.svg'));
    }

    public function publicPhoto(Employee $employee): Response
    {
        return $this->photo($employee);
    }

    public function signature(Employee $employee): Response
    {
        if ($employee->e_signature_path && Storage::disk('public')->exists($employee->e_signature_path)) {
            return Storage::disk('public')->response($employee->e_signature_path, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return response(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='), 200)
            ->header('Content-Type', 'image/png');
    }

    public function publicSignature(Employee $employee): Response
    {
        return $this->signature($employee);
    }

    public function print(Employee $employee): View
    {
        return view('pds.profile', array_merge($this->profilePayload($employee), [
            'printMode' => true,
        ]));
    }

    public function exportPdf(Employee $employee)
    {
        $payload = array_merge($this->profilePayload($employee), [
            'printMode' => true,
            'pdfMode' => true,
        ]);

        return Pdf::loadView('pds.profile', $payload)
            ->setPaper('a4', 'portrait')
            ->download('PDS-' . str_replace(' ', '-', $employee->full_name) . '.pdf');
    }

    private function profilePayload(Employee $employee, bool $publicMode = false): array
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
            'latestWorkExperience',
            'changeLogs.user',
        ]);

        $qrSvg = null;
        if ($employee->qr_code_path && Storage::disk('public')->exists($employee->qr_code_path)) {
            $qrSvg = Storage::disk('public')->get($employee->qr_code_path);
        }

        return [
            'employee' => $employee,
            'data' => $this->pds->fromEmployee($employee),
            'qrSvg' => $qrSvg,
            'profilePhotoUrl' => $publicMode
                ? route('profile.public.photo', ['employee' => $employee, 'v' => optional($employee->updated_at)?->timestamp])
                : route('profile.photo', ['employee' => $employee, 'v' => optional($employee->updated_at)?->timestamp]),
            'profileLink' => route('profile.public', $employee),
            'profileStatus' => $employee->is_active ? 'Active' : 'Inactive',
            'employmentType' => $employee->employment_type,
            'changeLogs' => $employee->changeLogs,
            'incompleteFields' => $this->admin->incompleteFields($employee),
            'printMode' => false,
            'pdfMode' => false,
            'publicMode' => $publicMode,
        ];
    }

    private function completeImportHistory(?int $importHistoryId, Employee $employee): void
    {
        if (! $importHistoryId) {
            return;
        }

        ImportHistory::query()
            ->whereKey($importHistoryId)
            ->update([
                'status' => 'completed',
                'employee_id' => $employee->id,
                'success_rows' => 1,
                'failed_rows' => 0,
                'notes' => 'Imported and saved as employee record.',
            ]);
    }

    private function duplicateHighlightFields(string $reason): array
    {
        if (str_contains(strtolower($reason), 'employee code')) {
            return [
                'personal.agency_employee_no',
            ];
        }

        return [
            'personal.surname',
            'personal.first_name',
            'personal.middle_name',
            'personal.name_extension',
            'personal.date_of_birth',
        ];
    }

    private function analyticsFilters(Request $request): array
    {
        return [
            'scope' => trim((string) $request->query('scope')),
            'office' => trim((string) $request->query('office')),
            'sex' => trim((string) $request->query('sex')),
            'status' => trim((string) $request->query('status')),
            'submitted_by' => trim((string) $request->query('submitted_by')),
            'date_from' => trim((string) $request->query('date_from')),
            'date_to' => trim((string) $request->query('date_to')),
            'q' => trim((string) $request->query('q')),
        ];
    }

    private function filteredAnalyticsQuery(array $filters): Builder
    {
        $query = Employee::query()
            ->with(['personalInformation', 'workExperience', 'latestWorkExperience', 'user']);

        $scope = $filters['scope'] ?? '';
        $office = $filters['office'] ?? '';
        $sex = $filters['sex'] ?? '';
        $status = $filters['status'] ?? '';
        $submittedBy = $filters['submitted_by'] ?? '';
        $dateFrom = $filters['date_from'] ?? '';
        $dateTo = $filters['date_to'] ?? '';
        $searchTerm = $filters['q'] ?? '';

        if ($scope === 'incomplete' || $status === 'incomplete') {
            $query->incomplete();
        } elseif ($status === 'complete') {
            $query->where(function($q) {
                $q->whereNotNull('office')
                  ->where('office', '<>', '')
                  ->whereNotNull('profile_photo_path')
                  ->where('profile_photo_path', '<>', '')
                  ->whereHas('workExperience')
                  ->whereHas('personalInformation', function($inner) {
                      $inner->where(function($contact) {
                          $contact->whereNotNull('mobile_no')->where('mobile_no', '<>', '');
                      })->orWhere(function($contact) {
                          $contact->whereNotNull('email_address')->where('email_address', '<>', '');
                      });
                  });
            });
        }

        if ($office !== '') {
            $query->where('office', $office);
        }

        if ($sex !== '') {
            $query->where('sex_at_birth', $sex);
        }

        if ($submittedBy !== '') {
            if ($submittedBy === 'user') {
                $query->whereNotNull('user_id');
            } else {
                $query->whereNull('user_id');
            }
        }

        if ($dateFrom !== '') {
            $query->where('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
        }

        if ($dateTo !== '') {
            $query->where('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
        }

        if ($searchTerm !== '') {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                    ->orWhere('job_order', 'like', "%{$searchTerm}%")
                    ->orWhere('position_title', 'like', "%{$searchTerm}%")
                    ->orWhere('office', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($u) use ($searchTerm) {
                        $u->where('email', 'like', "%{$searchTerm}%")
                            ->orWhere('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        return $query->latest();
    }

    private function analyticsPayload($employees): array
    {
        // We still need to calculate some things that are hard to do in pure SQL without complex queries
        // but we can at least make the collection handling faster.
        $employees->each(function (Employee $employee) {
            $employee->record_status = $this->admin->isComplete($employee) ? 'complete' : 'incomplete';
            $employee->submission_source = $employee->user_id ? 'user' : 'admin';
        });

        $total = $employees->count();
        $complete = $employees->where('record_status', 'complete')->count();
        $incomplete = $employees->where('record_status', 'incomplete')->count();
        $completionRate = $total > 0 ? (int) round(($complete / $total) * 100) : 0;
        $updatedThisMonth = $employees->filter(fn (Employee $employee) => optional($employee->updated_at)?->isCurrentMonth())->count();
        $activeOffices = $employees->pluck('office')->filter()->unique()->count();
        $topOffice = $employees->pluck('office')->filter()->countBy()->sortDesc()->keys()->first();
        $needsAttention = $employees
            ->where('record_status', 'incomplete')
            ->groupBy(fn (Employee $employee) => $employee->office ?: 'Unassigned')
            ->map(fn ($group, $office) => ['office' => $office, 'count' => $group->count()])
            ->sortByDesc('count')
            ->values()
            ->take(5);

        return [
            'totals' => [
                'records' => $total,
                'complete' => $complete,
                'incomplete' => $incomplete,
                'completion_rate' => $completionRate,
                'updated_this_month' => $updatedThisMonth,
                'active_offices' => $activeOffices,
            ],
            'breakdowns' => [
                'offices' => $employees->pluck('office')->filter()->countBy()->sortDesc()->take(6),
                'sexes' => collect([
                    'Male' => $employees->where('sex_at_birth', 'Male')->count(),
                    'Female' => $employees->where('sex_at_birth', 'Female')->count(),
                    'Prefer not to say' => $employees->reject(fn($e) => in_array($e->sex_at_birth, ['Male', 'Female']))->count(),
                ])->filter(fn($v) => $v > 0 || in_array($v, ['Male', 'Female'])), // Keep M/F even if 0
                'submissions' => $employees->pluck('submission_source')->filter()->countBy()->sortDesc(),
            ],
            'insights' => [
                $total === 0 ? 'No records match the current filters.' : "{$completionRate}% of the filtered records are complete.",
                $topOffice ? "{$topOffice} currently has the largest share of filtered records." : 'No office data is available for the current filter set.',
                $incomplete > 0 ? "{$incomplete} filtered records still need follow-up." : 'All filtered records are currently complete.',
            ],
            'needs_attention' => $needsAttention,
        ];
    }
}
