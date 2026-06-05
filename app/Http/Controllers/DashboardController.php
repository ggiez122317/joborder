<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImportHistory;
use App\Services\PdsAdminService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly PdsAdminService $admin)
    {
    }

    public function __invoke(): View
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            $totalEmployees = Employee::count();
            $maleCount = Employee::where('sex_at_birth', 'Male')->count();
            $femaleCount = Employee::where('sex_at_birth', 'Female')->count();
            $otherCount = Employee::whereNotIn('sex_at_birth', ['Male', 'Female'])->orWhereNull('sex_at_birth')->count();

            $jobOrderCount = Employee::where(function($query) {
                $query->whereNotNull('job_order')
                      ->whereNotIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None'])
                      ->orWhereHas('latestWorkExperience', function($q) {
                          $q->where('status_of_appointment', 'like', '%job order%');
                      });
            })->count();

            $regularCount = Employee::whereDoesntHave('latestWorkExperience', function($q) {
                $q->where('status_of_appointment', 'like', '%job order%');
            })->where(function($q) {
                $q->whereNull('job_order')
                  ->orWhereIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None']);
            })->whereHas('latestWorkExperience', function($q) {
                $q->where('status_of_appointment', 'like', '%regular%')
                  ->orWhere('status_of_appointment', 'like', '%permanent%');
            })->count();

            $activeOffices = Employee::whereNotNull('office')->where('office', '<>', '')->distinct('office')->count('office');
            $addedThisMonth = Employee::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

            $months = collect(range(5, 0))
                ->map(fn (int $offset) => now()->startOfMonth()->subMonths($offset))
                ->values();

            $monthlyIntake = $months->map(function (Carbon $month) {
                return Employee::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            });

            $maleTrend = $months->map(function (Carbon $month) {
                return Employee::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('sex_at_birth', 'Male')
                    ->count();
            });

            $femaleTrend = $months->map(function (Carbon $month) {
                return Employee::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('sex_at_birth', 'Female')
                    ->count();
            });

            $typeTrend = [
                'Job Order' => $months->map(fn(Carbon $month) => Employee::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where(function($query) {
                        $query->whereNotNull('job_order')
                              ->whereNotIn('job_order', ['', 'n/a', 'na', 'none', 'n / a', 'no', 'N/A', 'N / A', 'None'])
                              ->orWhereHas('latestWorkExperience', function($q) {
                                  $q->where('status_of_appointment', 'like', '%job order%');
                              });
                    })->count()),
                'N/A' => $months->map(fn(Carbon $month) => Employee::whereYear('created_at', $month->year)
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
                ->whereNotNull('office')
                ->where('office', '<>', '')
                ->groupBy('office')
                ->orderByDesc('count')
                ->get();

            $officeCompletion = $this->admin->officeCompletionStats(
                Employee::with(['personalInformation', 'workExperience', 'latestWorkExperience'])->get()
            );

            $importSummary = ImportHistory::query()
                ->selectRaw('count(*) as total')
                ->selectRaw("sum(case when status = 'completed' then 1 else 0 end) as completed")
                ->selectRaw("sum(case when status = 'failed' then 1 else 0 end) as failed")
                ->first();

            return [
                'totalEmployees' => $totalEmployees,
                'maleCount' => $maleCount,
                'femaleCount' => $femaleCount,
                'otherCount' => $otherCount,
                'jobOrderCount' => $jobOrderCount,
                'regularCount' => $regularCount,
                'activeOffices' => $activeOffices,
                'addedThisMonth' => $addedThisMonth,
                'months' => $months,
                'monthlyIntake' => $monthlyIntake,
                'maleTrend' => $maleTrend,
                'femaleTrend' => $femaleTrend,
                'typeTrend' => $typeTrend,
                'officeStats' => $officeCounts->pluck('count', 'office')->take(5),
                'officeChart' => $officeCounts->pluck('count', 'office')->take(6),
                'officeCompletion' => $officeCompletion,
                'importSummary' => $importSummary,
            ];
        });

        $recentRecords = Employee::query()
            ->with('latestWorkExperience')
            ->latest()
            ->take(8)
            ->get()
            ->map(function (Employee $employee) {
                $type = $employee->employment_type;

                return [
                    'employee' => $employee,
                    'type' => $type,
                    'initials' => $this->initials($employee->full_name),
                    'badgeClass' => $this->badgeClass($type),
                    'avatarClass' => $this->avatarClass($type),
                ];
            })
            ->values();

        $incompleteQueue = Employee::incomplete()
            ->with(['latestWorkExperience', 'personalInformation', 'workExperience'])
            ->get();
        $incompleteCount = $incompleteQueue->count();
        $completeRecords = $stats['totalEmployees'] - $incompleteCount;

        $completionRate = $stats['totalEmployees'] > 0
            ? (int) round(($completeRecords / $stats['totalEmployees']) * 100)
            : 0;

        return view('pds.dashboard', [
            'totalEmployees' => $stats['totalEmployees'],
            'maleCount' => $stats['maleCount'],
            'femaleCount' => $stats['femaleCount'],
            'otherCount' => $stats['otherCount'],
            'jobOrderCount' => $stats['jobOrderCount'],
            'regularCount' => $stats['regularCount'],
            'activeOffices' => $stats['activeOffices'],
            'addedThisMonth' => $stats['addedThisMonth'],
            'completeRecords' => $completeRecords,
            'completionRate' => $completionRate,
            'months' => $stats['months']->map(fn (Carbon $month) => $month->format('M'))->all(),
            'monthlyIntake' => $stats['monthlyIntake']->all(),
            'maleTrend' => $stats['maleTrend']->all(),
            'femaleTrend' => $stats['femaleTrend']->all(),
            'typeTrend' => collect($stats['typeTrend'])->map(fn (Collection $trend) => $trend->all())->all(),
            'officeStats' => $stats['officeStats'],
            'officeChart' => $stats['officeChart'],
            'recentRecords' => $recentRecords,
            'officeCompletion' => $stats['officeCompletion'],
            'incompleteCount' => $incompleteCount,
            'importHistoryTotal' => (int) ($stats['importSummary']->total ?? 0),
            'importHistoryCompleted' => (int) ($stats['importSummary']->completed ?? 0),
            'importHistoryFailed' => (int) ($stats['importSummary']->failed ?? 0),
        ]);
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
}
