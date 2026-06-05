<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            background: #eef4f1;
            color: #0f172a;
        }

        .login-layout {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        }

        .login-form-side {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px clamp(32px, 7vw, 96px);
        }

        .login-form-wrap {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .login-brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .login-kicker {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #15803d;
        }

        .login-brand-title {
            margin-top: 2px;
            font-size: 18px;
            font-weight: 800;
        }

        .login-heading {
            margin-bottom: 32px;
        }

        .login-heading h1 {
            margin: 12px 0 0;
            font-size: 40px;
            line-height: 1.05;
            font-weight: 800;
        }

        .login-heading p,
        .login-help,
        .login-note,
        .login-signup {
            color: #64748b;
        }

        .login-heading p {
            margin: 14px 0 0;
            font-size: 14px;
            line-height: 1.7;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }

        .login-help {
            margin-top: 6px;
            font-size: 12px;
        }

        .login-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }

        .login-note,
        .login-signup {
            text-align: center;
            font-size: 12px;
        }

        .login-signup {
            font-size: 14px;
        }

        .login-signup a {
            color: #15803d;
            font-weight: 700;
            text-decoration: none;
        }

        .login-image-side {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .login-image-side img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-image-side::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 18% 20%, rgba(22, 163, 74, .22), transparent 34%),
                linear-gradient(135deg, rgba(7, 94, 84, .72), rgba(15, 23, 42, .54));
        }

        .login-image-copy {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 64px clamp(32px, 7vw, 96px);
            color: white;
        }

        .login-image-copy h2 {
            max-width: 560px;
            margin: 16px 0 0;
            font-size: 44px;
            line-height: 1.08;
            font-weight: 800;
        }

        .login-image-copy p {
            max-width: 520px;
            margin: 18px 0 0;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(255, 255, 255, .82);
        }

        .login-image-copy .login-kicker {
            color: rgba(255, 255, 255, .82);
        }

        @media (max-width: 1023px) {
            .login-layout {
                display: block;
            }

            .login-form-side {
                padding: 40px 24px;
            }

            .login-form-wrap {
                max-width: 460px;
                margin: 0 auto;
            }

            .login-image-side {
                display: none;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-[#eef4f1] text-[#0f172a]">
    @include('partials.toast')
    <main class="login-layout">
        <section class="login-image-side" aria-hidden="true">
            <img src="{{ route('brand.login-background') }}" alt="">
            <div class="login-image-copy">
                <div class="login-kicker">Personnel Records</div>
                <h2>Manage PDS access from one secure sign-in.</h2>
                <p>Administrators go straight to records and user management. Verified users continue to their own personal data sheet workspace.</p>
            </div>
        </section>

        <section class="login-form-side">
            <div class="login-form-wrap">
                <div class="login-brand">
                    <img src="{{ route('brand.logo') }}" alt="LGU Trento Logo">
                    <div>
                        <div class="login-kicker">LGU Trento</div>
                        <div class="login-brand-title">PDS Management System</div>
                    </div>
                </div>

                <div class="login-heading">
                    <h1>Sign in to continue</h1>
                    <p>Use your admin username or your verified user email on this same page.</p>
                </div>

                <form method="POST" action="{{ route('login.attempt') }}" class="login-form">
                    @csrf
                    <div>
                        <label class="form-label" for="login">Username or Email</label>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" class="form-input" autocomplete="username" inputmode="email" autocapitalize="off" spellcheck="false" autofocus>
                        @error('login')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="password">Password</label>
                        <input id="password" name="password" type="password" class="form-input" autocomplete="current-password">
                        @error('password')
                            <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between my-1">
                        <label class="login-remember">
                            <input type="checkbox" name="remember" value="1" class="rounded border-[#cbd5e1] text-[#16a34a]">
                            <span>Remember session</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-[#15803d] hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-primary w-full">Login</button>
                    <div class="login-signup">
                        No account yet?
                        <a href="{{ route('user.register') }}">Sign up here</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
