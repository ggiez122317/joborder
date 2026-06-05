<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ImportHistory;
use App\Services\AuditLogService;
use App\Services\PdsAdminService;
use App\Services\PdsDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdminToolsController extends Controller
{
    public function __construct(
        private readonly PdsAdminService $admin,
        private readonly PdsDataService $pds,
        private readonly AuditLogService $audit
    ) {
    }

    public function importHistory(): View
    {
        $imports = ImportHistory::query()
            ->with(['creator', 'employee'])
            ->latest()
            ->paginate(15);

        return view('pds.admin.import-history', compact('imports'));
    }

    public function downloadErrorReport(ImportHistory $importHistory): Response
    {
        abort_unless(
            $importHistory->error_report_path && Storage::disk('public')->exists($importHistory->error_report_path),
            404
        );

        return Storage::disk('public')->download($importHistory->error_report_path);
    }

    public function incompleteQueue(): View
    {
        $employees = Employee::query()
            ->with(['personalInformation', 'workExperience', 'latestWorkExperience'])
            ->latest()
            ->get()
            ->map(function (Employee $employee) {
                $missing = $this->admin->incompleteFields($employee);

                return [
                    'employee' => $employee,
                    'missing' => $missing,
                    'missing_count' => count($missing),
                ];
            })
            ->filter(fn (array $item) => $item['missing_count'] > 0)
            ->sortByDesc('missing_count')
            ->values();

        return view('pds.admin.incomplete-queue', compact('employees'));
    }

    public function notifyIncomplete(Request $request, Employee $employee): RedirectResponse
    {
        $missing = $this->admin->incompleteFields($employee);

        if ($missing === []) {
            return back()->with('status', 'This PDS is already complete.');
        }

        $recipientUserId = $employee->user_id ?? $employee->created_by;

        if (! $recipientUserId) {
            return back()->withErrors([
                'notify_user' => 'This employee record is not linked to any user account and cannot be sent back.',
            ]);
        }

        $this->audit->log(
            'notification',
            'admin-pds-return-incomplete',
            'Admin returned PDS for completion: missing ' . implode(', ', $missing),
            $request,
            $request->user(),
            Employee::class,
            $employee->id,
            [
                'recipient_user_id' => $recipientUserId,
                'missing_fields' => array_values($missing),
            ]
        );

        return back()->with('status', 'The user was notified to complete the missing PDS details.');
    }

}
