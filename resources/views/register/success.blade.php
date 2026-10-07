<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Received &mdash; {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <style>
        body { margin: 0; background: #eef4f1; color: #0f172a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; }
        .card {
            background: #fff; border-radius: 16px; box-shadow: 0 12px 32px rgba(15,23,42,.08);
            padding: 40px 36px; max-width: 480px; width: 100%; text-align: center;
        }
        .check {
            width: 72px; height: 72px; border-radius: 50%; background: #f0fdf4;
            display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px;
        }
        h1 { margin: 0 0 10px; font-size: 22px; font-weight: 800; }
        p { margin: 0 0 8px; font-size: 14px; line-height: 1.7; color: #475569; }
        .brand { margin-top: 22px; font-size: 11px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #15803d; }
    </style>
</head>
<body>
    <main class="wrap">
        <div class="card">
            <div class="check">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
            </div>
            <h1>Registration received!</h1>
            @if (!empty($name))
                <p>Thank you, <strong>{{ $name }}</strong>.</p>
            @endif
            <p>Your details were sent to the HR office for review. They will contact you on your mobile number once your record is ready.</p>
            <div class="brand">LGU Trento &bull; PDS Management System</div>
        </div>
    </main>
</body>
</html>
