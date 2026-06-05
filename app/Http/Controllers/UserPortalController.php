<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SystemAuditLog;
use App\Models\ImportHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Services\AuditLogService;
use App\Services\PdsAdminService;
use App\Services\PdsDataService;
use App\Services\PdsFileParser;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StorePdsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View as ViewContract;
use Illuminate\View\View;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class UserPortalController extends Controller
{
    public function __construct(
        private readonly PdsDataService $pds,
        private readonly PdsFileParser $parser,
        private readonly PdsAdminService $admin,
        private readonly AuditLogService $audit
    ) {
    }

    public function dashboard(Request $request): View
    {
        $employee = $this->employeeForUser($request);
        $userId = $request->user()->id;

        $totalEmployees = Employee::where('created_by', $userId)->count();
        $maleCount = Employee::where('created_by', $userId)->where('sex_at_birth', 'Male')->count();
        $femaleCount = Employee::where('created_by', $userId)->where('sex_at_birth', 'Female')->count();
        $otherCount = Employee::where('created_by', $userId)->whereNotIn('sex_at_birth', ['Male', 'Female'])->orWhereNull('sex_at_birth')->where('created_by', $userId)->count();

        $jobOrderCount = Employee::where('created_by', $userId)->where(function($query) {
            $query->whereNotNull('job_order')
                  ->whereNotIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None'])
                  ->orWhereHas('latestWorkExperience', function($q) {
                      $q->where('status_of_appointment', 'like', '%job order%');
                  });
        })->count();

        $regularCount = Employee::where('created_by', $userId)->whereDoesntHave('latestWorkExperience', function($q) {
            $q->where('status_of_appointment', 'like', '%job order%');
        })->where(function($q) {
            $q->whereNull('job_order')
              ->orWhereIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None']);
        })->whereHas('latestWorkExperience', function($q) {
            $q->where('status_of_appointment', 'like', '%regular%')
              ->orWhere('status_of_appointment', 'like', '%permanent%');
        })->count();

        $activeOffices = Employee::where('created_by', $userId)->whereNotNull('office')->where('office', '<>', '')->distinct('office')->count('office');
        $addedThisMonth = Employee::where('created_by', $userId)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        $incompleteQueue = Employee::incomplete()
            ->where('created_by', $userId)
            ->with(['latestWorkExperience', 'personalInformation', 'workExperience'])
            ->get();
        $incompleteCount = $incompleteQueue->count();
        $completeRecords = $totalEmployees - $incompleteCount;

        $completionRate = $totalEmployees > 0
            ? (int) round(($completeRecords / $totalEmployees) * 100)
            : 0;

        $officeCompletion = $this->admin->officeCompletionStats(
            Employee::where('created_by', $userId)->with(['personalInformation', 'workExperience', 'latestWorkExperience'])->get()
        );

        $importSummary = ImportHistory::query()
            ->where('created_by', $userId)
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when status = 'completed' then 1 else 0 end) as completed")
            ->selectRaw("sum(case when status = 'failed' then 1 else 0 end) as failed")
            ->first();

        $months = collect(range(5, 0))
            ->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset))
            ->values();

        $monthlyIntake = $months->map(function (Carbon $month) use ($userId) {
            return Employee::where('created_by', $userId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        });

        $maleTrend = $months->map(function (Carbon $month) use ($userId) {
            return Employee::where('created_by', $userId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('sex_at_birth', 'Male')
                ->count();
        });

        $femaleTrend = $months->map(function (Carbon $month) use ($userId) {
            return Employee::where('created_by', $userId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('sex_at_birth', 'Female')
                ->count();
        });

        $typeTrend = [
            'Job Order' => $months->map(fn(Carbon $month) => Employee::where('created_by', $userId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where(function($query) {
                    $query->whereNotNull('job_order')
                          ->whereNotIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None'])
                          ->orWhereHas('latestWorkExperience', function($q) {
                              $q->where('status_of_appointment', 'like', '%job order%');
                          });
                })->count()),
            'N/A' => $months->map(fn(Carbon $month) => Employee::where('created_by', $userId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where(function($q) {
                    $q->where(function($sub) {
                        $sub->whereNull('job_order')
                            ->orWhereIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None']);
                    })->whereDoesntHave('latestWorkExperience', function($sub) {
                        $sub->where('status_of_appointment', 'like', '%job order%');
                    });
                })->where(function($q) {
                    $q->whereDoesntHave('latestWorkExperience', function($sub) {
                        $sub->where('status_of_appointment', 'like', '%regular%')
                            ->orWhere('status_of_appointment', 'like', '%permanent%');
                    });
                })->where(function($q) {
                    $q->whereDoesntHave('latestWorkExperience', function($sub) {
                        $sub->where('status_of_appointment', 'like', '%plantilla%');
                    });
                })->count()),
        ];

        $officeCounts = Employee::selectRaw('office, count(*) as count')
            ->where('created_by', $userId)
            ->whereNotNull('office')
            ->where('office', '<>', '')
            ->groupBy('office')
            ->orderByDesc('count')
            ->get();

        $officeStats = $officeCounts->pluck('count', 'office')->take(5);
        $officeChart = $officeCounts->pluck('count', 'office')->take(6);

        $recentRecords = Employee::query()
            ->where('created_by', $userId)
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Employee $emp) {
                $type = $emp->employment_type;

                return [
                    'employee' => $emp,
                    'type' => $type,
                    'initials' => $this->initials($emp->full_name),
                    'badgeClass' => $this->badgeClass($type),
                    'avatarClass' => $this->avatarClass($type),
                ];
            })
            ->values();

        return view('user.dashboard', [
            'employee' => $employee,
            'hasPds' => (bool) $employee,
            'incompleteFields' => $employee ? $this->admin->incompleteFields($employee) : ['personal data sheet not submitted'],
            'notifications' => $this->notificationsForUser($request),
            'unreadNotificationsCount' => $this->unreadNotificationsCount($request),

            // Admin analytics clones
            'totalEmployees' => $totalEmployees,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'otherCount' => $otherCount,
            'jobOrderCount' => $jobOrderCount,
            'regularCount' => $regularCount,
            'activeOffices' => $activeOffices,
            'addedThisMonth' => $addedThisMonth,
            'completeRecords' => $completeRecords,
            'completionRate' => $completionRate,
            'months' => $months->map(fn (Carbon $month) => $month->format('M'))->all(),
            'monthlyIntake' => $monthlyIntake->all(),
            'maleTrend' => $maleTrend->all(),
            'femaleTrend' => $femaleTrend->all(),
            'typeTrend' => collect($typeTrend)->map(fn (Collection $trend) => $trend->all())->all(),
            'officeStats' => $officeStats,
            'officeChart' => $officeChart,
            'recentRecords' => $recentRecords,
            'officeCompletion' => $officeCompletion,
            'incompleteCount' => $incompleteCount,
            'importHistoryTotal' => (int) ($importSummary->total ?? 0),
            'importHistoryCompleted' => (int) ($importSummary->completed ?? 0),
            'importHistoryFailed' => (int) ($importSummary->failed ?? 0),
        ]);
    }

    public function offices(Request $request): View
    {
        $user = $request->user();
        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;

        $employeesQuery = Employee::query()->active();

        if (!$isHR && $user->office) {
            $employeesQuery->where('office', $user->office);
        }

        $employees = $employeesQuery
            ->whereNotNull('office')
            ->where('office', '<>', '')
            ->get();

        $officeDirectory = $employees
            ->groupBy('office')
            ->map(function ($group, $office) {
                return [
                    'office' => $office,
                    'male' => $group->where('sex_at_birth', 'Male')->count(),
                    'female' => $group->where('sex_at_birth', 'Female')->count(),
                    'total' => $group->count(),
                ];
            })
            ->sortBy('office')
            ->values();

        $totalOffices = $officeDirectory->count();
        $totalEmployees = $employees->count();
        $totalMale = $employees->where('sex_at_birth', 'Male')->count();
        $totalFemale = $employees->where('sex_at_birth', 'Female')->count();

        return view('user.offices', [
            'officeDirectory' => $officeDirectory,
            'totalOffices' => $totalOffices,
            'totalEmployees' => $totalEmployees,
            'totalMale' => $totalMale,
            'totalFemale' => $totalFemale,
        ]);
    }

    public function officeStaff(Request $request): View
    {
        $office = (string) $request->query('office');
        $user = $request->user();
        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;

        if (!$isHR && $user->office && strcasecmp($office, $user->office) !== 0) {
            abort(403, 'Unauthorized access to this office.');
        }

        $employees = Employee::query()
            ->active()
            ->where('office', $office)
            ->orderBy('full_name')
            ->get();

        return view('user.office-staff', [
            'office' => $office,
            'employees' => $employees,
        ]);
    }

    public function viewIdCard(Employee $employee)
    {
        $user = auth()->user();
        $isHR = $user && ($user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0);
        abort_unless($isHR, 403, 'Unauthorized access.');

        $employee->load(['personalInformation', 'familyBackground', 'otherInformation']);
        return view('pds.id-card', compact('employee'));
    }

    public function showRecord(Request $request, Employee $employee): View
    {
        abort_unless((int) $employee->created_by === (int) $request->user()->id, 403);

        return view('pds.profile', array_merge($this->profilePayload($employee), [
            'portalMode' => 'user-record',
        ]));
    }

    public function printRecord(Request $request, Employee $employee)
    {
        abort_unless((int) $employee->created_by === (int) $request->user()->id, 403);

        return view('pds.profile', array_merge($this->profilePayload($employee), [
            'printMode' => true,
        ]));
    }

    public function exportPdfRecord(Request $request, Employee $employee)
    {
        abort_unless((int) $employee->created_by === (int) $request->user()->id, 403);

        $payload = array_merge($this->profilePayload($employee), [
            'printMode' => true,
            'pdfMode' => true,
        ]);

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('pds.profile', $payload)
            ->setPaper('a4', 'portrait')
            ->download('PDS-' . str_replace(' ', '-', $employee->full_name) . '.pdf');
    }

    public function records(Request $request): View
    {
        $query = trim((string) $request->query('q'));

        $records = Employee::query()
            ->where('created_by', $request->user()->id)
            ->with(['personalInformation', 'latestWorkExperience'])
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($where) use ($query) {
                    $where->where('full_name', 'like', "%{$query}%")
                        ->orWhere('office', 'like', "%{$query}%")
                        ->orWhere('position_title', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('user.records', [
            'records' => $records,
            'query' => $query,
        ]);
    }

    public function createRecord(Request $request): View
    {
        return view('pds.form', [
            'data' => old() ?: $this->pds->defaultData(),
            'sourceFile' => null,
            'employee' => null,
            'profilePhotoUrl' => null,
            'mode' => 'create',
            'officeOptions' => $this->getUserOfficeOptions($request),
            'portalMode' => 'user-record',
        ]);
    }

    public function storeRecord(StorePdsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();
        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;
        if (!$isHR && $user->office) {
            $validated['personal']['office'] = $user->office;
        }

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

        // Ensure created_by is set but user_id is NOT set (this record is not the user's own PDS)
        $employee->update([
            'created_by' => $request->user()->id,
            'user_id' => null,
        ]);

        $this->admin->createChangeLog($employee, $request->user(), 'created', [], $this->pds->fromEmployee($employee));

        return redirect()
            ->route('user.records')
            ->with('status', 'PDS record created successfully.');
    }

    public function editRecord(Request $request, Employee $employee): View|RedirectResponse
    {
        if ((int) $employee->user_id === (int) $request->user()->id) {
            return redirect()->route('user.pds.form');
        }

        abort_unless((int) $employee->created_by === (int) $request->user()->id, 403);
        $reviewContext = $this->reviewContextFromRequest($request);

        return view('pds.form', [
            'data' => old() ?: $this->pds->fromEmployee($employee),
            'sourceFile' => $employee->source_file,
            'employee' => $employee,
            'profilePhotoUrl' => $employee->profile_photo_path ? route('user.profile-photo', $employee) : null,
            'mode' => 'edit',
            'officeOptions' => $this->getUserOfficeOptions($request, $employee->office),
            'portalMode' => 'user-record',
            'reviewContext' => $reviewContext,
        ]);
    }

    public function updateRecord(StorePdsRequest $request, Employee $employee): RedirectResponse
    {
        if ((int) $employee->user_id === (int) $request->user()->id) {
            return redirect()->route('user.pds.save'); // Should ideally go to savePds logic
        }

        abort_unless((int) $employee->created_by === (int) $request->user()->id, 403);

        $validated = $request->validated();
        $user = $request->user();
        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;
        if (!$isHR && $user->office) {
            $validated['personal']['office'] = $user->office;
        }

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

        $employee = $this->pds->update($employee, $validated, $request->input('source_file'), $profilePhotoPath, $request->user(), $eSignaturePath);
        $this->admin->createChangeLog($employee, $request->user(), 'updated', $before, $this->pds->fromEmployee($employee));

        return redirect()
            ->route('user.records')
            ->with('status', "PDS for {$employee->full_name} updated successfully.");
    }

    public function destroyRecord(Request $request, Employee $employee): RedirectResponse
    {
        abort_unless((int) $employee->created_by === (int) $request->user()->id, 403);

        if ((int) $employee->user_id === (int) $request->user()->id) {
            return back()->withErrors(['delete' => 'You cannot delete your own account PDS record from here.']);
        }

        if ($employee->qr_code_path && Storage::disk('public')->exists($employee->qr_code_path)) {
            Storage::disk('public')->delete($employee->qr_code_path);
        }

        if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
            Storage::disk('public')->delete($employee->profile_photo_path);
        }

        $employee->delete();

        return redirect()
            ->route('user.records')
            ->with('status', 'PDS record deleted successfully.');
    }

    public function pdsForm(Request $request): View
    {
        $employee = $this->employeeForUser($request);
        $reviewContext = $this->reviewContextFromRequest($request);

        return view('pds.form', [
            'data' => old() ?: ($employee ? $this->pds->fromEmployee($employee) : $this->pds->defaultData()),
            'sourceFile' => $employee?->source_file,
            'employee' => $employee,
            'profilePhotoUrl' => $employee ? route('user.profile-photo', $employee) : null,
            'mode' => $employee ? 'edit' : 'create',
            'officeOptions' => $this->getUserOfficeOptions($request, $employee?->office),
            'portalMode' => 'user',
            'reviewContext' => $reviewContext,
        ]);
    }

    public function savePds(StorePdsRequest $request): RedirectResponse
    {
        $employee = $this->employeeForUser($request);
        $validated = $request->validated();
        $user = $request->user();
        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;
        if (!$isHR && $user->office) {
            $validated['personal']['office'] = $user->office;
        }

        $profilePhotoPath = $employee?->profile_photo_path;

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');

            if ($employee?->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
                Storage::disk('public')->delete($employee->profile_photo_path);
            }
        }

        $eSignaturePath = $employee?->e_signature_path;

        if ($request->hasFile('e_signature')) {
            $eSignaturePath = $request->file('e_signature')->store('e-signatures', 'public');

            if ($employee?->e_signature_path && Storage::disk('public')->exists($employee->e_signature_path)) {
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

                        if ($employee?->e_signature_path && Storage::disk('public')->exists($employee->e_signature_path)) {
                            Storage::disk('public')->delete($employee->e_signature_path);
                        }
                    }
                }
            }
        }

        if ($employee) {
            $before = $this->pds->fromEmployee($employee);
            $employee = $this->pds->update($employee, $validated, $request->input('source_file'), $profilePhotoPath, $request->user(), $eSignaturePath);
            $this->admin->createChangeLog($employee, $request->user(), 'updated', $before, $this->pds->fromEmployee($employee));
            $missing = $this->admin->incompleteFields($employee);
        } else {
            $employee = $this->pds->save($validated, $request->user(), $request->input('source_file'), $profilePhotoPath, $eSignaturePath);
            $this->admin->createChangeLog($employee, $request->user(), 'created', [], $this->pds->fromEmployee($employee));
            $missing = $this->admin->incompleteFields($employee);
        }

        return redirect()->route('user.dashboard')->with('status', 'Your PDS has been saved and is now visible in admin records.');
    }

    public function photo(Request $request, Employee $employee): Response
    {
        $userId = $request->user()->id;
        abort_unless($employee->user_id === $userId || (int) $employee->created_by === (int) $userId, 403);

        if ($employee->profile_photo_path && Storage::disk('public')->exists($employee->profile_photo_path)) {
            return Storage::disk('public')->response($employee->profile_photo_path, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        return response()->file(public_path('assets/profile-placeholder.svg'));
    }

    public function signature(Request $request, Employee $employee): Response
    {
        $userId = $request->user()->id;
        abort_unless($employee->user_id === $userId || (int) $employee->created_by === (int) $userId, 403);

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

    public function print(Request $request)
    {
        $employee = $this->employeeForUser($request);
        abort_unless($employee, 404);

        return view('pds.profile', array_merge($this->profilePayload($employee), [
            'printMode' => true,
        ]));
    }

    public function exportPdf(Request $request)
    {
        $employee = $this->employeeForUser($request);
        abort_unless($employee, 404);

        $payload = array_merge($this->profilePayload($employee), [
            'printMode' => true,
            'pdfMode' => true,
        ]);

        return Pdf::loadView('pds.profile', $payload)
            ->setPaper('a4', 'portrait')
            ->download('PDS-' . str_replace(' ', '-', $employee->full_name) . '.pdf');
    }

    private function profilePayload(Employee $employee): array
    {
        $qrSvg = null;
        if ($employee->qr_code_path && Storage::disk('public')->exists($employee->qr_code_path)) {
            $qrSvg = Storage::disk('public')->get($employee->qr_code_path);
        }

        return [
            'employee' => $employee,
            'data' => $this->pds->fromEmployee($employee),
            'qrSvg' => $qrSvg,
            'profilePhotoUrl' => route('user.profile-photo', ['employee' => $employee, 'v' => optional($employee->updated_at)?->timestamp]),
            'profileLink' => route('profile.public', $employee),
            'profileStatus' => 'Active',
            'employmentType' => $employee->employment_type,
            'changeLogs' => $employee->changeLogs()->with('user')->take(10)->get(),
            'incompleteFields' => $this->admin->incompleteFields($employee),
            'printMode' => false,
            'pdfMode' => false,
            'publicMode' => false,
        ];
    }

    private function employeeForUser(Request $request): ?Employee
    {
        $user = $request->user();

        // 1. Try to find by user_id
        $employee = Employee::query()
            ->with(['personalInformation', 'familyBackground', 'education', 'eligibility', 'workExperience', 'voluntaryWork', 'trainings', 'otherInformation', 'latestWorkExperience'])
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        if ($employee) {
            return $employee;
        }

        // 2. Try to find by email address in Personal Information if not linked yet
        $employeeByEmail = Employee::query()
            ->with(['personalInformation', 'familyBackground', 'education', 'eligibility', 'workExperience', 'voluntaryWork', 'trainings', 'otherInformation', 'latestWorkExperience'])
            ->whereNull('user_id')
            ->whereHas('personalInformation', function ($query) use ($user) {
                $query->where('email_address', $user->email);
            })
            ->latest('id')
            ->first();

        if ($employeeByEmail) {
            // Auto-link the employee to the user
            $employeeByEmail->update(['user_id' => $user->id]);
            return $employeeByEmail;
        }

        return null;
    }

    private function notificationsForUser(Request $request)
    {
        return $this->visibleNotificationsQuery($request)
            ->latest()
            ->limit(10)
            ->get();
    }

    private function unreadNotificationsCount(Request $request): int
    {
        return $this->visibleNotificationsQuery($request)
            ->whereNull('read_at')
            ->count();
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        $this->visibleNotificationsQuery($request)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }

    public function markSingleNotificationRead($id)
    {
        $notification = SystemAuditLog::findOrFail($id);
        $currentUser = auth()->user();

        if ($currentUser->isUser()) {
            $isRecipient = (int) data_get($notification->context, 'recipient_user_id') === (int) $currentUser->id;

            abort_unless($isRecipient, 403);
        }

        $notification->update(['read_at' => now()]);

        $redirectUrl = url('/');
        if ($notification->action === 'admin-pds-return-incomplete' && $currentUser->isUser()) {
            $missing = collect(data_get($notification->context, 'missing_fields', []))
                ->filter()
                ->map(fn($field) => $this->normalizeMissingField($field))
                ->unique()
                ->values();

            $employee = Employee::find($notification->target_id);
            $reviewParam = implode(',', $missing->all());

            if ($employee && (int) $employee->user_id === (int) $currentUser->id) {
                // Their own PDS
                $redirectUrl = route('user.pds.form', ['review' => $reviewParam]);
            } elseif ($employee && (int) $employee->created_by === (int) $currentUser->id) {
                // A record they created for someone else
                $redirectUrl = route('user.records.edit', [
                    'employee' => $employee->id,
                    'review' => $reviewParam
                ]);
            } else {
                $redirectUrl = route('user.pds.form', ['review' => $reviewParam]);
            }
        } elseif ($notification->target_type === 'App\Models\Employee' && $notification->target_id) {
            $redirectUrl = $currentUser->isAdmin()
                ? route('profile.show', $notification->target_id)
                : route('user.pds.form');
        } elseif ($notification->path) {
            $redirectUrl = $this->notificationFallbackPath($notification->path, $currentUser);
        }

        return redirect($redirectUrl);
    }

    private function visibleNotificationsQuery(Request $request)
    {
        $query = SystemAuditLog::query()
            ->whereIn('action', [
                'user-pds-create',
                'user-pds-update',
                'employee-created',
                'employee-updated',
                'user-pds-upload-review',
                'admin-pds-return-incomplete',
            ]);

        if ($request->user()->isUser()) {
            $query
                ->where('action', 'admin-pds-return-incomplete')
                ->where('context->recipient_user_id', $request->user()->id);
        }

        return $query;
    }

    private function reviewContextFromRequest(Request $request): ?array
    {
        $requested = collect(explode(',', (string) $request->query('review')))
            ->map(fn(string $field) => $this->normalizeMissingField($field))
            ->filter()
            ->unique()
            ->values();

        if ($requested->isEmpty()) {
            return null;
        }

        $fieldMap = [
            'office' => ['personal.office'],
            'photo' => ['profile_photo'],
            'contact' => ['personal.mobile_no', 'personal.email_address', 'personal.telephone_no'],
            'work data' => [
                'work_experience.0.date_from',
                'work_experience.0.date_to',
                'work_experience.0.position_title',
                'work_experience.0.department_agency_office_company',
                'work_experience.0.status_of_appointment',
            ],
        ];

        return [
            'missing_labels' => $requested->all(),
            'field_paths' => $requested
                ->flatMap(fn(string $key) => $fieldMap[$key] ?? [])
                ->unique()
                ->values()
                ->all(),
            'focus_step' => $requested->contains('work data') ? 4 : 0,
        ];
    }

    private function normalizeMissingField(string $field): string
    {
        return match (strtolower(trim($field))) {
            'work', 'work-data', 'work_data', 'work data' => 'work data',
            'contact', 'contacts' => 'contact',
            'photo', 'profile photo', 'profile_photo' => 'photo',
            'office' => 'office',
            default => strtolower(trim($field)),
        };
    }

    private function notificationFallbackPath(?string $path, $currentUser): string
    {
        $normalizedPath = '/' . ltrim((string) $path, '/');

        if ($currentUser?->isAdmin()) {
            if (str_starts_with($normalizedPath, '/portal/')) {
                return route('records.index');
            }

            return url($normalizedPath);
        }

        if ($currentUser?->isUser() && str_starts_with($normalizedPath, '/portal/')) {
            return url($normalizedPath);
        }

        return $currentUser?->isUser()
            ? route('user.dashboard')
            : route('dashboard');
    }
    private function initials(?string $name): string
    {
        $parts = collect(explode(' ', (string) $name))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)));

        return $parts->isNotEmpty() ? $parts->join('') : 'NA';
    }

    private function badgeClass(string $type): string
    {
        return match ($type) {
            'Regular' => 'badge-regular',
            'Job Order' => 'badge-job-order',
            default => 'badge-plantilla',
        };
    }

    private function avatarClass(string $type): string
    {
        return match ($type) {
            'Regular' => 'avatar-regular',
            'Job Order' => 'avatar-job-order',
            default => 'avatar-plantilla',
        };
    }

    private function getUserOfficeOptions(Request $request, ?string $selected = null): array
    {
        $user = $request->user();
        if (!$user) {
            return [];
        }

        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;

        if ($isHR) {
            return $this->pds->officeOptions($selected);
        }

        return $user->office ? [$user->office] : [];
    }

    public function showProfile(Request $request): View
    {
        $offices = $this->pds->officeOptions();
        return view('user.profile', compact('offices'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->input('action_type') === 'password') {
            $validated = $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            $this->audit->log('auth', 'user-password-change', 'User changed their password', $request, $user, get_class($user), $user->id);

            return back()->with('status', 'Password updated successfully.');
        }

        // Action type details
        $offices = $this->pds->officeOptions();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'office' => ['sometimes', 'string', 'in:' . implode(',', $offices)],
        ]);

        $before = [
            'name' => $user->name,
            'office' => $user->office,
        ];

        $updateData = [
            'name' => $validated['name'],
        ];

        // Restrict office editing for regular users
        $isHR = $user->isAdmin() || strcasecmp($user->office ?? '', 'HRMO') === 0;
        if ($isHR && isset($validated['office'])) {
            $updateData['office'] = $validated['office'];
        }

        $user->update($updateData);

        $after = [
            'name' => $user->name,
            'office' => $user->office,
        ];

        $this->audit->log('crud', 'user-profile-update', 'User updated account details', $request, $user, get_class($user), $user->id, $before, $after);

        return back()->with('status', 'Profile details updated successfully.');
    }
}
