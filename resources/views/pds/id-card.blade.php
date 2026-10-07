<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ID Card - {{ $employee->full_name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Alex+Brush&display=swap');

        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-print {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-print:hover {
            background-color: #15803d;
            transform: translateY(-1px);
        }

        .print-menu {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .print-menu > .btn-print {
            width: 100%;
        }

        .print-menu-items {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            flex-direction: column;
            z-index: 1001;
        }

        .print-menu.open .print-menu-items {
            display: flex;
        }

        .print-menu-items button {
            background: #ffffff;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
        }

        .print-menu-items button:last-child {
            border-bottom: none;
        }

        .print-menu-items button:hover {
            background: #f8fafc;
        }

        .print-menu-items .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .print-grid {
            display: grid;
            grid-template-columns: repeat(2, 3.5in);
            gap: 10mm;
            justify-content: center;
            padding: 20mm 10mm;
        }

        .id-container {
            width: 3.5in;
            height: 5.5in;
            background-image: url('{{ \App\Models\IdTemplate::getActiveImageUrl() }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border: 1px solid #cbd5e1;
            box-sizing: border-box;
        }

        /* Profile Photo - rounded rect to match template white box */
        .photo-container {
            position: absolute;
            top: 118px;
            left: 50%;
            transform: translateX(-50%);
            width: 165px;
            height: 165px;
            border-radius: 22px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            border: none; /* Removed circle border */
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Name block - nickname + full name grouped so they stay centered as one unit */
        .name-block {
            position: absolute;
            top: 288px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
            z-index: 2;
            padding: 0 12px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .nickname {
            font-family: 'Century Gothic', CenturyGothic, AppleGothic, 'Segoe UI', sans-serif;
            font-size: 24pt;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 0.5px;
            color: #000;
            white-space: nowrap;
        }

        .fullname {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 30pt;
            font-weight: 400;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: 1px;
            color: #14532d;
            white-space: nowrap;
        }

        .office {
            position: absolute;
            top: 371px;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
            z-index: 2;
            padding: 0 12px;
            box-sizing: border-box;
            font-family: 'Myriad Pro', Myriad, 'Segoe UI', Tahoma, sans-serif;
            font-size: 11pt;
            font-weight: 600;
            text-transform: uppercase;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 25px;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
        }

        /* Long position titles step down so they stay inside the green pill */
        .office-long {
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }

        /* QR Code & ID Number - tucked in the free lane left of the baked mayor
           signature (ink starts ~26% width): shared center x (46px) */
        .qr-container {
            position: absolute;
            bottom: 28px;
            left: 12px;
            width: 68px;
            height: 68px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            mix-blend-mode: multiply;
        }

        .job-order {
            position: absolute;
            bottom: 104px;
            left: 0;
            width: 92px;
            text-align: center;
            color: #000;
            font-weight: 900;
            font-size: 8pt;
            line-height: 1.2;
            letter-spacing: -0.1px;
        }

        .job-order span {
            color: #dc2626;
        }

        /* ID Card Back Styles */
        .id-container-back {
            width: 3.5in;
            height: 5.5in;
            background-color: #ffffff;
            @if (\App\Models\IdTemplate::getActiveBackImageUrl())
                background-image: url('{{ \App\Models\IdTemplate::getActiveBackImageUrl() }}');
            @else
                background-image: url('{{ \App\Models\IdTemplate::getActiveImageUrl() }}');
            @endif
            background-size: 100% 100%;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border: 1px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            padding: 14px;
            box-sizing: border-box;
            color: #1e293b;
        }

        .back-header-banner {
            background-color: #16a34a; /* Matches LGU active template green */
            color: #ffffff;
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18pt;
            letter-spacing: 2px;
            padding: 3px 0;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .back-terms {
            font-size: 6.8pt;
            line-height: 1.3;
            text-align: center;
            color: #475569;
            margin-bottom: 10px;
            padding: 0 4px;
            font-weight: 500;
        }

        .back-details-grid {
            display: grid;
            grid-template-columns: 85px 1fr;
            row-gap: 3.5px;
            column-gap: 5px;
            margin-bottom: 10px;
            font-size: 8pt;
            background-color: #f8fafc;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
        }

        .back-details-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 7.2pt;
        }

        .back-details-value {
            font-weight: 600;
            color: #0f172a;
        }

        .back-emergency-banner {
            background-color: #dc2626; /* Matches LGU office red */
            color: #ffffff;
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 10.5pt;
            letter-spacing: 1px;
            padding: 3px 0;
            border-radius: 6px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .back-emergency-grid {
            display: grid;
            grid-template-columns: 60px 1fr;
            row-gap: 3.5px;
            column-gap: 5px;
            font-size: 7.8pt;
            padding: 0 4px;
            margin-bottom: 8px;
        }

        .back-emergency-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 7pt;
        }

        .back-emergency-value {
            font-weight: 600;
            color: #0f172a;
            line-height: 1.2;
        }

        .back-signatory-section {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-bottom: 2px;
        }

        .back-signature {
            font-family: 'Alex Brush', cursive;
            font-size: 25pt;
            color: #1d4ed8; /* Blue ink pen */
            margin-bottom: -15px;
            z-index: 2;
            transform: rotate(-3deg);
            user-select: none;
        }

        .back-signatory-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 13.5pt;
            color: #0f172a;
            letter-spacing: 0.8px;
            border-top: 1.2px solid #cbd5e1;
            width: 75%;
            padding-top: 1px;
            margin-top: 5px;
        }

        .back-signatory-title {
            font-size: 6pt;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
        }

        /* Absolute Overlay for Custom Back Background Template */
        .id-container-back.has-back-template * {
            display: none !important;
        }

        .id-container-back.has-back-template .back-template-overlay,
        .id-container-back.has-back-template .back-template-overlay * {
            display: block !important;
        }

        .back-template-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            pointer-events: none;
            display: none;
        }

        .id-container-back.has-back-template .back-template-overlay {
            display: block;
        }

        .back-template-overlay div {
            position: absolute;
            left: 56.5%;
            font-size: 8pt;
            font-weight: 700;
            color: #000000;
            text-align: left;
            white-space: nowrap;
        }

        /* Absolute offsets to line up perfectly next to background colons */
        .val-height { top: 28.21%; }
        .val-weight { top: 31.97%; }
        .val-tin { top: 35.73%; }
        .val-philhealth { top: 39.49%; }
        .val-birth { top: 43.20%; }
        .val-blood { top: 47.01%; }
        .val-hdmf { top: 50.77%; }
        .val-gsis { top: 55.06%; }

        .val-emerg-name { top: 66.25%; }
        .val-emerg-relation { top: 69.96%; }
        .val-emerg-contact { top: 73.72%; }
        .val-emerg-address {
            top: 77.48%;
            white-space: normal !important;
            width: 40%;
            line-height: 1.1;
            font-size: 7.2pt !important;
        }

        .val-signature-img {
            position: absolute;
            left: 50% !important;
            transform: translateX(-50%) !important;
            top: 83% !important;
            width: 170px !important;
            height: 70px !important;
            object-fit: contain !important;
            mix-blend-mode: multiply !important;
            filter: contrast(3) brightness(1.3) grayscale(1) !important;
            z-index: 15 !important;
        }
        .val-signature-name {
            position: absolute;
            left: 50% !important;
            transform: translateX(-50%) !important;
            top: 90% !important;
            font-size: 8.5pt !important;
            font-weight: 700 !important;
            color: #000000 !important;
            text-align: center !important;
            width: 100% !important;
            white-space: nowrap !important;
        }
        .val-signature-title {
            position: absolute;
            left: 50% !important;
            transform: translateX(-50%) !important;
            top: 93% !important;
            font-size: 6pt !important;
            font-weight: 800 !important;
            color: #64748b !important;
            text-align: center !important;
            width: 100% !important;
            text-transform: uppercase !important;
        }

        /* Front-only / back-only printing */
        body.show-front-only .id-container-back {
            display: none !important;
        }

        body.show-back-only .id-container {
            display: none !important;
        }

        body.show-front-only .print-grid,
        body.show-back-only .print-grid {
            grid-template-columns: repeat(1, 3.5in);
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background-color: white;
            }

            .id-container,
            .id-container-back {
                box-shadow: none;
                border: 0.1mm solid #ddd;
            }

            .print-grid {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <div class="print-menu" id="printMenu">
            <button class="btn-print" type="button" onclick="togglePrintMenu(event)">Print &#9662;</button>
            <div class="print-menu-items">
                <button type="button" onclick="printBoth()"><span class="dot" style="background:#16a34a;"></span>Both Sides</button>
                <button type="button" onclick="printFrontOnly()"><span class="dot" style="background:#2563eb;"></span>Front Only</button>
                <button type="button" onclick="printBackOnly()"><span class="dot" style="background:#7c3aed;"></span>Back Only</button>
            </div>
        </div>
        <form action="{{ route('pds.regenerate-qr', $employee) }}" method="POST" style="display: contents;">
            @csrf
            <button type="submit" class="btn-print" style="background-color: #f59e0b;">Refresh QR (Transparent)</button>
        </form>
        <button class="btn-print" style="background-color: #4b5563;" onclick="window.close()">Close</button>
    </div>

    <div class="print-grid">
        {{-- Front Side --}}
        @php
            $frontPosition = trim((string) ($employee->position_title ?: $employee->office));
            $rawCode = trim((string) $employee->employee_code);
            $rawJob = trim((string) $employee->job_order);
            $isPlaceholder = fn ($v) => $v === '' || in_array(strtolower($v), ['none', 'n/a', 'na', 'n / a', 'no', '-']);
            $frontIdNo = ! $isPlaceholder(strtolower($rawCode)) ? $rawCode : (! $isPlaceholder(strtolower($rawJob)) ? $rawJob : '');
        @endphp
        <div class="id-container">
            <!-- Profile Photo -->
            <div class="photo-container">
                @if ($employee->profile_photo_path)
                    <img src="{{ Storage::url($employee->profile_photo_path) }}">
                @else
                    <img src="{{ asset('assets/profile-placeholder.svg') }}">
                @endif
            </div>

            <!-- Text Content -->
            <div class="name-block">
                <div class="nickname">&ldquo;{{ ucfirst(strtolower($employee->nickname ?: 'NICKNAME')) }}&rdquo;</div>
                <div class="fullname">{{ strtoupper($employee->first_name) }}
                    {{ $employee->middle_name ? strtoupper(substr($employee->middle_name, 0, 1)) . '.' : '' }}
                    {{ strtoupper($employee->surname) }}{{ $employee->name_extension ? ' ' . strtoupper($employee->name_extension) : '' }}
                </div>
            </div>
            <div class="office {{ strlen($frontPosition) > 22 ? 'office-long' : '' }}">{{ $frontPosition ?: 'Position Title' }}</div>

            <!-- QR Code -->
            @if (($showQr ?? true) && $employee->qr_code_path)
                <div class="qr-container">
                    <img src="{{ Storage::url($employee->qr_code_path) }}">
                </div>
            @endif

            <!-- ID Number (hidden together with QR in No-QR mode) -->
            @if (($showQr ?? true))
                <div class="job-order">
                    ID NO: <span>{{ $frontIdNo ?: 'N/A' }}</span>
                </div>
            @endif
        </div>

        {{-- Dynamic Back Side --}}
        @php
            $personal = $employee->personalInformation;
            $family = $employee->familyBackground;
            $other = $employee->otherInformation;

            $birthDate = $personal && $personal->date_of_birth 
                ? $personal->date_of_birth->format('F d, Y') 
                : 'N/A';

            // Resolve height/weight unit displays
            $heightVal = $personal && $personal->height_m ? trim($personal->height_m) : 'N/A';
            if ($heightVal !== 'N/A' && is_numeric($heightVal)) {
                $heightVal .= ' m';
            }
            $weightVal = $personal && $personal->weight_kg ? trim($personal->weight_kg) : 'N/A';
            if ($weightVal !== 'N/A' && is_numeric($weightVal)) {
                $weightVal .= ' kg';
            }

            // Smart Emergency Contact fallback routing
            $emergencyName = 'N/A';
            $emergencyRelation = 'N/A';
            $emergencyContact = 'N/A';
            $emergencyAddress = 'N/A';

            if ($family && ($family->spouse_first_name || $family->spouse_surname)) {
                $spouseMiddleInitial = $family->spouse_middle_name ? strtoupper(substr($family->spouse_middle_name, 0, 1)) . '.' : '';
                $emergencyName = trim(($family->spouse_first_name ?? '') . ' ' . $spouseMiddleInitial . ' ' . ($family->spouse_surname ?? ''));
                $emergencyName = preg_replace('/\s+/', ' ', $emergencyName);
                $emergencyRelation = 'Spouse';
                $emergencyContact = $family->spouse_telephone_no ?: ($personal->mobile_no ?? 'N/A');
                
                $emergencyAddress = $family->spouse_business_address ?: trim(
                    ($personal->residential_house_no ?? '') . ' ' .
                    ($personal->residential_street ?? '') . ' ' .
                    ($personal->residential_subdivision ?? '') . ' ' .
                    ($personal->residential_barangay ?? '') . ' ' .
                    ($personal->residential_city ?? '') . ', ' .
                    ($personal->residential_province ?? '')
                );
                $emergencyAddress = preg_replace('/\s+/', ' ', $emergencyAddress);
                $emergencyAddress = rtrim(trim($emergencyAddress), ',');
            } else {
                $refs = $other->references ?? [];
                $ref = $refs[0] ?? null;
                if ($ref && ($ref['name'] ?? null)) {
                    $emergencyName = $ref['name'];
                    $emergencyRelation = 'Reference';
                    $emergencyContact = $ref['contact'] ?? 'N/A';
                    $emergencyAddress = $ref['address'] ?? 'N/A';
                }
            }

            $signatoryName = strtoupper($employee->first_name) . 
                ($employee->middle_name ? ' ' . strtoupper(substr($employee->middle_name, 0, 1)) . '.' : '') . 
                ' ' . strtoupper($employee->surname) . 
                ($employee->name_extension ? ' ' . strtoupper($employee->name_extension) : '');
            $signatoryName = preg_replace('/\s+/', ' ', $signatoryName);
        @endphp

        <div class="id-container-back {{ \App\Models\IdTemplate::getActiveBackImageUrl() ? 'has-back-template' : '' }}">
            <!-- Header Banner -->
            <div class="back-header-banner">IMPORTANT</div>

            <!-- Terms -->
            <div class="back-terms">
                This ID is non-transferable and shall be confiscated when used by others. Please return to the owner if found.<br>
                Wear this ID Card when entering the Trento Municipal Hall premises and present to guard on duty upon demand.
            </div>

            <!-- PDS Metrics & Government IDs -->
            <div class="back-details-grid">
                <div class="back-details-label">Height</div>
                <div class="back-details-value">: {{ $heightVal }}</div>

                <div class="back-details-label">Weight</div>
                <div class="back-details-value">: {{ $weightVal }}</div>

                <div class="back-details-label">TIN No.</div>
                <div class="back-details-value">: {{ $personal && $personal->tin_no ? $personal->tin_no : 'N/A' }}</div>

                <div class="back-details-label">PhilHealth No.</div>
                <div class="back-details-value">: {{ $personal && $personal->philhealth_no ? $personal->philhealth_no : 'N/A' }}</div>

                <div class="back-details-label">Date of Birth</div>
                <div class="back-details-value">: {{ $birthDate }}</div>

                <div class="back-details-label">Blood Type</div>
                <div class="back-details-value">: {{ $personal && $personal->blood_type ? trim(strtoupper($personal->blood_type), '"\'') : 'N/A' }}</div>

                <div class="back-details-label">HDMF No.</div>
                <div class="back-details-value">: {{ $personal && $personal->pagibig_id_no ? $personal->pagibig_id_no : 'N/A' }}</div>

                <div class="back-details-label">GSIS No.</div>
                <div class="back-details-value">: {{ $personal && $personal->umid_id_no ? $personal->umid_id_no : 'N/A' }}</div>
            </div>

            <!-- Emergency Notify Banner -->
            <div class="back-emergency-banner">IN CASE OF EMERGENCY, PLEASE NOTIFY</div>

            <!-- Emergency Info -->
            <div class="back-emergency-grid">
                <div class="back-emergency-label">Name</div>
                <div class="back-emergency-value">: {{ strtoupper($emergencyName) }}</div>

                <div class="back-emergency-label">Relation</div>
                <div class="back-emergency-value">: {{ $emergencyRelation }}</div>

                <div class="back-emergency-label">Contact No.</div>
                <div class="back-emergency-value">: {{ $emergencyContact }}</div>

                <div class="back-emergency-label">Address</div>
                <div class="back-emergency-value">: {{ $emergencyAddress }}</div>
            </div>

            <!-- Signatory Footer -->
            <div class="back-signatory-section" style="position: relative;">
                @if ($employee->e_signature_path)
                    <div style="position: absolute; bottom: 0px; left: 50%; transform: translateX(-50%); z-index: 5;">
                        <img src="{{ route('profile.public.signature', $employee) }}" style="width: 170px; height: 70px; object-fit: contain; mix-blend-mode: multiply; filter: contrast(3) brightness(1.3) grayscale(1);">
                    </div>
                    <div class="back-signatory-name" style="text-transform: uppercase; margin-top: 55px;">{{ $signatoryName }}</div>
                @else
                    <div class="back-signature">Alberto Salas</div>
                    <div class="back-signatory-name">ALBERTO M. SALAS</div>
                    <div class="back-signatory-title">MUNICIPAL GOVERNMENT DEPARTMENT HEAD / HRMO</div>
                @endif
            </div>

            <!-- Absolute Overlay for Custom Back Background Template -->
            <div class="back-template-overlay">
                <div class="val-height">{{ $heightVal }}</div>
                <div class="val-weight">{{ $weightVal }}</div>
                <div class="val-tin">{{ $personal && $personal->tin_no ? $personal->tin_no : 'N/A' }}</div>
                <div class="val-philhealth">{{ $personal && $personal->philhealth_no ? $personal->philhealth_no : 'N/A' }}</div>
                <div class="val-birth">{{ $birthDate }}</div>
                <div class="val-blood">{{ $personal && $personal->blood_type ? trim(strtoupper($personal->blood_type), '"\'') : 'N/A' }}</div>
                <div class="val-hdmf">{{ $personal && $personal->pagibig_id_no ? $personal->pagibig_id_no : 'N/A' }}</div>
                <div class="val-gsis">{{ $personal && $personal->umid_id_no ? $personal->umid_id_no : 'N/A' }}</div>

                <div class="val-emerg-name">{{ strtoupper($emergencyName) }}</div>
                <div class="val-emerg-relation">{{ $emergencyRelation }}</div>
                <div class="val-emerg-contact">{{ $emergencyContact }}</div>
                <div class="val-emerg-address">{{ $emergencyAddress }}</div>

                @if ($employee->e_signature_path)
                    <img class="val-signature-img" src="{{ route('profile.public.signature', $employee) }}">
                    <div class="val-signature-name">{{ $signatoryName }}</div>
                @endif
            </div>
        </div>
    </div>
    <script>
        function togglePrintMenu(e) {
            e.stopPropagation();
            document.getElementById('printMenu').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            const m = document.getElementById('printMenu');
            if (m && m.classList.contains('open') && !m.contains(e.target)) m.classList.remove('open');
        });
        function printBoth() {
            document.body.classList.remove('show-front-only', 'show-back-only');
            window.print();
        }
        function printFrontOnly() {
            document.body.classList.remove('show-back-only');
            document.body.classList.add('show-front-only');
            window.print();
        }
        function printBackOnly() {
            document.body.classList.remove('show-front-only');
            document.body.classList.add('show-back-only');
            window.print();
        }
        window.addEventListener('afterprint', function() {
            document.body.classList.remove('show-front-only', 'show-back-only');
        });
    </script>
</body>

</html>