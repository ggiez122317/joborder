<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeChangeLog;
use App\Models\ImportHistory;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdsAdminService
{
    public function sidebarCounts(): array
    {
        return [
            'imports' => ImportHistory::query()
                ->whereIn('status', ['reviewed', 'failed'])
                ->count(),
            'incomplete' => Employee::incomplete()->count(),
        ];
    }

    public function incompleteFields(Employee $employee): array
    {
        $missing = [];

        if (blank($employee->office)) {
            $missing[] = 'office';
        }

        if (blank($employee->profile_photo_path)) {
            $missing[] = 'photo';
        }

        if ($employee->workExperience->isEmpty() || blank(optional($employee->latestWorkExperience)->position_title)) {
            $missing[] = 'work data';
        }

        if ($employee->personalInformation && blank($employee->personalInformation->mobile_no) && blank($employee->personalInformation->email_address)) {
            $missing[] = 'contact';
        }

        return $missing;
    }

    public function isComplete(Employee $employee): bool
    {
        return $this->incompleteFields($employee) === [];
    }

    public function officeCompletionStats(?Collection $employees = null): Collection
    {
        if ($employees !== null) {
            return $employees
                ->groupBy(fn (Employee $employee) => $employee->office ?: 'Unassigned')
                ->map(function (Collection $group, string $office) {
                    $missingFields = $group
                        ->sum(fn (Employee $employee) => count($this->incompleteFields($employee)));
                    $completeCount = $group
                        ->filter(fn (Employee $employee) => $this->isComplete($employee))
                        ->count();

                    return [
                        'office' => $office,
                        'total' => $group->count(),
                        'complete' => $completeCount,
                        'completion_rate' => $group->count() > 0 ? (int) round(($completeCount / $group->count()) * 100) : 0,
                        'missing_fields_count' => $missingFields,
                    ];
                })
                ->sortByDesc('total')
                ->values();
        }

        $offices = Employee::select('office')
            ->groupBy('office')
            ->get()
            ->pluck('office')
            ->map(fn($o) => $o ?: 'Unassigned')
            ->unique();

        return $offices->map(function(string $office) {
            $officeVal = $office === 'Unassigned' ? null : $office;
            $query = Employee::where(function($q) use ($officeVal) {
                if (is_null($officeVal)) {
                    $q->whereNull('office')->orWhere('office', '');
                } else {
                    $q->where('office', $officeVal);
                }
            });

            $total = $query->count();
            $incompleteRecords = (clone $query)->incomplete()
                ->with(['workExperience', 'personalInformation'])
                ->get();
            
            $incompleteCount = $incompleteRecords->count();
            $complete = $total - $incompleteCount;

            $missingFieldsCount = $incompleteRecords->sum(fn($emp) => count($this->incompleteFields($emp)));

            return [
                'office' => $office,
                'total' => $total,
                'complete' => $complete,
                'completion_rate' => $total > 0 ? (int) round(($complete / $total) * 100) : 0,
                'missing_fields_count' => $missingFieldsCount,
            ];
        })->sortByDesc('total')->values();
    }

    public function createChangeLog(Employee $employee, ?User $user, string $event, array $before = [], array $after = []): ?EmployeeChangeLog
    {
        $changes = $this->diffPayloads($before, $after);

        if ($event === 'created') {
            $changes = collect($this->flatten($after))
                ->filter(fn ($value) => filled($value))
                ->mapWithKeys(fn ($value, $key) => [$key => ['from' => null, 'to' => $value]])
                ->all();
        }

        if ($changes === []) {
            return null;
        }

        return EmployeeChangeLog::create([
            'employee_id' => $employee->id,
            'user_id' => $user?->id,
            'event' => $event,
            'changes' => $changes,
        ]);
    }

    public function createImportHistory(array $attributes): ImportHistory
    {
        return ImportHistory::create($attributes);
    }

    public function storeErrorReport(string $filename, string $message): string
    {
        $safeName = Str::slug(pathinfo($filename, PATHINFO_FILENAME) ?: 'upload');
        $path = 'import-error-reports/' . $safeName . '-' . now()->format('YmdHis') . '.txt';

        Storage::disk('public')->put($path, trim($message) . PHP_EOL);

        return $path;
    }

    private function diffPayloads(array $before, array $after): array
    {
        $flatBefore = $this->flatten($before);
        $flatAfter = $this->flatten($after);
        $keys = collect(array_keys($flatBefore))
            ->merge(array_keys($flatAfter))
            ->unique()
            ->sort()
            ->values();

        $changes = [];

        foreach ($keys as $key) {
            $from = $flatBefore[$key] ?? null;
            $to = $flatAfter[$key] ?? null;

            if ($from === $to) {
                continue;
            }

            $changes[$key] = [
                'from' => $from,
                'to' => $to,
            ];
        }

        return $changes;
    }

    private function flatten(array $payload, string $prefix = ''): array
    {
        $flat = [];

        foreach ($payload as $key => $value) {
            $path = $prefix === '' ? (string) $key : $prefix . '.' . $key;

            if (is_array($value)) {
                $flat += $this->flatten($value, $path);
                continue;
            }

            $flat[$path] = is_bool($value)
                ? ($value ? 'Yes' : 'No')
                : (is_null($value) ? null : trim((string) $value));
        }

        return Arr::where($flat, fn ($value, $key) => !Str::endsWith($key, '.sort_order'));
    }
}
