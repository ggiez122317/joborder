<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Link Expired &mdash; {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <style>
        body { margin: 0; background: #eef4f1; color: #0f172a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px 16px; }
        .card {
            background: #fff; border-radius: 16px; box-shadow: 0 12px 32px rgba(15,23,42,.08);
            padding: 40px 36px; max-width: 480px; width: 100%; text-align: center;
        }
        h1 { margin: 0 0 10px; font-size: 22px; font-weight: 800; }
        p { margin: 0; font-size: 14px; line-height: 1.7; color: #475569; }
        .brand { margin-top: 22px; font-size: 11px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: #15803d; }
    </style>
</head>
<body>
    <main class="wrap">
        <div class="card">
            <h1>This link no longer works</h1>
            <p>It may have expired, reached its usage limit, or been revoked. Please ask the HR office for a fresh registration link.</p>
            <div class="brand">LGU Trento &bull; PDS Management System</div>
        </div>
    </main>
</body>
</html>
