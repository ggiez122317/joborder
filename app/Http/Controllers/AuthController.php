<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }

    public function showLogin(Request $request): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($credentials['login']);
        $user = User::query()
            ->where('username', $login)
            ->orWhere('email', Str::lower($login))
            ->first();

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $attempt = [
            'password' => $credentials['password'],
            $field => $field === 'email' ? Str::lower($login) : $login,
        ];

        if (Auth::attempt($attempt, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->forget('url.intended');
            $authenticatedUser = $request->user();

            if ($authenticatedUser?->isUser()) {
                $this->audit->log('auth', 'user-login', 'User signed in', $request, $authenticatedUser);

                if (! $authenticatedUser->hasVerifiedEmail()) {
                    return redirect()->route('verification.notice')
                        ->with('toast_type', 'warning')
                        ->with('status', 'Verify your email to continue to the user dashboard.');
                }

                return redirect()->route('user.dashboard');
            }

            $this->audit->log('auth', 'admin-login', 'Administrator signed in', $request, $authenticatedUser);

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors(['login' => 'Invalid username/email or password.'])
            ->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        $redirect = $user?->isAdmin() ? route('login') : route('user.login');

        if ($user) {
            $this->audit->log('auth', 'logout', 'User signed out', $request, $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to($redirect);
    }

    public function registerUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'office' => ['required', 'string', 'in:' . implode(',', config('offices', []))],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $this->uniqueUsername($validated['email']),
            'email' => Str::lower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'office' => $validated['office'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $code = $user->generateEmailVerificationCode(10);
        $user->notify(new EmailVerificationCodeNotification($code, 10));

        $this->audit->log('auth', 'user-register', 'User account registered', $request, $user, User::class, $user->id);

        return redirect()->route('verification.notice')
            ->with('status', 'Account created. Enter the 6-digit code sent to your email within 10 minutes.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::query()
            ->where('email', Str::lower($validated['email']))
            ->first();

        if ($user) {
            $code = $user->generatePasswordResetCode(10);
            $user->notify(new \App\Notifications\PasswordResetCodeNotification($code, 10));
            $this->audit->log('auth', 'password-reset-requested', 'Requested password reset verification code', $request, $user, User::class, $user->id);
        }

        return redirect()
            ->route('password.reset', ['email' => $validated['email']])
            ->with('status', 'A 6-digit verification code has been sent to your email. It expires in 10 minutes.');
    }

    public function showResetPassword(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()
            ->where('email', Str::lower($validated['email']))
            ->first();

        if (! $user || ! $user->hasValidPasswordResetCode($validated['code'])) {
            return back()->withErrors([
                'code' => 'The verification code is invalid or has expired.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $user->clearPasswordResetCode();

        $this->audit->log('auth', 'password-reset-completed', 'Password reset successfully completed', $request, $user, User::class, $user->id);

        return redirect()
            ->route('login')
            ->with('status', 'Your password has been reset successfully. You can now log in.');
    }

    private function uniqueUsername(string $email): string
    {
        $base = Str::slug(Str::before($email, '@'), '_');
        $base = $base !== '' ? $base : 'user';
        $candidate = $base;
        $counter = 1;

        while (User::query()->where('username', $candidate)->exists()) {
            $counter++;
            $candidate = $base . '_' . $counter;
        }

        return $candidate;
    }
}
