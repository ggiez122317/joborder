<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <style>
        @page { size: a4 portrait; margin: 0; }
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; color-adjust: exact; }
        body { margin: 0; padding: 0; background: #FFFFFF; color: #000000; font-family: Arial, sans-serif; }
        .print-hidden { display: none !important; }
        .pds-doc { width: 100%; max-width: 210mm; margin: 0; background: #FFFFFF; color: #000000; font-family: Arial, sans-serif; font-size: 8px; line-height: 1.15; }
        .pds-page { padding: 10mm; height: 297mm; page-break-after: always; background: #FFFFFF; display: flex; flex-direction: column; }
        .pds-page:last-child { page-break-after: auto; }
        .pds-table { width: 100%; height: 100%; border-collapse: collapse; table-layout: fixed; }
        .pds-table td, .pds-table th { border: 1px solid #000000; padding: 2px 3px; vertical-align: middle; word-break: break-word; }
        .pds-section, .pds-label { background: #F3F4F6 !important; font-weight: bold; text-transform: uppercase; }
        .pds-value { background: #FFFFFF !important; font-weight: bold; min-height: 18px; }
        .pds-small { font-size: 7px; font-weight: normal; }
        .bg-white { background: #FFFFFF !important; }
        .bg-light { background: #F3F4F6 !important; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        @media screen {
            body {
                background: #f1f5f9;
                padding: 40px 0;
            }

            .pds-doc {
                width: 210mm;
                margin: 0 auto;
                box-shadow: 0 0 0 1px rgba(0,0,0,0.05), 0 20px 50px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>
    @yield('content')
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
