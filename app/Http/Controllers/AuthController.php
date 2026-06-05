<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationCodeNotification;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
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
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        if (! $this->verifyRecaptcha($request->input('g-recaptcha-response'), $request->ip())) {
            return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])->onlyInput('login');
        }

        $login = trim($request->input('login'));
        $throttleKey = 'login_attempts_' . Str::lower($login);

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'login' => "Too many failed attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('login');
        }

        $user = User::query()
            ->where('username', $login)
            ->orWhere('email', Str::lower($login))
            ->first();

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $attempt = [
            'password' => $request->input('password'),
            $field => $field === 'email' ? Str::lower($login) : $login,
        ];

        if (Auth::attempt($attempt, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $request->session()->forget('url.intended');
            $authenticatedUser = $request->user();

            if ($authenticatedUser?->isAdmin()) {
                $this->audit->log('auth', 'admin-login', 'Administrator signed in', $request, $authenticatedUser);
                $request->session()->put('two_factor_pending', false);

                return redirect()->route('dashboard');
            }

            $this->audit->log('auth', 'user-login', 'User signed in', $request, $authenticatedUser);

            if (! $authenticatedUser->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('toast_type', 'warning')
                    ->with('status', 'Verify your email to continue to the user dashboard.');
            }

            if ($this->isUserDeviceTrusted($request, $authenticatedUser)) {
                $request->session()->put('two_factor_pending', false);

                return redirect()->route('user.dashboard');
            }

            $code = $authenticatedUser->generateTwoFactorCode(5);
            $authenticatedUser->notify(new \App\Notifications\LoginCodeNotification($code, 5));

            $request->session()->put('two_factor_pending', true);

            return redirect()->route('login.two-factor.show')
                ->with('status', 'A 6-digit verification code has been sent to your email. It expires in 5 minutes.');
        }

        RateLimiter::hit($throttleKey, 900);

        $this->audit->log('auth', 'login-failed', "Failed login attempt for: {$login}", $request);

        return back()
            ->withErrors(['login' => 'Invalid username/email or password.'])
            ->onlyInput('login');
    }

    private function isUserDeviceTrusted(Request $request, User $user): bool
    {
        $token = $request->cookie('trusted_device');

        if ($token === null) {
            return false;
        }

        return $user->isCurrentDeviceTrusted($token, $request->userAgent());
    }

    public function showTwoFactorForm(): View
    {
        return view('auth.verify-two-factor');
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (! $user || ! $user->isUser()) {
            return redirect()->route('login');
        }

        if ($user->hasValidTwoFactorCode($request->input('code'))) {
            $user->clearTwoFactorCode();

            $token = $user->trustCurrentDevice($request);

            $request->session()->put('two_factor_pending', false);

            $this->audit->log('auth', 'two-factor-verified', 'Two-factor authentication verified', $request, $user, User::class, $user->id);

            return redirect()->route('user.dashboard')
                ->withCookie(cookie('trusted_device', $token, 43200, '/', null, true, true, false, 'Strict'));
        }

        return back()->withErrors(['code' => 'The verification code is invalid or has expired.']);
    }

    public function resendTwoFactorCode(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user || ! $user->isUser()) {
            return redirect()->route('login');
        }

        $code = $user->generateTwoFactorCode(5);
        $user->notify(new \App\Notifications\LoginCodeNotification($code, 5));

        $this->audit->log('auth', 'two-factor-resent', 'Two-factor code resent', $request, $user, User::class, $user->id);

        return back()->with('status', 'A fresh 6-digit verification code has been sent to your email. It expires in 5 minutes.');
    }

    private function verifyRecaptcha(string $token, ?string $ip = null): bool
    {
        $secret = config('services.recaptcha.secret_key');
        if ($secret === null || $secret === '') {
            return true;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $ip,
        ]);

        $result = $response->json();

        return $result['success'] ?? false;
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
