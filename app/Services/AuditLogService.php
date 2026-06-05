<?php

namespace App\Services;

use App\Models\SystemAuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogService
{
    public function log(
        string $eventType,
        string $action,
        ?string $description = null,
        ?Request $request = null,
        ?User $user = null,
        ?string $targetType = null,
        $targetId = null,
        array $context = []
    ): SystemAuditLog {
        $request ??= request();
        $user ??= $request?->user();

        return SystemAuditLog::create([
            'user_id' => $user?->id,
            'user_role' => $user?->role,
            'event_type' => $eventType,
            'action' => $action,
            'description' => $description,
            'http_method' => $request?->method(),
            'route_name' => optional($request?->route())->getName(),
            'path' => $request?->path(),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'target_type' => $targetType,
            'target_id' => $targetId,
            'context' => $context,
        ]);
    }
}
