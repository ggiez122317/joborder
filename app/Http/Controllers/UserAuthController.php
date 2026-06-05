<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAuthController extends Controller
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function showLogin(): RedirectResponse
    {
        return redirect()->route('login');
    }

    public function showRegister(): View
    {
        $offices = collect(config('offices', []))->sort()->values()->all();
        return view('auth.register', compact('offices'));
    }

    public function verifyNotice(): View
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard')->with('status', 'Email already verified.');
        }

        if (! $user->hasValidEmailVerificationCode($validated['code'])) {
            return back()->withErrors([
                'code' => 'The verification code is invalid or already expired.',
            ]);
        }

        $user->markEmailAsVerified();
        $user->clearEmailVerificationCode();
        $this->audit->log('auth', 'email-verified', 'User verified email address', $request, $user, User::class, $user->id);

        return redirect()->route('user.dashboard')->with('status', 'Email verified successfully.');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        if ($request->user()?->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        $user = $request->user();
        $code = $user?->generateEmailVerificationCode(10);

        if ($user && $code) {
            $user->notify(new EmailVerificationCodeNotification($code, 10));
        }

        $this->audit->log('auth', 'verification-resent', 'Verification email resent', $request, $request->user(), User::class, $request->user()?->id);

        return back()->with('status', 'A fresh 6-digit verification code has been sent to your email. It expires in 10 minutes.');
    }
}
