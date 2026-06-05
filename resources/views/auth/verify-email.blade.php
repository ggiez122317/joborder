<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#eef4f1] text-[#0f172a]">
    @include('partials.toast')
    <main class="auth-shell">
        <section class="auth-hero relative hidden overflow-hidden lg:block">
            <img src="{{ route('brand.login-background') }}" alt="LGU Trento background" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(7,94,84,0.86),rgba(15,23,42,0.68))]"></div>
            <div class="relative flex h-full flex-col justify-between p-10 text-white">
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.24em] text-white/75">LGU Trento</div>
                    <div class="mt-3 text-4xl font-bold leading-tight">Verify your email with a secure code.</div>
                </div>
                <div class="max-w-lg">
                    <p class="text-base leading-7 text-white/80">Enter the 6-digit code we sent to your email address. The code stays valid for 10 minutes, and you can request a fresh one anytime.</p>
                </div>
            </div>
        </section>

        <section class="auth-panel flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-10">
            <div class="w-full max-w-md">
                <section class="overflow-hidden rounded-[28px] border border-[#dbe5e1] bg-white shadow-[0_30px_70px_rgba(15,23,42,0.10)]">
                    <div class="border-b border-[#edf2ef] bg-[radial-gradient(circle_at_top_left,_rgba(22,163,74,0.12),_transparent_50%),linear-gradient(135deg,#ffffff,#f8fbfa)] px-6 py-6">
                        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-[#16a34a]">Email Verification</div>
                        <h1 class="mt-3 text-2xl font-bold text-[#0f172a]">Enter your verification code</h1>
                        <p class="mt-2 text-sm leading-6 text-[#64748b]">Check your inbox, copy the 6-digit code, and enter it below to unlock your user dashboard.</p>
                    </div>

                    <div class="space-y-5 px-6 py-6">
                        <div class="rounded-[18px] border border-[#e5efe8] bg-[#f8fbf9] px-4 py-4">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.12em] text-[#16a34a]">Quick Reminder</div>
                            <div class="mt-2 text-sm leading-6 text-[#64748b]">
                                The verification code expires after <span class="font-semibold text-[#0f172a]">10 minutes</span>.
                                If the code expires, just tap <span class="font-semibold text-[#0f172a]">Resend Code</span>.
                            </div>
                        </div>

                        <form method="POST" action="{{ route('verification.verify') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="code" class="form-label">Verification Code</label>
                                <input
                                    id="code"
                                    name="code"
                                    inputmode="numeric"
                                    maxlength="6"
                                    autocomplete="one-time-code"
                                    value="{{ old('code') }}"
                                    class="form-input mt-2 text-center text-[28px] font-semibold tracking-[0.45em]"
                                    placeholder="000000"
                                >
                                <div class="mt-2 text-xs text-[#94a3b8]">Enter the 6 numbers exactly as shown in the email.</div>
                                @error('code')
                                    <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="btn-primary w-full">Verify Code</button>
                        </form>

                        <div class="grid gap-3">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn-secondary w-full">Resend Code</button>
                            </form>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-secondary w-full">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>
</body>
</html>
