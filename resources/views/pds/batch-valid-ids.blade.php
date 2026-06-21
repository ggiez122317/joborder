<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Batch Valid ID Cards</title>
    <style>
        @import url('https://fonts.cdnfonts.com/css/lemon-milk');
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

        .print-grid {
            display: grid;
            grid-template-columns: repeat(2, 2.125in);
            gap: 8mm;
            justify-content: center;
            padding: 10mm;
            page-break-after: always;
        }

        .print-grid:last-of-type {
            page-break-after: avoid;
        }

        /* Valid ID Size (CR80 Portrait Dimensions) */
        .id-container {
            width: 2.125in;
            height: 3.375in;
            background-image: url('{{ \App\Models\IdTemplate::getActiveImageUrl() }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border: 1px solid #cbd5e1;
            box-sizing: border-box;
        }

        /* Profile Photo for CR80 */
        .photo-container {
            position: absolute;
            top: 72px;
            left: 50%;
            transform: translateX(-50%);
            width: 95px;
            height: 95px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            border: none; /* Removed circle border */
        }

        .id-container.has-front-template .front-signatory-container {
            display: none !important;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Text Elements scaled for CR80 */
        .nickname,
        .fullname,
        .office {
            position: absolute;
            width: 100%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #000;
            z-index: 5;
            padding: 0 8px;
            box-sizing: border-box;
        }

        .nickname {
            top: 180px;
            font-family: 'LEMON MILK', sans-serif;
            font-size: 11pt;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: 0.3px;
        }

        .fullname {
            top: 205px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 13pt;
            font-weight: 400;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: 0.5px;
        }

        .office {
            top: 223px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 9pt;
            font-weight: 400;
            text-transform: uppercase;
            color: #dc2626;
            letter-spacing: 0.5px;
        }

        /* QR Code & Job Order scaled for CR80 */
        .qr-container {
            position: absolute;
            bottom: 17.5px;
            left: 26.5px;
            width: 39px;
            height: 39px;
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
            bottom: 65px;
            left: 14px;
            width: 60px;
            text-align: center;
            color: #000;
            font-weight: 900;
            font-size: 4pt;
            letter-spacing: -0.1px;
        }

        /* Signatory Front overlay scaled for CR80 */
        .front-signatory-container {
            position: absolute;
            bottom: 12px;
            right: 18px;
            width: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            z-index: 5;
        }

        .front-signature-img {
            font-family: 'Alex Brush', cursive;
            font-size: 14pt;
            color: #1e3a8a;
            margin-bottom: -10px;
            transform: rotate(-3deg);
            user-select: none;
        }

        .front-signatory-line {
            width: 100%;
            border-top: 0.8px solid #cbd5e1;
            margin-top: 4px;
        }

        .front-signatory-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 6pt;
            color: #0f172a;
            letter-spacing: 0.3px;
            margin-top: 1px;
            line-height: 1;
        }

        .front-signatory-title {
            font-size: 3pt;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1;
        }

        /* ID Card Back Styles for CR80 */
        .id-container-back {
            width: 2.125in;
            height: 3.375in;
            background-color: #ffffff;
            @if (\App\Models\IdTemplate::getActiveBackImageUrl())
                background-image: url('{{ \App\Models\IdTemplate::getActiveBackImageUrl() }}');
            @else
                background-image: url('{{ \App\Models\IdTemplate::getActiveImageUrl() }}');
            @endif
            background-size: 100% 100%;
            background-repeat: no-repeat;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border: 1px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            padding: 10px;
            box-sizing: border-box;
            color: #1e293b;
        }

        .back-header-banner {
            background-color: #dc2626;
            color: #ffffff;
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 10pt;
            letter-spacing: 1px;
            padding: 1.5px 0;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .back-terms {
            font-size: 4pt;
            line-height: 1.2;
            text-align: center;
            color: #475569;
            margin-bottom: 6px;
            padding: 0 2px;
            font-weight: 500;
        }

        .back-details-grid {
            display: grid;
            grid-template-columns: 52px 1fr;
            row-gap: 1.8px;
            column-gap: 3px;
            margin-bottom: 6px;
            font-size: 5pt;
            background-color: #f8fafc;
            padding: 4px 6px;
            border-radius: 4px;
            border: 0.5px solid #f1f5f9;
        }

        .back-details-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 4.5pt;
        }

        .back-details-value {
            font-weight: 600;
            color: #0f172a;
        }

        .back-emergency-banner {
            background-color: #dc2626;
            color: #ffffff;
            text-align: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 6.5pt;
            letter-spacing: 0.5px;
            padding: 1.5px 0;
            border-radius: 4px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .back-emergency-grid {
            display: grid;
            grid-template-columns: 38px 1fr;
            row-gap: 1.8px;
            column-gap: 3px;
            font-size: 4.8pt;
            padding: 0 2px;
            margin-bottom: 5px;
        }

        .back-emergency-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 4.2pt;
        }

        .back-emergency-value {
            font-weight: 600;
            color: #0f172a;
            line-height: 1.1;
        }

        .back-signatory-section {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-bottom: 1px;
        }

        .back-signature {
            font-family: 'Alex Brush', cursive;
            font-size: 15pt;
            color: #1d4ed8;
            margin-bottom: -9px;
            z-index: 2;
            transform: rotate(-3deg);
            user-select: none;
        }

        .back-signatory-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 8pt;
            color: #0f172a;
            letter-spacing: 0.5px;
            border-top: 0.8px solid #cbd5e1;
            width: 80%;
            padding-top: 0.5px;
            margin-top: 3px;
        }

        .back-signatory-title {
            font-size: 3.5pt;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 0.5px;
            line-height: 1;
        }

        /* ----------------------------------------------------
           SAFEGUARD HIDING RULES & OVERLAYS FOR BACK TEMPLATES
           ---------------------------------------------------- */
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
            font-size: 5pt;
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
            font-size: 4.5pt !important;
        }

        .val-signature-img {
            position: absolute;
            left: 50% !important;
            transform: translateX(-50%) !important;
            top: 78.5% !important;
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
        <button class="btn-print" onclick="window.print()">Print Batch ID Cards</button>
        <button class="btn-print" style="background-color: #4b5563;" onclick="window.close()">Close</button>
    </div>

    @foreach ($employees as $employee)
        @php
            $personal = $employee->personalInformation;
            $family = $employee->familyBackground;
            $other = $employee->otherInformation;

            $birthDate = $personal && $personal->date_of_birth 
                ? \Carbon\Carbon::parse($personal->date_of_birth)->format('F d, Y') 
                : 'N/A';

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

        <div class="print-grid">
            <!-- Front Side -->
            <div class="id-container {{ \App\Models\IdTemplate::getActiveImageUrl() ? 'has-front-template' : '' }}">
                <!-- Profile Photo -->
                <div class="photo-container">
                    @if ($employee->profile_photo_path)
                        <img src="{{ asset('storage/' . $employee->profile_photo_path) }}" alt="Profile Photo">
                    @else
                        <div style="width: 100%; height: 100%; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #475569; font-weight: bold; font-size: 7pt; font-family: 'Bebas Neue', sans-serif;">
                            <span style="font-size: 14px; margin-bottom: 2px;">👤</span>
                            NO PHOTO
                        </div>
                    @endif
                </div>

                <!-- Nickname -->
                <div class="nickname">"{{ $employee->nickname ?: strtok($employee->first_name, ' ') }}"</div>

                <!-- Full Name -->
                <div class="fullname">
                    {{ strtoupper($employee->first_name) }}
                    {{ $employee->middle_name ? strtoupper(substr($employee->middle_name, 0, 1)) . '.' : '' }}
                    {{ strtoupper($employee->surname) }}{{ $employee->name_extension ? ' ' . strtoupper($employee->name_extension) : '' }}
                </div>

                <!-- Department Office -->
                <div class="office">{{ $employee->office ?: 'LGU TRENTO' }}</div>

                <!-- Job Order -->
                <div class="job-order">{{ $employee->job_order ?: 'JO-TRN-2026' }}</div>

                <!-- QR Code -->
                <div class="qr-container">
                    @if($employee->qr_code_path)
                        <img src="{{ asset('storage/' . $employee->qr_code_path) }}" alt="QR Code">
                    @endif
                </div>

                <!-- Front Signatory Block -->
                <div class="front-signatory-container">
                    <div class="front-signature-img">Kristoffer Calvez</div>
                    <div class="front-signatory-line"></div>
                    <div class="front-signatory-name">KRISTOFFER E. CALVEZ</div>
                    <div class="front-signatory-title">MUNICIPAL MAYOR</div>
                </div>
            </div>

            <!-- Back Side -->
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
    @endforeach
</body>

</html>
