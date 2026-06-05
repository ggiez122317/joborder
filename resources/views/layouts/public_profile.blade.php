<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f8fafc] text-[#0f172a]">
    @include('partials.toast')
    <main class="mx-auto max-w-6xl px-4 py-6 sm:px-5">
        @yield('content')
    </main>
</body>
</html>
