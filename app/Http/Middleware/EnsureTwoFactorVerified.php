<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('two_factor_pending', false)) {
            $allowedRoutes = [
                'login.two-factor.show',
                'login.two-factor.verify',
                'login.two-factor.resend',
                'logout',
            ];

            if (! in_array($request->route()?->getName(), $allowedRoutes, true)) {
                return redirect()->route('login.two-factor.show');
            }
        }

        return $next($request);
    }
}
