<?php

namespace App\Http\Controllers;

use App\Models\SystemAuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditLogPageController extends Controller
{
    public function index(Request $request): View
    {
        $eventType = trim((string) $request->query('event_type'));
        $query = trim((string) $request->query('q'));

        $logs = SystemAuditLog::query()
            ->with('user')
            ->when($eventType !== '', fn ($builder) => $builder->where('event_type', $eventType))
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($where) use ($query) {
                    $where->where('action', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('ip_address', 'like', "%{$query}%")
                        ->orWhere('route_name', 'like', "%{$query}%");
                });
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('pds.admin.audit-logs', [
            'logs' => $logs,
            'eventType' => $eventType,
            'query' => $query,
        ]);
    }

    public function clear(): RedirectResponse
    {
        SystemAuditLog::query()->delete();

        return redirect()
            ->route('admin.audit-logs')
            ->with('status', 'Audit logs deleted successfully.');
    }
}
