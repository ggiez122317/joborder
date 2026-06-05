<?php

namespace App\Http\Middleware;

use App\Services\AuditLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogSystemActivity
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->route() || $response->getStatusCode() < 400) {
            return $response;
        }

        $routeName = (string) $request->route()->getName();

        if ($routeName === '' || str_starts_with($routeName, 'ignition.')) {
            return $response;
        }

        $eventType = 'error';
        $action = $request->method() . ' ' . $routeName;

        $this->audit->log(
            $eventType,
            $action,
            'Request ended with an error status',
            $request,
            $request->user(),
            null,
            null,
            ['status_code' => $response->getStatusCode()]
        );

        return $response;
    }
}
